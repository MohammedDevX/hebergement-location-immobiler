<?php 
session_start();
require "../includes/connection.php";

// Déterminer le type d'utilisateur et l'ID basé sur les paramètres
if (isset($_GET['id_hote']) && !empty($_GET['id_hote'])) {
    $user_id = $_GET['id_hote'];
    $user_type = 'hote';
} elseif (isset($_GET['id_locataire']) && !empty($_GET['id_locataire'])) {
    $user_id = $_GET['id_locataire'];
    $user_type = 'locataire';
} else {
    header("Location: accueille.php");
    exit();
}

// Récupérer les données utilisateur
if ($user_type === 'hote') {
    // Pour un hôte, on récupère les données depuis la table locataire via la jointure
    $user_query = "SELECT l.*, h.id_hote, h.created_at as hote_created_at 
                   FROM hote h 
                   JOIN locataire l ON h.id_locataire = l.id_locataire 
                   WHERE h.id_hote = :id";
} else {
    // Pour un locataire, on récupère directement depuis la table locataire
    $user_query = "SELECT * FROM locataire WHERE id_locataire = :id";
}
$user_stmt = $conn->prepare($user_query);
$user_stmt->bindParam(':id', $user_id);
$user_stmt->execute();
$user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$user_data) {
    header("Location: accueille.php");
    exit();
}

// Nombre d'avis pour ce profil
if ($user_type === 'hote') {
    // Pour un hôte, compter les avis sur ses annonces
    $review_query = "SELECT count(*) as nbr FROM avis a 
                     JOIN annonce an ON a.id_annonce = an.id_annonce 
                     WHERE an.id_hote = :id_user";
} else {
    // Pour un locataire, compter ses avis
    $review_query = "SELECT count(*) as nbr FROM avis WHERE id_locataire = :id_user";
}
$review_stmt = $conn->prepare($review_query);
$review_stmt->bindParam(":id_user", $user_id);
$review_stmt->execute();
$review_data = $review_stmt->fetch(PDO::FETCH_ASSOC);

// Calculer le temps sur la plateforme
// Pour un hôte, on utilise la date de création du compte hôte, sinon celle du locataire
$date_creation = $user_type === 'hote' && isset($user_data['hote_created_at']) 
    ? $user_data['hote_created_at'] 
    : $user_data['created_at'];

$date_inscription = new DateTime($date_creation);
$date_actuelle = new DateTime();
$interval = $date_inscription->diff($date_actuelle);
$mois_sur_plateforme = $interval->m + ($interval->y * 12);
if ($mois_sur_plateforme == 0) {
    $mois_sur_plateforme = 1; // Au minimum 1 mois
}

// Image de profil
$profile_image = $user_data['photo_profil'] ? '../' . $user_data['photo_profil'] : 'https://placehold.co/80x80';

// Déterminer le titre de la page
$page_title = $user_type === 'hote' ? 'Profil Hôte' : 'Profil Locataire';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/94d90b0a3f.js" crossorigin="anonymous"></script>
</head>
<body class="font-[Grotesk] bg-[#fafafa]">
    
    <div class="navbar-container">
        <?php include "../includes/navbar.php" ?>
    </div>
    
    <div class="container mx-auto px-4 py-8 my-30">
        <!-- Bouton retour -->
        
        <!-- Carte centrée avec une largeur max -->
        <div class="flex justify-center items-center">
            <div class="rounded-xl shadow-sm bg-white p-6 max-w-md w-full">
                <div class="flex flex-col items-center text-center">
                    <img
                        src="<?php echo $profile_image; ?>"
                        alt="Profile Picture"
                        class="size-20 rounded-full object-cover mb-4"
                    />
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($user_data["prenom"] ?? '') . ' ' . htmlspecialchars($user_data["nom"] ?? ''); ?></h2>
                    <span class="text-sm mb-2 text-gray-500">@<?php echo htmlspecialchars($user_data["nom_user"] ?? 'utilisateur'); ?></span>
                    
                    <!-- Badge du type d'utilisateur -->
                    <?php if ($user_type === 'hote'): ?>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            <i class="fas fa-home mr-1"></i>Hôte
                        </span>
                    <?php else: ?>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            <i class="fas fa-user mr-1"></i>Locataire
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-center gap-6 mt-6">
                    <div class="text-center">
                        <span class="block text-lg font-bold"><?php echo $review_data["nbr"]; ?></span>
                        <span class="text-xs text-gray-500">
                            <?php echo $user_type === 'hote' ? 'Avis reçus' : 'Avis donnés'; ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <span class="block text-lg font-bold"><?php echo $mois_sur_plateforme; ?></span>
                        <span class="text-xs text-gray-500">
                            Mois <?php echo $user_type === 'hote' ? 'en tant qu\'hôte' : 'sur la plateforme'; ?>
                        </span>
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Email:</span>
                            <span><?php echo htmlspecialchars($user_data["email"] ?? 'Non renseigné'); ?></span>
                        </div>
                        <?php if (!empty($user_data["tel"])): ?>
                        <div class="flex justify-between">
                            <span>Téléphone:</span>
                            <span><?php echo htmlspecialchars($user_data["tel"]); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span><?php echo $user_type === 'hote' ? 'Hôte depuis:' : 'Membre depuis:'; ?></span>
                            <span><?php echo date('d/m/Y', strtotime($date_creation)); ?></span>
                        </div>
                        <?php if ($user_type === 'hote' && $user_data['created_at'] !== $user_data['hote_created_at']): ?>
                        <div class="flex justify-between">
                            <span>Locataire depuis:</span>
                            <span><?php echo date('d/m/Y', strtotime($user_data['created_at'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <?php include "../includes/footer.html" ?> 
    </div>

    <script>
        // Script pour améliorer l'expérience utilisateur
        document.addEventListener('DOMContentLoaded', function() {
            // Animation simple pour les éléments lors du chargement
            const cards = document.querySelectorAll('.bg-white');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>