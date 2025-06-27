<?php 
session_start();
if (isset($_SESSION["user_id"])) {
    require "../includes/connection.php";
    
    // Traitement de la mise à jour du profil
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $nom_user = $_POST['nom_user'];
        $tel = $_POST['tel'];
        $user_id = $_SESSION["user_id"]["id_locataire"];
        
        // Gestion de l'upload d'image
        $image_path = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/profiles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $image_path = 'uploads/profiles/' . $new_filename;
            }
        }
        
        // Mise à jour des données
        if ($image_path) {
            $update_query = "UPDATE locataire SET nom = :nom, prenom = :prenom, email = :email, nom_user = :nom_user, tel = :tel, photo_profil = :image, updated_at = CURRENT_TIMESTAMP WHERE id_locataire = :id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':image', $image_path);
        } else {
            $update_query = "UPDATE locataire SET nom = :nom, prenom = :prenom, email = :email, nom_user = :nom_user, tel = :tel, updated_at = CURRENT_TIMESTAMP WHERE id_locataire = :id";
            $update_stmt = $conn->prepare($update_query);
        }
        
        $update_stmt->bindParam(':nom', $nom);
        $update_stmt->bindParam(':prenom', $prenom);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':nom_user', $nom_user);
        $update_stmt->bindParam(':tel', $tel);
        $update_stmt->bindParam(':id', $user_id);
        
        if ($update_stmt->execute()) {
            // Mettre à jour la session
            $_SESSION["user_id"]["nom"] = $nom;
            $_SESSION["user_id"]["prenom"] = $prenom;
            $_SESSION["user_id"]["email"] = $email;
            $_SESSION["user_id"]["nom_user"] = $nom_user;
            $_SESSION["user_id"]["tel"] = $tel;
            if ($image_path) {
                $_SESSION["user_id"]["photo_profil"] = $image_path;
            }
            $success_message = "Profil mis à jour avec succès!";
        } else {
            $error_message = "Erreur lors de la mise à jour du profil.";
        }
    }
    
    // Récupérer les données utilisateur actuelles
    $user_query = "SELECT * FROM locataire WHERE id_locataire = :id";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bindParam(':id', $_SESSION["user_id"]["id_locataire"]);
    $user_stmt->execute();
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Nombre d'avis
    $review_query = "SELECT count(*) as nbr FROM avis WHERE id_locataire = :id_locataire";
    $review_stmt = $conn->prepare($review_query);
    $review_stmt->bindParam(":id_locataire", $_SESSION["user_id"]["id_locataire"]);
    $review_stmt->execute();
    $review_data = $review_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calculer le temps sur la plateforme
    $date_inscription = new DateTime($user_data['created_at']);
    $date_actuelle = new DateTime();
    $interval = $date_inscription->diff($date_actuelle);
    $mois_sur_plateforme = $interval->m + ($interval->y * 12);
    if ($mois_sur_plateforme == 0) {
        $mois_sur_plateforme = 1; // Au minimum 1 mois
    }
    
    // Image de profil
    $profile_image = $user_data['photo_profil'] ? '../' . $user_data['photo_profil'] : 'https://placehold.co/80x80';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/94d90b0a3f.js" crossorigin="anonymous"></script>
</head>
<body class="font-[Grotesk] bg-[#fafafa]">
    <?php if (isset($success_message)): ?>
        <div class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-md z-50" id="success-message">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-md z-50" id="error-message">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- PopUp edit Profile -->
    <input type="radio" id="editPrfl" name="Modal" class="peer/editPrfl hidden">
    <label for="hide" class="hidden peer-checked/editPrfl:block fixed inset-0 z-40 custom-overlay"></label>
    <div class="font-[Grotesk] hidden fixed inset-0 peer-checked/editPrfl:flex items-center justify-center min-h-screen z-50 overflow-y-auto pointer-events-none">
        <div class="bg-white shadow-md rounded-lg p-6 w-[90%] max-w-lg pointer-events-auto overflow-y-auto max-h-screen">
            <h1 class="font-[Krylon] text-4xl text-gray-800 mb-4 text-center">Modifier Votre Info</h1>
            <p class="text-gray-600 text-sm mb-6 text-center">
                Entrez les nouvelles informations.
            </p>
            <div class="flex flex-col items-center text-center">
                <!-- Image with Label -->
                <label for="profileImageInput" class="cursor-pointer relative mb-4 block">
                    <!-- Image -->
                    <img
                        src="<?php echo $profile_image; ?>"
                        alt="Profile Picture"
                        class="size-20 rounded-full object-cover peer"
                        id="profileImagePreview"
                    />
                    
                    <!-- Overlay that appears on hover -->
                    <div class="absolute inset-0 hidden peer-hover:flex hover:flex bg-black/50 rounded-full items-center justify-center text-white text-sm font-medium">
                        Changer
                    </div>
                    
                    <!-- Hidden File Input -->
                    <input
                        type="file"
                        id="profileImageInput"
                        name="profile_image"
                        accept="image/*"
                        class="hidden"
                        form="profile-form"
                    />
                </label>
            </div>
            
            <form id="profile-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="mb-6">
                    <label for="nom" class="block font-medium text-lg text-gray-700">Nom</label>
                    <input 
                        type="text" 
                        id="nom" 
                        placeholder="Entrez votre nom" 
                        name="nom"
                        value="<?php echo htmlspecialchars($user_data['nom'] ?? ''); ?>"
                        pattern="[A-Za-z\s]{2,30}"
                        required
                        class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="prenom" class="block font-medium text-lg text-gray-700">Prénom</label>
                    <input 
                        type="text" 
                        id="prenom" 
                        placeholder="Entrez votre prénom" 
                        name="prenom"
                        value="<?php echo htmlspecialchars($user_data['prenom'] ?? ''); ?>"
                        pattern="[A-Za-z\s]{2,30}"
                        required
                        class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="email" class="block font-medium text-lg text-gray-700">Adresse Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        placeholder="Entrez votre email" 
                        name="email"
                        value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>"
                        required
                        class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="nom_user" class="block font-medium text-lg text-gray-700">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        id="nom_user" 
                        placeholder="Entrez votre nom d'utilisateur"
                        name="nom_user"
                        value="<?php echo htmlspecialchars($user_data['nom_user'] ?? ''); ?>"
                        required
                        class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="tel" class="block font-medium text-lg text-gray-700">Numéro de téléphone</label>
                    <input 
                        type="tel"
                        id="tel" 
                        placeholder="Entrez votre numéro de téléphone"
                        pattern="^\+?[0-9\s\-\(\)]{8,20}$"
                        name="tel"
                        value="<?php echo htmlspecialchars($user_data['tel'] ?? ''); ?>"
                        class="mt-1 text-md p-1.5 block w-full border-gray-300 bg-[#13868623] rounded-md shadow-sm focus:outline-none ring-0 focus:ring-2 focus:ring-nova"
                    >
                </div>
                
                <div class="mb-4">
                    <button type="submit" class="w-full px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-opacity-60 hover:cursor-pointer">Confirmer</button>
                </div>
            </form>
            
            <div class="flex justify-center items-center">
                <label for="hide" class="border-t-1 pt-4 text-center text-gray-700 text-sm hover:underline px-9 hover:opacity-80 hover:cursor-pointer">Fermer</label>
            </div>
        </div>
    </div>

    <!-- PopUp edit Profile -->
    <input type="radio" id="hide" name="Modal" class="peer/hide hidden" checked>
    
    <div class="navbar-container">
        <?php include "../includes/navbar.php" ?>
    </div>
    
    <div class="container mx-auto px-4 py-8 my-30">
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
                    <label for="editPrfl" class="bg-[#005555] text-white px-4 py-2 rounded-md hover:bg-[#0b574fe2] cursor-pointer">
                        Modifier le profil
                    </label>
                </div>
                <div class="flex justify-center gap-6 mt-6">
                    <div class="text-center">
                        <span class="block text-lg font-bold"><?php echo $review_data["nbr"]; ?></span>
                        <span class="text-xs text-gray-500">Avis</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-lg font-bold"><?php echo $mois_sur_plateforme; ?></span>
                        <span class="text-xs text-gray-500">Mois sur la plateforme</span>
                    </div>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Email:</span>
                            <span><?php echo htmlspecialchars($user_data["email"] ?? 'Non renseigné'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Téléphone:</span>
                            <span><?php echo htmlspecialchars($user_data["tel"] ?? 'Non renseigné'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Membre depuis:</span>
                            <span><?php echo date('d/m/Y', strtotime($user_data["created_at"])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <?php include "../includes/footer.html" ?> 
    </div>

    <script>
        // Preview de l'image sélectionnée
        document.getElementById('profileImageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Masquer les messages après 5 secondes
        setTimeout(function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            if (successMsg) successMsg.style.display = 'none';
            if (errorMsg) errorMsg.style.display = 'none';
        }, 5000);

        // Fermer automatiquement le popup après mise à jour réussie
        <?php if (isset($success_message)): ?>
        setTimeout(function() {
            document.getElementById('hide').checked = true;
        }, 2000);
        <?php endif; ?>
    </script>
    <script src="../assets/js/profile.js"></script>
</body>
</html>
<?php 
} else {
    header("Location: acceuille.php");
    exit();
} 
?>