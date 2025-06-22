<?php 
session_start();

require_once '../includes/connection.php';

// Verify if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: detaille.php?id=" . (isset($_POST['accommodation_id']) ? $_POST['accommodation_id'] : ''));
    exit;
}

// Get values from POST or set them to null if not present
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

// Fetch accommodation photo
$query11 = "SELECT photos.photo, photos.titre 
            FROM photos INNER JOIN annonce ON photos.id_annonce=annonce.id_annonce
            WHERE annonce.id_annonce=:id_annonce
            ORDER BY photo ASC LIMIT 1";
$stmt = $conn->prepare($query11);
$stmt->bindParam(":id_annonce", $accommodation_id);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate amounts for the reservation
$montant_total_sejour = $nights * $price_per_night;
$commission = round($montant_total_sejour * 0.03, 2);
$montant_hote = $montant_total_sejour - $commission;

// If data isn't present in POST, use session data
if (!$start_date) {
    // Get data from session
    $start_date = $_SESSION['date_debut'] ?? date('Y-m-d');
    $end_date = $_SESSION['date_fin'] ?? date('Y-m-d', strtotime('+3 days'));
    $total_price = $_SESSION['montant_total'] ?? 1000;
    $accommodation_id = $_SESSION['id_annonce'] ?? 1;
    $montant_total_sejour = $total_price;
    $commission = round($montant_total_sejour * 0.10, 2);
    $montant_hote = $montant_total_sejour - $commission;
}

// Store in session for payment processing
$_SESSION['date_debut'] = $start_date;
$_SESSION['date_fin'] = $end_date;
$_SESSION['montant'] = $montant_total_sejour;
$_SESSION['id_annonce'] = $accommodation_id;
$_SESSION['montant_total'] = $total_price;

$errors = []; // Array to store errors
$success = "";

// Function to validate card number
function validateCardNumber($cardNumber) {
    $cardNumber = preg_replace('/\D/', '', $cardNumber); // Remove non-numeric chars

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

// Function to insert all dates of the reservation in the availability table
function insertUnavailableDates($conn, $start_date, $end_date, $id_annonce) {
    // Convert dates to DateTime objects
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    
    // Create a one-day interval
    $interval = new DateInterval('P1D');
    
    // Create a period between the two dates
    $daterange = new DatePeriod($start, $interval, $end);
    
    // Prepare the insert query
    $query = "INSERT INTO disponibilite (date_dispo, id_annonce, created_at, updated_at) 
            VALUES (:date_dispo, :id_annonce, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    
    // Insert each date into the disponibilite table
    foreach ($daterange as $date) {
        $formatted_date = $date->format('Y-m-d');
        $stmt->bindParam(":date_dispo", $formatted_date);
        $stmt->bindParam(":id_annonce", $id_annonce);
        $stmt->execute();
    }
    
    // Don't forget to insert the end date as well
    $formatted_end_date = $end->format('Y-m-d');
    $stmt->bindParam(":date_dispo", $formatted_end_date);
    $stmt->bindParam(":id_annonce", $id_annonce);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["card-number"])) {
    // Get form data
    $num_carte = preg_replace('/\s+/', '', trim($_POST["card-number"] ?? ''));
    $exp_date = trim($_POST["expiry-date"] ?? '');
    $cvv = trim($_POST["cvv"] ?? '');
    
    // Extract month and year from expiration date
    $exp_parts = explode('/', $exp_date);
    $exp_mois = isset($exp_parts[0]) ? trim($exp_parts[0]) : '';
    $exp_annee = isset($exp_parts[1]) ? '20'.trim($exp_parts[1]) : '';

    if (!validateCardNumber($num_carte)) {
        $errors[] = "Numéro de carte invalide";
    }

    // Validate expiration month
    if (!preg_match('/^\d{1,2}$/', $exp_mois) || $exp_mois < 1 || $exp_mois > 12) {
        $errors[] = "Mois d'expiration invalide";
    }

    // Validate expiration year
    if (!preg_match('/^\d{4}$/', $exp_annee) || $exp_annee < date('Y')) {
        $errors[] = "Année d'expiration invalide";
    } else if ($exp_annee == date('Y') && $exp_mois < date('m')) {
        $errors[] = "La carte est expirée";
    }

    // Validate CVV
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = "CVV invalide";
    }

    // Process reservation if everything is valid
    if (empty($errors)) {
        $montant = $_SESSION['montant'] ?? 0;
        $date_debut = $_SESSION['date_debut'] ?? '';
        $date_fin = $_SESSION['date_fin'] ?? '';
        $id_locataire = $_SESSION['user_id']['id_locataire'] ?? null; // Make sure this structure is correct
        $id_annonce = $_SESSION['id_annonce'] ?? null;

        if (!$montant || !$id_annonce || !$id_locataire || !$date_debut || !$date_fin) {
            $errors[] = "Informations de réservation manquantes";
        } else {
            try {
                // Start a transaction to ensure all operations are performed or none
                $conn->beginTransaction();
                
                $query = "INSERT INTO reservation (date_debut, date_fin, montant, date_paiement, id_locataire, id_annonce, created_at, updated_at) 
                          VALUES (:date_debut, :date_fin, :montant, NOW(), :id_locataire, :id_annonce, NOW(), NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":date_debut", $date_debut);
                $stmt->bindParam(":date_fin", $date_fin);
                $stmt->bindParam(":montant", $montant);
                $stmt->bindParam(":id_locataire", $id_locataire);
                $stmt->bindParam(":id_annonce", $id_annonce);
                $stmt->execute();
                
                insertUnavailableDates($conn, $date_debut, $date_fin, $id_annonce);
                
                $conn->commit();
                
                $success = "Réservation et paiement enregistrés avec succès!";
            } catch (PDOException $e) {
                // In case of error, rollback the transaction
                $conn->rollBack();
                $errors[] = "Erreur lors de l'enregistrement: " . $e->getMessage();
            }
        }
    }
}

// Keep the existing form fields from POST to reuse them if validation fails
$hidden_fields = '';
foreach ($_POST as $key => $value) {
    if ($key != 'card-number' && $key != 'expiry-date' && $key != 'cvv') {
        $hidden_fields .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
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
                <?php foreach ($errors as $error): ?>
                    <p class="mb-1"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="font-[Grotesk]">
            <!-- Hidden fields to preserve data -->
            <?php echo $hidden_fields; ?>
            
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
					maxlength="19">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <input 
					type="text" 
					name="expiry-date"
					placeholder="MM / YY" 
					required 
					class="block p-1.5 w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555] "
					maxlength="7">
                <input 
					type="text" 
					name="cvv"
					placeholder="CVV" 
					required 
					class="block p-1.5 w-full border-gray-300 rounded-md shadow-sm bg-[#13868623] focus:outline-none ring-0 focus:ring-2 focus:ring-[#005555] "
					maxlength="4">
            </div>

            <!-- Booking Summary Section -->
        	<div class="bg-gray-50 p-4 rounded-lg shadow-inner">
        	    <div class="flex items-center mb-4">
    		    	<h3 class="text-3xl font-[Krylon] font-semibold w-full border-r-2 border-gray-600 text-left text-gray-800">Booking Summary</h3>
					<?php if (isset($data["photo"]) && !empty($data["photo"])): ?>
					<img src="<?php echo htmlspecialchars($data["photo"]) ?>" alt="<?php echo htmlspecialchars($data["titre"] ?? 'Property') ?>" class="w-72 h-38 ml-2 object-cover rounded-lg">
					<?php endif; ?>
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
        	            <span><?php echo isset($total_price) ? number_format($total_price, 0, ',', ',') : number_format($_SESSION['montant_total'] ?? 0, 0, ',', ',') ?> DH</span>
        	        </div>
        	        <p class="text-sm text-gray-500">+ applicable taxes</p>
        	    </div>
        	</div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="detaille.php?id=<?php echo htmlspecialchars($accommodation_id ?? '') ?>"><button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</button></a>
                <button type="submit" class="px-4 py-2 bg-[#005555] text-white rounded-md hover:bg-[#0b574fe2]">Validate</button>
            </div>
        </form>
    </div>
    
    <script>
    // Format the card number with spaces
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
    
    // Format the expiration date
    document.querySelector('input[name="expiry-date"]').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9\/]/gi, '');
        
        // Remove any existing slashes
        value = value.replace(/\//g, '');
        
        let formattedValue = '';
        
        if (value.length > 0) {
            // Get the month part (max 2 digits)
            let month = value.substring(0, 2);
            
            // If first digit > 1, prefix with 0
            if (month.length === 1 && parseInt(month) > 1) {
                month = '0' + month;
            } else if (month.length === 2) {
                // Ensure month is valid (01-12)
                let monthNum = parseInt(month);
                if (monthNum > 12) {
                    month = '12';
                } else if (monthNum < 1) {
                    month = '01';
                }
            }
            
            formattedValue = month;
            
            // Add separator and year if there are more digits
            if (value.length > 2) {
                formattedValue += ' / ' + value.substring(2, 4);
            }
        }
        
        e.target.value = formattedValue;
    });
    
    // Only allow digits for CVV
    document.querySelector('input[name="cvv"]').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '').substring(0, 4);
    });
    </script>
</body>
</html>