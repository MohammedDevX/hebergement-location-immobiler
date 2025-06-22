<?php 
session_start();

require "../includes/connection.php";

// Système de notification
$notificationHTML = "";
$hasNotification = false;
$type = "";
$message = "";

// Vérifier s'il y a une notification dans les cookies
if (isset($_COOKIE["notif"])) {
    $message = $_COOKIE["notif"];
    $hasNotification = true;
    
    // Déterminer le type de notification basé sur le contenu du message
    $type = "success"; // Par défaut
    if (strpos($message, "Erreur") !== false || strpos($message, "introuvable") !== false) {
        $type = "error";
    }
    
    // Supprimer le cookie après l'avoir utilisé
    setcookie("notif", "", 1);
}

// Vérifier s'il y a une notification dans la session (pour la réinitialisation de mot de passe)
if (isset($_SESSION['notification'])) {
    $hasNotification = true;
    $type = $_SESSION['notification']['type'];
    $message = $_SESSION['notification']['message'];
    
    // Supprimer la notification après l'avoir récupérée
    unset($_SESSION['notification']);
}

$query = "SELECT p.photo, a.id_annonce, v.nom_ville, l.nom, l.prenom, a.prix_nuit
        FROM annonce a
        INNER JOIN (
            SELECT id_annonce, MIN(id_photo) as min_photo_id
            FROM photos
            GROUP BY id_annonce
        ) as first_photos ON a.id_annonce = first_photos.id_annonce
        INNER JOIN photos p ON p.id_photo = first_photos.min_photo_id
        INNER JOIN ville v ON a.id_ville = v.id_ville
        INNER JOIN hote h ON a.id_hote = h.id_hote
        INNER JOIN locataire l ON h.id_locataire = l.id_locataire";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaNook - Accueil</title>
    <script src="https://kit.fontawesome.com/5300af1428.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .background {
            height: 1000px;
            background-image: url('../assets/images/Bg.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        @keyframes moveUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .search-bar {
            animation: moveUp 1s ease-out;
        }
        
        /* Styles pour les notifications */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
            max-width: 400px;
            width: 350px;
            transform: translateY(100px);
            transition: transform 0.3s ease-in-out;
            opacity: 0; /* Ajout de l'opacité à 0 par défaut */
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1; /* Rendre visible avec l'opacité */
        }
        
        .notification-success {
            background-color: #10b981;
        }
        
        .notification-error {
            background-color: #ef4444;
        }
        
        .notification-icon {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .notification-message {
            flex-grow: 1;
            font-size: 14px;
        }
        
        .notification-close {
            margin-left: 12px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        /* Responsive pour petits écrans */
        @media (max-width: 480px) {
            .notification {
                width: calc(100% - 40px);
                right: 20px;
                left: 20px;
            }
        }
    </style>
</head>
<body>
    <?php if ($hasNotification): ?>
    <!-- Notification -->
    <div id="notification" class="notification notification-<?php echo $type; ?>">
        <div class="notification-icon">
            <?php if ($type === 'success'): ?>
                <i class="fas fa-check-circle"></i>
            <?php else: ?>
                <i class="fas fa-exclamation-circle"></i>
            <?php endif; ?>
        </div>
        <div class="notification-message"><?php echo $message; ?></div>
        <div class="notification-close" onclick="closeNotification()">
            <i class="fas fa-times"></i>
        </div>
    </div>
    <!-- Message de débogage temporaire -->
    <script>console.log("Notification détectée: <?php echo $type; ?> - <?php echo addslashes($message); ?>");</script>
    <?php else: ?>
    <!-- Message de débogage temporaire -->
    <script>console.log("Aucune notification détectée");</script>
    <?php endif; ?>

    <div class="background">
        <!-- NavBar -->
        <div class="navbar-container">
            <?php include "../includes/navbar.php" ?>
        </div>
        <!-- NavBar -->
        
        <!-- Search Bar -->
        <div class="mx-auto font-[Krylon] text-md max-w-7xl sm:px-6 lg:px-8 search-bar">
            <div class="relative isolate overflow-hidde px-6 py-20 text-center sm:px-16">
                <form action="results.php" method="post">
                    <label
                        class="mx-auto mt-[300px] relative bg-white max-w-2xl flex flex-col md:flex-row items-center justify-center py-2 px-2 rounded-2xl gap-2 focus-within:border-gray-300"
                        for="search-bar">
                        <input id="search-bar" placeholder="your keyword here" name="results"
                            class="px-6 py-2 w-full rounded-md flex-1 outline-none bg-white" required="">
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 bg-[#005555] text-white fill-white active:scale-95 duration-100 border will-change-transform overflow-hidden relative rounded-xl transition-all">
                            <div class="flex items-center transition-all">
                                <span class="text-lg font-semibold whitespace-nowrap truncate mx-auto">
                                    Search
                                </span>
                            </div>
                        </button>
                    </label>
                </form>
            </div>
        </div>
        <!-- Search Bar -->
    </div>

    <div class="bg-white w-full font-[Grotesk]">
        <div class="mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:max-w-8xl">
            <h2 class="text-6xl pl-10 font-[Krylon] tracking-tight text-gray-900">Proprietes</h2>
            
            <div class="mt-10 grid px- grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-1 xl:grid-cols-4 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
                <!-- Start Propr... -->
                <?php for ($i=0; $i<$stmt->rowCount(); $i++) { ?>
                <div class="group relative">
                    <img src="<?php echo $data[$i]["photo"] ?>" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php?id=<?php echo $data[$i]["id_annonce"] ?>">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    <?php echo $data[$i]["nom_ville"] ?>, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By <?php echo $data[$i]["nom"] ?> <?php echo $data[$i]["prenom"] ?></p>
                            <p class="text-sm font-bold text-gray-900">MAD <?php echo number_format($data[$i]["prix_nuit"], 0, ',', ',') ?> night</p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div><?php include "../includes/footer.html" ?></div>
    <!-- Footer -->

    <!-- Script pour les notifications -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    
    if (notification) {
        // Afficher la notification immédiatement
        notification.classList.add('show');
        
        // Fermer automatiquement après 5 secondes
        setTimeout(function() {
            closeNotification();
        }, 5000);
    }
});

function closeNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.classList.remove('show');
        
        // Supprimer l'élément après la fin de l'animation
        setTimeout(function() {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}
</script>
</body>
</html>
