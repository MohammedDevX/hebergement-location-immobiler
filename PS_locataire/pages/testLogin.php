<?php
session_start();
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $pass = $_POST["password"];
    $errors = []; // Tableaux pour stocker les erreurs

    if (empty($email) || empty($pass)) {
        $errors[] = "S'il vous plait remplir touts les champs";
    }

    if (empty($errors)) {
    $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$filterEmail) {
        $errors[] = "S'il vous plait saisir un email valide";
    }

    // Si les validations sont correctes
    if (empty($errors)) {
        require "../includes/connection.php";
        $query = "SELECT * 
        FROM locataire
        WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if (password_verify($pass, $data["mot_passe"])) { // Fonction qui comparer entre un string et un hash
            $_SESSION['user_id'] = $data; // Enregistrer l'utilisateur dans la session
            header("Location: acceuille.php"); // Rediriger vers le profile
            die;
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
    }

    // Si des erreurs existent, les enregistrer dans la session et rediriger vers la page de login
    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        header("Location:acceuille.php");
        die();
    }

} else {
    header("Location:acceuille.php");
    die();
}
?>