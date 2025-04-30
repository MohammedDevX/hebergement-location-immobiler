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
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); // Supprimer les erreurs après affichage ?>
    <?php endif; ?>

    <form action="testSignup.php" method="POST" enctype="multipart/form-data">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"><br>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>"><br>

        <label>Email :</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"><br>

        <label>Mot de passe :</label>
        <input type="password" name="mot_passe"><br>

        <label>Téléphone :</label>
        <input type="text" name="tel" value="<?php echo htmlspecialchars($_POST['tel'] ?? ''); ?>"><br>

        <label>Photo de profil :</label>
        <input type="file" name="photo_profil" accept="image/*"><br>

        <label>Date de naissance :</label>
        <input type="date" name="date_naissance" value="<?php echo htmlspecialchars($_POST['date_naissance'] ?? ''); ?>"><br><br>

        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
