<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="testLogin.php" method="post">
        username : <input type="text" name="nom_user" required > <br>
        password : <input type="password" name="password" required> <br>
        <input type="submit" value="login"> <br>
        <a href="comfirmeEmail.html">Mot de passe oubliée</a>
    </form>
        <?php
        // Afficheges des erreurs : 
    if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
        echo '<div class="errors" style="color: red; border: 1px solid red; padding: 10px;">';
        foreach ($_SESSION['errors'] as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
        // Supprimer les erreurs apres les affiches :
        unset($_SESSION['errors']);
    }
    ?>
</body>
</html>