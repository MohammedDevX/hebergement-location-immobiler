<?php
session_start(); // Démarre la session pour accéder aux erreurs
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Formulaire d'inscription</h2>

    <!-- Affichage des erreurs -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            echo '<div class="errors" style="color: red; border: 1px solid red; padding: 10px;">';
            echo "<p>".$_SESSION['errors'][0]."</p>";
            echo '</div>';
            // Supprimer les erreurs apres les affiches :
            unset($_SESSION['errors']);
        }?>

<!-- Il faut utiliser enctype="multipart/form-data" pour encoder le fichier image etc et l'envoyer comme code binaire -->
<!-- php recoie les fichiers dans le var super globale $_FILES[] -->
    <form action="testSignup.php" method="POST" enctype="multipart/form-data"> 
        <label>Nom utilisateur : </label>
        <input type="text" name="nom_user" required> <br>
        <label>Nom :</label>
        <input type="text" name="nom" required><br>
        <label>Prénom :</label>
        <input type="text" name="prenom" required><br>
        <label>Email :</label>
        <input type="text" name="email" required><br>
        <label>Mot de passe :</label>
        <input type="password" name="mot_passe" required><br>
        <label>Téléphone :</label>
        <input type="text" name="tel" required><br>
        <label>Photo de profil :</label>
        <input type="file" name="photo_profil" accept="image/*"><br>
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
