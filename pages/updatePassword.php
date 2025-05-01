<form action="resetPassword.php" method="post">
    <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
    Nouveau mot de passe : <input type="password" name="password"><br>
    <input type="submit" value="Changer le mot de passe">
</form>
<?php 
print_r($_GET);