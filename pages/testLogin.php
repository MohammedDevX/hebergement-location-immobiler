<?php
session_start();
if (isset($_POST["email"])) {
    $email = $_POST["email"];
    $pass = $_POST["password"];
    $errors = [];

    if (empty($email) || empty($pass)) {
        $errors[] = "S'il vous plait remplir touts les champs";
    }

    if (empty($errors)) {
    // Vérification de l'email
    $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$filterEmail) {
        $errors[] = "S'il vous plait saisir un email valide";
    }

    // Vérification du mot de passe
    $filterPass = preg_match("/^(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $pass);
    if (!$filterPass) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, un chiffre et un caractère spécial.";
    }

    // Si les validations sont correctes
    if (empty($errors)) {
        require "../includes/connection.php";
        $query = "SELECT * FROM locataire WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "nice";

        // Vérification du mot de passe
        if (password_verify($pass, $data["mot_passe"])) {
            // Si l'utilisateur est trouvé et que le mot de passe est correct
            // $_SESSION['user'] = $data; // Enregistrer l'utilisateur dans la session
            // header("Location: dashboard.php"); // Rediriger vers le dashboard
            // die();
            echo "nice";
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
    }

    // Si des erreurs existent, les enregistrer dans la session et rediriger vers la page de login
    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        // print_r($errors);
        header("Location:login.php");
        die();
    }

} else {
    // Si les données ne sont pas envoyées via POST, rediriger vers la page de login
    header("Location: login.php");
    die();
}
?>