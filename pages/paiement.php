<?php
session_start();
require_once '../includes/connection.php';

// Verifie si l'utilisateur est connecte
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    die;
}

// Test : 
if (!isset($_SESSION['montant'])) {
    $_SESSION['montant'] = 1000;
}
if (!isset($_SESSION['id_annonce'])) {
    $_SESSION['id_annonce'] = 1;
}
if (!isset($_SESSION['date_debut'])) {
    $_SESSION['date_debut'] = date('Y-m-d');
}
if (!isset($_SESSION['date_fin'])) {
    $_SESSION['date_fin'] = date('Y-m-d', strtotime('+3 days'));
}
if (!isset($_SESSION['montant_total'])) {
    $_SESSION['montant_total'] = $_SESSION['montant'];
}

$errors = []; // Tableaux pour stocker les erreurs 
$success = "";

$montant_total = $_SESSION['montant_total'];
$id_annonce = $_SESSION['id_annonce'];
$id_locataire = $_SESSION['user_id']['id_user'];
$commission = round($montant_total * 0.10, 2); // Prendre 10% de montant total pour la platforme
$montant_hote = $montant_total - $commission; // Le reste pour l'hote

// Fonction de validation Luhn
function validateCardNumber($cardNumber) {
    $cardNumber = preg_replace('/\D/', '', $cardNumber); // Enlever les non numériques

    if (strlen($cardNumber) != 16) return false;

    $sum = 0;
    $shouldDouble = false;

    for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
        $digit = (int)$cardNumber[$i];
        if ($shouldDouble) {
            $digit *= 2;
            if ($digit > 9) $digit -= 9;
        }
        $sum += $digit;
        $shouldDouble = !$shouldDouble;
    }

    return $sum % 10 === 0;
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $num_carte = trim($_POST["num_carte"] ?? '');
    $exp_mois = trim($_POST["exp_mois"] ?? '');
    $exp_annee = trim($_POST["exp_annee"] ?? '');
    $cvv = trim($_POST["cvv"] ?? '');

    if (!validateCardNumber($num_carte)) {
        $errors[] = "Numéro de carte invalide";
    }

    if (!preg_match('/^\d{2}$/', $exp_mois) || $exp_mois < 1 || $exp_mois > 12) {
        $errors[] = "Mois d'expiration invalide";
    }

    if (!preg_match('/^\d{4}$/', $exp_annee) || $exp_annee < date('Y')) {
        $errors[] = "Année d'expiration invalide";
    }

    if (!preg_match('/^\d{3}$/', $cvv)) {
        $errors[] = "CVV invalide";
    }

    // Reservation
    if (empty($errors)) {
        $montant = $_SESSION['montant'];
        $date_debut = $_SESSION['date_debut'];
        $date_fin = $_SESSION['date_fin'];

        if (!$montant || !$id_annonce || !$id_locataire || !$date_debut || !$date_fin) {
            $errors[] = "Informations de réservation manquantes";
        } else {
            $query = "INSERT INTO reservation (date_debut, date_fin, montant, montant_hote, commission_plateforme, id_locataire, id_annonce)
                    VALUES (:date_debut, :date_fin, :montant, :montant_hote, :commission_plateforme, :id_locataire, :id_annonce)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":date_debut", $date_debut);
            $stmt->bindParam(":date_fin", $date_fin);
            $stmt->bindParam(":montant", $montant);
            $stmt->bindParam(":montant_hote", $montant_hote);
            $stmt->bindParam(":commission_plateforme", $commission);
            $stmt->bindParam(":id_locataire", $id_locataire);
            $stmt->bindParam(":id_annonce", $id_annonce);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>Réservation et paiement enregistrés avec succès</p>";
            } else {
                echo "<p style='color:red;'>Erreur lors de l'enregistrement de la réservation</p>";
            }
        }
    }

    // Afficher erreurs
    if (!empty($errors)) {
        echo "<p style='color:red;'>".$errors[0]."</p>";
    }
}
?>

<form method="POST">
    <label>Numéro de carte :</label>
    <input type="text" name="num_carte" required><br>
    <label>Mois expiration :</label>
    <input type="text" name="exp_mois" required><br>
    <label>Année expiration :</label>
    <input type="text" name="exp_annee" required><br>
    <label>CVV :</label>
    <input type="text" name="cvv" required><br>
    <button type="submit">Payer</button>
</form>
