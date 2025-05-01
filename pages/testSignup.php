<?php
session_start();
require_once '../includes/connection.php';

$errors = []; // Tableaux pour stocker les erreurs 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom_user = $_POST["nom_user"] ?? ''; // Si le champs est vide ou null il retourne ''
    $nom = trim($_POST["nom"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $mot_passe = $_POST["mot_passe"] ?? '';
    $tel = trim($_POST["tel"] ?? '');
    $photo = $_FILES["photo_profil"] ?? null;

    if (empty($nom_user) || empty($nom) || empty($prenom) || empty($email) || empty($mot_passe) || empty($tel) || empty($photo['name'])) {
        $errors[] = "Veuillez remplir tous les champs";
    }

    if (empty($errors)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if (!preg_match("/^(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $mot_passe)) {
            $errors[] = "Mot de passe invalide (min 8 caractères, 1 chiffre, 1 caractère spécial)";
        }

        if (!preg_match('/^0[5-7][0-9]{8}$/', $tel)) {
            $errors[] = "Téléphone invalide (doit commencer par 05, 06 ou 07 et contenir 10 chiffres)";
        }
    }

    // Verifier si exists : nom_user, email, tel
    if (empty($errors)) {
        $query = "SELECT * FROM utilisateur WHERE email = :email OR tel = :tel OR nom_user = :nom_user";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":tel", $tel);
        $stmt->bindParam(":nom_user", $nom_user);
        $stmt->execute();
        $existing = $stmt->fetch();

        if ($existing) {
            if ($existing['email'] === $email) {
                $errors[] = "Cet email est déjà utilisé";
            }
            if ($existing['tel'] === $tel) {
                $errors[] = "Ce numéro de téléphone est déjà utilisé";
            }
            if ($existing['nom_user'] === $nom_user) {
                $errors[] = "Ce nom d'utilisateur est déjà utilisé";
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: signup.php");
        die;
    }

    // Gestion des photos : 
    $uploads_dir = '../assets/uploads/';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0775, true);
    }

    $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $ext;
    $destination = $uploads_dir . $uniqueName;

    if (!move_uploaded_file($photo['tmp_name'], $destination)) {
        $_SESSION['errors'] = ["Erreur lors du téléchargement de la photo."];
        header("Location: signup.php");
        die;
    }

    $photo_path = 'assets/uploads/' . $uniqueName;

    // Insertion dans utilisateur : 
    $hashed_password = password_hash($mot_passe, PASSWORD_DEFAULT);

    $query = "INSERT INTO utilisateur (nom_user, nom, prenom, email, mot_passe, tel, photo_profil, created_at, updated_at)
            VALUES (:nom_user, :nom, :prenom, :email, :mot_passe, :tel, :photo_profil, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":nom_user", $nom_user);
    $stmt->bindParam(":nom", $nom);
    $stmt->bindParam(":prenom", $prenom);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":mot_passe", $hashed_password);
    $stmt->bindParam(":tel", $tel);
    $stmt->bindParam(":photo_profil", $photo_path);
    $stmt->execute();

    // Insertion dans locataire
    $id_user = $conn->lastInsertId();
    $queryLoc = "INSERT INTO locataire (id_user, created_at, updated_at) VALUES (:id_user, NOW(), NOW())";
    $stmtLoc = $conn->prepare($queryLoc);
    $stmtLoc->bindParam(":id_user", $id_user);
    $stmtLoc->execute();

    header("Location: login.php");
    die;
} else {
    header("Location: signup.php");
    die;
}
