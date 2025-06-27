<?php
session_start();
include "../includes/connection.php";

// Vérifier que les champs sont bien remplis
if (isset($_POST['email'], $_POST['password'], $_POST['Confrm_password']) && 
    !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['Confrm_password'])) {
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['Confrm_password'];
    
    // Vérifier si les mots de passe correspondent
    if ($password != $confirmPassword) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Les mots de passe ne correspondent pas. Veuillez réessayer.'
        ];
        header("Location: updatePassword.php?email=" . urlencode($email));
        exit;
    }
    
    // Vérifier la complexité du mot de passe
    if (!preg_match("/^(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Mot de passe invalide (min 8 caractères, 1 chiffre, 1 caractère spécial).'
        ];
        header("Location: updatePassword.php?email=" . urlencode($email));
        exit;
    }
    
    // Hasher le mot de passe
    $newPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Mettre à jour le mot de passe
    $query = "UPDATE locataire SET mot_passe = :mot_passe WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":mot_passe", $newPassword);
    $stmt->execute();
    
    if ($stmt->rowCount()) {
        // Succès - rediriger vers acceuille.php avec notification de succès
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Votre mot de passe a été mis à jour avec succès.'
        ];
        header("Location: acceuille.php");
        exit;
    } else {
        // Erreur - aucune mise à jour effectuée
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Une erreur est survenue ou aucun changement n\'a été effectué.'
        ];
        header("Location: updatePassword.php?email=" . urlencode($email));
        exit;
    }
} else {
    // Erreur - champs manquants
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => 'Veuillez remplir tous les champs.'
    ];
    header("Location: updatePassword.php?email=" . urlencode($_POST['email'] ?? ''));
    exit;
}
?>
