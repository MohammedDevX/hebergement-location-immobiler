<?php 
session_start();

require_once '../includes/connection.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: detaille.php?id=$_POST[accommodation_id]");
    die;
}

// Récupération des données du formulaire précédent
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$nights = $_POST['nights'] ?? null;
$price_per_night = $_POST['price_per_night'] ?? null;
$service_fee = $_POST['service_fee'] ?? null;
$total_price = $_POST['total_price'] ?? null;
$travelers = $_POST['travelers'] ?? null;
$accommodation_id = $_POST['accommodation_id'] ?? null;
$accommodation_title = $_POST['accommodation_title'] ?? null;
$accommodation_city = $_POST['accommodation_city'] ?? null;

// Calcul des montants pour la réservation
$montant_total_sejour = $nights * $price_per_night;
$commission = round($montant_total_sejour * 0.03, 2);
$montant_hote = $montant_total_sejour - $commission;

// Si les données ne sont pas présentes dans POST, on utilise les données de session comme fallback
if (!$start_date) {
    // Récupération des données depuis la session
    $start_date = $_SESSION['date_debut'] ?? date('Y-m-d');
    $end_date = $_SESSION['date_fin'] ?? date('Y-m-d', strtotime('+3 days'));
    $total_price = $_SESSION['montant_total'] ?? 1000;
    $accommodation_id = $_SESSION['id_annonce'] ?? 1;
    $montant_total_sejour = $total_price;
    $commission = round($montant_total_sejour * 0.10, 2);
    $montant_hote = $montant_total_sejour - $commission;
}

// Stockage en session pour le traitement du paiement
$_SESSION['date_debut'] = $start_date;
$_SESSION['date_fin'] = $end_date;
$_SESSION['montant'] = $montant_total_sejour;
$_SESSION['id_annonce'] = $accommodation_id;
$_SESSION['montant_total'] = $total_price;

$errors = []; // Tableau pour stocker les erreurs 
$success = "";

// Fonction de validation du numéro de carte
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

// Fonction pour insérer toutes les dates de la réservation dans la table disponibilite
function insertUnavailableDates($conn, $start_date, $end_date, $id_annonce) {
    // Convertir les dates en objets DateTime
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    
    // Créer un intervalle d'un jour
    $interval = new DateInterval('P1D');
    
    // Créer une période entre les deux dates
    $daterange = new DatePeriod($start, $interval, $end);
    
    // Préparer la requête d'insertion
    $query = "INSERT INTO disponibilite (date_dispo, id_annonce, created_at, updated_at) 
              VALUES (:date_dispo, :id_annonce, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    
    // Insérer chaque date dans la table disponibilite
    foreach ($daterange as $date) {
        $formatted_date = $date->format('Y-m-d');
        $stmt->bindParam(":date_dispo", $formatted_date);
        $stmt->bindParam(":id_annonce", $id_annonce);
        $stmt->execute();
    }
    
    // Ne pas oublier d'insérer également la date de fin
    $formatted_end_date = $end->format('Y-m-d');
    $stmt->bindParam(":date_dispo", $formatted_end_date);
    $stmt->bindParam(":id_annonce", $id_annonce);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["card-number"])) {
    $num_carte = preg_replace('/\s+/', '', trim($_POST["card-number"] ?? ''));
    $exp_date = trim($_POST["expiry-date"] ?? '');
    $cvv = trim($_POST["cvv"] ?? '');
    
    // Extraction du mois et de l'année d'expiration
    $exp_parts = explode('/', $exp_date);
    $exp_mois = isset($exp_parts[0]) ? trim($exp_parts[0]) : '';
    $exp_annee = isset($exp_parts[1]) ? '20'.trim($exp_parts[1]) : '';

    // Validation du numéro de carte
    if (!validateCardNumber($num_carte)) {
        $errors[] = "Numéro de carte invalide";
    }

    // Validation du mois d'expiration
    if (!preg_match('/^\d{1,2}$/', $exp_mois) || $exp_mois < 1 || $exp_mois > 12) {
        $errors[] = "Mois d'expiration invalide";
    }

    // Validation de l'année d'expiration
    if (!preg_match('/^\d{4}$/', $exp_annee) || $exp_annee < date('Y')) {
        $errors[] = "Année d'expiration invalide";
    }

    // Validation du CVV
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = "CVV invalide";
    }

    // Traitement de la réservation si tout est valide
    if (empty($errors)) {
        $montant = $_SESSION['montant'];
        $date_debut = $_SESSION['date_debut'];
        $date_fin = $_SESSION['date_fin'];
        $id_locataire = $_SESSION['user_id']['id_locataire']; // Assurez-vous que cette structure est correcte
        $id_annonce = $_SESSION['id_annonce'];

        if (!$montant || !$id_annonce || !$id_locataire || !$date_debut || !$date_fin) {
            $errors[] = "Informations de réservation manquantes";
        } else {
            try {
                // Démarrer une transaction pour s'assurer que toutes les opérations sont effectuées ou aucune
                $conn->beginTransaction();
                
                // 1. Insérer la réservation
                $query = "INSERT INTO reservation (date_debut, date_fin, montant, date_paiement, id_locataire, id_annonce, created_at, updated_at) 
                          VALUES (:date_debut, :date_fin, :montant, NOW(), :id_locataire, :id_annonce, NOW(), NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":date_debut", $date_debut);
                $stmt->bindParam(":date_fin", $date_fin);
                $stmt->bindParam(":montant", $montant);
                $stmt->bindParam(":id_locataire", $id_locataire);
                $stmt->bindParam(":id_annonce", $id_annonce);
                $stmt->execute();
                
                // 2. Insérer les dates dans la table disponibilite
                insertUnavailableDates($conn, $date_debut, $date_fin, $id_annonce);
                
                // Valider la transaction
                $conn->commit();
                
                $success = "Réservation et paiement enregistrés avec succès";
            } catch (PDOException $e) {
                // En cas d'erreur, annuler la transaction
                $conn->rollBack();
                $errors[] = "Erreur lors de l'enregistrement: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Paiement et Réservation</title>
	<link href="../assets/css/paiementCSS/output.css" rel="stylesheet">
	<style>
		@font-face {
			font-family: 'Krylon';
			src: url("../assets/fonts/Krylon-Regular.otf") format("opentype");
			font-weight: 900;
		}
		@font-face {
			font-family: 'Grotesk';
			src: url("../assets/fonts/Grotesk-Regular.ttf") format("truetype");
		}
	</style>
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-lg w-full my-5">
        <h2 class="text-4xl font-[Krylon] text-gray-800 mb-4">Payment details</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <p><?php echo $errors[0]; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="font-[Grotesk]">
            <!-- Card Details -->
            <div class="mb-4 text-md">
                <label 
					for="card-number" 
					class="block text-lg font-medium text-gray-700">Card Details</label>
                <input 
					type="text" 
					id="card-number" 
					name="card-number"
					required 
					placeholder="XXXX XXXX XXXX XXXX" 
					class="mt-1 p-1.5 block w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555] " 
					pattern="\d{4} \d{4} \d{4} \d{4}" 
					title="Card number must be in the format XXXX XXXX XXXX XXXX">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <input 
					type="text" 
					name="expiry-date"
					placeholder="MM / YY" 
					required 
					class="block p-1.5 w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555] "
					pattern="^(0[1-9]|1[0-2]) \/ \d{2}$" 
			        title="Expiration date must be in the format MM / YY (e.g., 04 / 25)">
                <input 
					type="text" 
					name="cvv"
					placeholder="CVV" 
					required 
					class="block p-1.5 w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555}] "
					pattern="^\d{3,4}$" 
			        title="CVV must be 3 or 4 digits (e.g., 123 or 1234)">
            </div>

            <!-- Booking Summary Section -->
        	<div class="bg-gray-50 p-4 rounded-lg shadow-inner">
        	    <div class="flex items-center mb-4">
    		    	<h3 class="text-3xl font-[Krylon] font-semibold w-full border-r-2 border-gray-600 text-left text-gray-800">Booking Summary</h3>
					<img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1394247922445095202/original/d32a29bf-c3e8-4ed8-84ea-9a13c548fad4.jpeg?im_w=1200" alt="Booking Summary" class="w-72 h-38 ml-2 object-cover rounded-lg">
				</div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Property:</p>
        	        <p class="text-base font-medium text-gray-800"><?php echo isset($accommodation_title) ? htmlspecialchars($accommodation_title) : 'Property Information' ?></p>
        	    </div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Check-in:</p>
        	        <p class="text-base font-medium text-gray-800"><?php echo isset($start_date) ? htmlspecialchars($start_date) : '-' ?></p>
        	    </div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Check-out:</p>
        	        <p class="text-base font-medium text-gray-800"><?php echo isset($end_date) ? htmlspecialchars($end_date) : '-' ?></p>
        	    </div>
        	    <div class="mb-4">
        	        <p class="text-sm text-gray-700">Guests:</p>
        	        <p class="text-base font-medium text-gray-800"><?php echo isset($travelers) ? htmlspecialchars($travelers) : '1' ?></p>
        	    </div>
        	    <div class="border-t border-gray-200 pt-4">
        	        <div class="flex justify-between items-center mb-2">
        	            <span class="text-sm text-gray-700">Price per night</span>
        	            <span class="text-sm font-medium text-gray-800"><?php echo isset($price_per_night) ? number_format($price_per_night, 0, ',', ',') : '-' ?> DH</span>
        	        </div>
        	        <div class="flex justify-between items-center mb-2">
        	            <span class="text-sm text-gray-700">Service Fee</span>
        	            <span class="text-sm font-medium text-gray-800"><?php echo isset($service_fee) ? number_format($service_fee, 0, ',', ',') : number_format($commission, 0, ',', ',') ?> DH</span>
        	        </div>
        	        <div class="flex justify-between items-center text-lg font-semibold text-gray-800">
        	            <span>Total</span>
        	            <span><?php echo isset($total_price) ? number_format($total_price, 0, ',', ',') : number_format($_SESSION['montant_total'], 0, ',', ',') ?> DH</span>
        	        </div>
        	        <p class="text-sm text-gray-500">+ applicable taxes</p>
        	    </div>
        	</div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="detaille.php?id=<?php echo $accommodation_id ?>"><button type="button"  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button></a>
                <button type="submit" class="px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2]">Validate</button>
            </div>
        </form>
    </div>
    
    <script>
    // Script pour formater automatiquement le numéro de carte avec des espaces
    document.getElementById('card-number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';
        
        for (let i = 0; i < value.length && i < 16; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });
    
    // Script pour formater automatiquement la date d'expiration
    document.querySelector('input[name="expiry-date"]').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';
        
        if (value.length > 0) {
            // Max month is 12
            let month = value.substring(0, 2);
            if (month.length === 1) {
                if (parseInt(month) > 1) {
                    month = '0' + month;
                }
            } else if (parseInt(month) > 12) {
                month = '12';
            }
            
            formattedValue = month;
            
            if (value.length > 2) {
                formattedValue += ' / ' + value.substring(2, 4);
            }
        }
        
        e.target.value = formattedValue;
    });
    </script>
</body>
</html>
