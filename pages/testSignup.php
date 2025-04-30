<?php
session_start();
require_once '../includes/connection.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier si tous les champs sont présents
    $nom = trim($_POST["nom"] ?? ''); // Si le champs est vide ou null il retourne ''
    $prenom = trim($_POST["prenom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $mot_passe = $_POST["mot_passe"] ?? '';
    $tel = trim($_POST["tel"] ?? '');
    $date_naissance = $_POST["date_naissance"] ?? '';
    $photo = $_FILES["photo_profil"] ?? null;

    // Vérification des champs vides
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_passe) || empty($tel) || empty($date_naissance) || empty($photo['name'])) {
        $errors[] = "Veuillez remplir tous les champs";
    }

    // Deuxième vérification si pas d'erreurs
    if (empty($errors)) {
        // Email valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        // Mot de passe : minimum 8 caractères, 1 chiffre, 1 caractère spécial
        if (!preg_match("/^(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $mot_passe)) {
            $errors[] = "Mot de passe invalide. (min 8 caractères, 1 chiffre, 1 caractère spécial)";
        }

        // Téléphone : 10 chiffres
        if (!preg_match("/^\d{10}$/", $tel)) {
            $errors[] = "Téléphone invalide (10 chiffres).";
        }
    }

    // Vérifier si email ou téléphone existe déjà
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM locataire WHERE email = :email OR tel = :tel");
        $stmt->execute(['email' => $email, 'tel' => $tel]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email ou téléphone déjà utilisé.";
        }
    }

    // Si des erreurs sont présentes, rediriger vers la page d'inscription
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['tel'] = $tel;
        $_SESSION['date_naissance'] = $date_naissance;
        header("Location: signup.php");
        exit;
    }

    // Si tout est OK, enregistrer
    if (empty($errors)) {
        // Gérer la photo
        $uploads_dir = '../assets/uploads/';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0775, true);
        }

        $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '.' . $ext;
        $destination = $uploads_dir . $uniqueName;

        if (move_uploaded_file($photo['tmp_name'], $destination)) {
            $photo_path = 'assets/uploads/' . $uniqueName;
        } else {
            $errors[] = "Erreur lors du téléchargement de l'image.";
        }

        // Hacher le mot de passe
        $hashed_password = password_hash($mot_passe, PASSWORD_DEFAULT);

        // Insertion dans la base
        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO locataire (nom, prenom, email, mot_passe, tel, photo_profil, date_naissance, created_at, updated_at)
                                    VALUES (:nom, :prenom, :email, :mot_passe, :tel, :photo_profil, :date_naissance, NOW(), NOW())");

            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_passe' => $hashed_password, // Utilisation du mot de passe haché
                'tel' => $tel,
                'photo_profil' => $photo_path,
                'date_naissance' => $date_naissance
            ]);

            echo "Inscription réussie !";
        }
    }
} else {
    header("Location: signup.php");
    exit;
}
