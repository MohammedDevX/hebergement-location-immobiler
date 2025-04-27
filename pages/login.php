<?php 
require "../includes/connection.php";
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $pass = $_POST["password"];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) { // Type email et mot de pass>=8 et aux moins 1 numero et aux moin un char specaile 
        // $passHache = password_hash($pass, PASSWORD_DEFAULT);
        $query = "SELECT * FROM utilisateur WHERE email=:email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo $data["email"];
        // echo $data["mot_pass"];
        if ($data) {
            if ($pass == $data["mot_passe"]) {
                echo $stmt->rowCount();
            } else {
                header("Location:Login.html");
                die();
            }
        } else {
            header("Location:login.html");
            die();
        }
    } else {
        header("Location:login.html");
        die();
    }
} else {
    header("Location:login.html");
    die();
}
?>