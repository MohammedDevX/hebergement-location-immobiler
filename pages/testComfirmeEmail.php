<?php
require "../libraries/phpmailer/mail.php";
require "../includes/connection.php";

// Sécurise les données saisies
$email = $_POST['email'] ?? '';

$query = "SELECT * FROM locataire WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(":email", $email);
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    // Créer le lien avec l'email (ou avec un token plus tard)
    $resetLink = "http://localhost/hebergement-entreparticulier/pages/updatePassword.php?email=" . urlencode($email);

    // Configuration de l'envoi de mail
    $mail->setFrom("itsbakhtaouimohammed@gmail.com", "Bakhtaoui Mohammed");
    $mail->addAddress($email); // ENVOIE À L'UTILISATEUR et non à toi-même
    $mail->Subject = "Lien de réinitialisation du mot de passe";
    $mail->Body = "Cliquez ici pour réinitialiser votre mot de passe : <a href='$resetLink'>Réinitialiser</a>";
    $mail->AltBody = "Cliquez ici pour réinitialiser votre mot de passe : $resetLink";

    if ($mail->send()) {
        echo "Un lien de réinitialisation a été envoyé à votre email.";
    } else {
        echo "Erreur d'envoi de l'email.";
    }
} else {
    echo "Email introuvable dans notre base de données.";
}
?>
