<?php
require "../libraries/phpmailer/mail.php";
require "../includes/connection.php";

if (isset($_POST["email"])) {
    $email = $_POST['email'] ?? '';

    $query = "SELECT * FROM utilisateur WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Créer le lien avec l'email (ou avec un token plus tard)
        $resetLink = "http://localhost/hebergement-entreparticulier/pages/updatePassword.php?email=" . urlencode($email); // Cette methode utiliser pour encoder un string pour utiliser dans un url 

        // Configuration de l'envoi de mail
        $mail->setFrom("itsbakhtaouimohammed@gmail.com", "Bakhtaoui Mohammed");
        $mail->addAddress($email); 
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
}
?>
