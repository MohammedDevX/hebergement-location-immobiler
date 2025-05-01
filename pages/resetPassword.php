<?php
include "../includes/connection.php";

// Vérifier que les champs sont bien remplis
if (isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hasher le mot de passe

    // Mettre à jour le mot de passe
    $query = "UPDATE locataire SET mot_passe = :mot_passe WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":mot_passe", $newPassword);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount()) {
        echo "Mot de passe mis à jour avec succès. <a href='login.php'>Se connecter</a>";
        print_r($_POST);
    } else {
        echo "Une erreur est survenue ou aucun changement n'a été effectué.";
    }
} else {
    echo "Veuillez remplir tous les champs.";
}
?>
