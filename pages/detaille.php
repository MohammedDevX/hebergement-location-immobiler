<?php 
session_start();
require "../includes/connection.php";

$id = $_GET["id"];

$query = "SELECT annonce.*, hote.created_at, locataire.id_locataire, ville.nom_ville, locataire.nom, locataire.prenom, locataire.photo_profil
          FROM annonce INNER JOIN ville ON annonce.id_ville=ville.id_ville 
          INNER JOIN hote ON annonce.id_hote=hote.id_hote 
          INNER JOIN locataire ON hote.id_locataire=locataire.id_locataire
          WHERE annonce.id_annonce=:id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $id);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$today = new DateTime();
$created = new DateTime($data["created_at"]);
$intervale = date_diff($today, $created);

$query2 = "SELECT photo, titre
            FROM photos 
            WHERE id_annonce=:id";
$stmt = $conn->prepare($query2);
$stmt->bindParam(":id", $id);
$stmt->execute();
$data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tab = end($data2);

$query3 = "SELECT date_dispo
            FROM disponibilite
            WHERE id_annonce=:id";
$stmt=$conn->prepare($query3);
$stmt->bindParam(":id", $id);
$stmt->execute();
$data3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data3 = json_encode($data3);

$query4 = "SELECT avis.note, avis.commentaire, avis.created_at, locataire.id_locataire, locataire.nom, locataire.prenom, locataire.photo_profil
          FROM avis INNER JOIN annonce ON annonce.id_annonce=avis.id_annonce
          INNER JOIN locataire ON avis.id_locataire=locataire.id_locataire
          WHERE annonce.id_annonce=:id";
$stmt4 = $conn->prepare($query4);
$stmt4->bindParam(":id", $id);
$stmt4->execute();
$data4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$tabNotes = array_column($data4, "note"); // Predre une colonne dans un tableaux 


if (isset($_POST['submit_rev'])) {
  $note = $_POST["rating"];
  $comentaire = $_POST["comment"];
  $query5 = "INSERT INTO avis (note, commentaire, id_locataire, id_annonce) 
            VALUES (:note, :commentaire, :id_locataire, :id_annonce)";
  $stmt5 = $conn->prepare($query5);
  $stmt5->bindParam(":note", $note);
  $stmt5->bindParam(":commentaire", $comentaire);
  $stmt5->bindParam(":id_locataire", $_SESSION["user_id"]["id_locataire"]);
  $stmt5->bindParam(":id_annonce", $data["id_annonce"]);
  $stmt5->execute();
  header("Location:detaille.php?id=$id");
}
?>
<script>

const unavailableDates = <?php echo $data3; ?>;

const parsedUnavailableDates = unavailableDates.map(item => {
  const dateStr = item.date_dispo;
  const [year, month, day] = dateStr.split('-').map(Number);
  return new Date(year, month - 1, day);
});

console.log("Dates indisponibles:", parsedUnavailableDates);
</script>
<?php
?>



<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chambre luxury - Riad Chahd Palace - Fès, Maroc</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="../assets/css/detaille.css">
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
  </head>
<body class="bg-gray-100 font-[Grotesk] text-gray-900">
    <div class=" mb-10">
        <?php include "../includes/navbar.php" ?>
    </div>
    <div class="px-6 sm:px-6 lg:px-18">
      <!-- Header -->
      <div class="py-4">
        <h1 class="text-4xl md:text-4xl font-[Krylon] font-medium"><?php echo $data["titre"] ?></h1>
      </div>

      <!-- Main Gallery -->
      <div class="pt-4">
  <div class="font-[Grotesk] grid grid-cols-1 md:grid-cols-3 gap-2">
    <div class="md:col-span-2 h-64 sm:h-80 md:h-96 lg:h-150 overflow-hidden rounded-lg">
      <img
        src="<?php echo $data2[0]["photo"] ?>"
        alt="<?php echo $data2[0]["titre"] ?>"
        class="w-full h-full object-cover"
      />
    </div>
    <div class="grid grid-cols-2 gap-2 h-64 sm:h-80 md:h-96 lg:h-150">
      <?php for ($i=1; $i< count($data2)-1; $i++) { ?>
        <div class='overflow-hidden rounded-lg'>
          <img 
            src="<?php echo $data2[$i]["photo"] ?>" 
            alt="<?php echo $data2[$i]["titre"] ?>" 
            class="w-full h-full object-cover"
          />
        </div>
      <?php } ?>
      <div class="overflow-hidden rounded-lg relative">
        <img
          src="<?php echo $tab["photo"] ?>"
          alt="<?php echo $tab["titre"] ?>"
          class="w-full h-full object-cover"
        />
        <button
          id="showGalleryBtn"
          class="absolute bottom-2 right-2 left-2 bg-white text-black py-2 px-4 rounded-lg font-medium text-xs sm:text-sm"
        >
          Show all photos
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Photo Gallery Modal -->
<div id="photoGalleryModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-80 flex items-center justify-center p-4">
  <div class="relative w-full max-w-7xl mx-auto">
    <!-- Close button -->
    <button id="closeGallery" class="absolute -top-12 right-2 text-white p-2 z-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
    
    <!-- Gallery grid -->
    <div class="grid grid-cols-2 font-[Grotesk] md:grid-cols-3 lg:grid-cols-4 gap-4 hide-scrollbar overflow-y-auto max-h-[80vh]">
      <?php foreach ($data2 as $index => $image) { ?>
        <div class="gallery-item opacity-0 transform translate-y-4 transition-all duration-300 overflow-hidden rounded-lg">
          <img 
            src="<?php echo $image["photo"] ?>" 
            alt="<?php echo $image["titre"] ?>" 
            class="w-full h-48 md:h-64 object-cover"
          />
          <?php if (!empty($image["titre"])) { ?>
            <div class="p-2 bg-white bg-opacity-80 text-black">
              <h4 class="text-sm font-medium"><?php echo $image["titre"] ?></h4>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

      <!-- Accommodation info -->
      <div class="py-6">
        <div class="flex flex-col font-[Grotesk] lg:flex-row lg:justify-between">
          <div class="lg:w-2/3 pr-0 lg:pr-8">
            <h2 class="text-2xl font-semibold mb-1"><?php echo $data["type_logement"] ?> - <?php echo $data["nom_ville"] ?>, Morocco</h2>
            <p class="text-lg text-gray-700 mb-2">
              For <?php echo $data["capacite"] ?> travelers
            </p>
              <div class="flex items-center mb-6">
                <label for="PopupRev" class="ml-1 underline hover:cursor-pointer"><?php echo $stmt4->rowCount()<=1 ? $stmt4->rowCount() ." review" : $stmt4->rowCount() ." reviews" ?></label>
              </div>

            <!-- Host information -->
            <div class="border-t border-b border-gray-200 py-8 my-8">
              <div class="flex items-center mb-6">
              <a href="profileUser.php?id_hote=<?php echo $data["id_locataire"]?>">
                <img
                    src="../<?php echo $data["photo_profil"] ?>"
                    alt="<?php echo $data["nom"] ?> <?php echo $data["prenom"] ?>"
                    class="w-12 h-12 rounded-full mr-4"
                  />
                  <div>
                    <h3 class="font-medium">Host: <?php echo $data["nom"] ?> <?php echo $data["prenom"] ?></h3>
                    <p class="text-gray-600">Host for <?php echo $intervale->format('%y years, %m months, %d days'); ?></p>
                  </div>
                </a>  
              </div>
            </div>

            <!-- Description -->
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-4">About this accommodation</h3>
              <p class="mb-2">
                <?php echo $data["description_annonce"] ?>
            </div>

            <!-- What this accommodation offers -->

            <!-- Improved calendar -->
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-4 nights"> </h3>
              <p class="text-gray-600 mb-4" id="dateRangeDisplay">Apr 11, 2025 - Apr 16, 2025</p>

              <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-0">
                <!-- Calendar navigation -->
                <div class="w-full flex justify-between items-center mb-4">
                  <button id="prevMonthBtn" class="p-2 text-gray-600 hover:text-black focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                  <div class="flex justify-center space-x-4 sm:space-x-8 md:space-x-16 lg:space-x-32">
                    <h4 id="month1Title" class="text-base sm:text-lg font-medium">October 2025</h4>
                    <h4 id="month2Title" class="text-base sm:text-lg font-medium">November 2025</h4>
                  </div>
                  <button id="nextMonthBtn" class="p-2 text-gray-600 hover:text-black focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </div>

                <!-- First month -->
                <div class="w-full md:w-1/2 p-2 md:p-4">
                  <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
                    <div class="calendar-header text-center">M</div>
                    <div class="calendar-header text-center">T</div>
                    <div class="calendar-header text-center">W</div>
                    <div class="calendar-header text-center">T</div>
                    <div class="calendar-header text-center">F</div>
                    <div class="calendar-header text-center">S</div>
                    <div class="calendar-header text-center">S</div>
                  </div>
                  <div id="calendar1" class="grid grid-cols-7 gap-1 sm:gap-2"></div>
                </div>

                <!-- Second month -->
                <div class="w-full md:w-1/2 p-2 md:p-4">
                  <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
                    <div class="calendar-header text-center">M</div>
                    <div class="calendar-header text-center">T</div>
                    <div class="calendar-header text-center">W</div>
                    <div class="calendar-header text-center">T</div>
                    <div class="calendar-header text-center">F</div>
                    <div class="calendar-header text-center">S</div>
                    <div class="calendar-header text-center">S</div>
                  </div>
                  <div id="calendar2" class="grid grid-cols-7 gap-1 sm:gap-2"></div>
                </div>
              </div>

              <div class="flex items-center mt-4">
                <button id="clearDatesBtn" class="bg-white border border-gray-300 hover:bg-gray-50 text-black py-2 px-4 rounded-lg font-medium transition duration-150 flex items-center text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  <span>Clear dates</span>
                </button>
              </div>
            </div>

            <!-- Review Popup -->
            <input type="radio" id="PopupRev" name="Modal" class="hidden peer/editPrfl">
            <label for="hide" class="hidden peer-checked/editPrfl:block fixed inset-0 z-40 custom-overlay"></label>
            <div class="font-[Grotesk] hidden fixed inset-0 peer-checked/editPrfl:flex items-center justify-center min-h-screen z-50 overflow-y-auto pointer-events-none">
              <div class="bg-white shadow-md rounded-lg p-6 w-[90%] max-w-lg hide-scrollbar max-h-[80vh] lg:max-w-[70%] md:max-w-[80%] pointer-events-auto overflow-y-auto">
                <!-- Review 1 -->
                <?php foreach ($data4 as $dt=>$avis) { ?>
                <div class="bg-white p-6 mb-4 rounded-xl shadow-sm">
                  <div class="flex items-center mb-4">
                    <a href="profileUser.php?id_locataire=<?php echo $avis["id_locataire"] ?>">
                      <img src="../<?php echo $avis["photo_profil"] ?>" alt="<?php echo $avis["nom"] ?>" class="w-12 h-12 rounded-full mr-4">

                      <div>
                        <h3 class="font-bold text-novanook-teal"><?php echo $avis["nom"] ?> <?php echo $avis["prenom"] ?></h3>
                      </a>
                          <div class="flex items-center text-gray-500 text-sm">
                            <div class="flex text-yellow-400 mr-2">
                            <?php for ($i=1; $i<=5; $i++) { 
                              if ($i<=$avis["note"]) {?>
                              <i class="fas fa-star"></i>
                              <?php } else { ?>
                                <i class="fa-regular fa-star"></i>
                              <?php };
                            } ?>
                              </div>
                              <span>· <?php echo explode(' ', $avis["created_at"])[0] ?></span>
                          </div>
                        </div>
                      </div>
                  <p class="text-gray-700 mb-4">
                      <?php echo $avis["commentaire"] ?>.
                  </p>
              </div>
                <?php } ?>
                
              
              <!-- Review Form -->
              <?php if (!empty($_SESSION["user_id"])) {?>
                <div class="bg-white p-6 rounded-xl shadow-sm">
                  <h2 class="text-xl font-bold text-novanook-teal mb-6">Write a Review</h2>
                    <form id="review-form" method="post" action="detaille.php?id=<?php echo $_GET['id']; ?>">
                      <input type="hidden" name="rating" id="rating-value" value="0">
  
                      <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                        <div id="rating-stars" class="flex space-x-2">
                          <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400" data-value="1">★</button>
                          <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400" data-value="2">★</button>
                          <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400" data-value="3">★</button>
                          <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400" data-value="4">★</button>
                          <button type="button" class="star text-3xl text-gray-300 hover:text-yellow-400" data-value="5">★</button>
                        </div>
                      </div>
  
                      <div class="mb-6">
                        <label for="review-text" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                        <textarea id="review-text" name="comment" rows="5" class="w-full px-4 py-3 border rounded-lg" placeholder="Share your experience"></textarea>
                      </div>
  
                      <input type="submit" value="Submit review" name="submit_rev" class="w-full bg-[#005555] text-white font-bold py-3 rounded-lg">
                    </form>
                    </div>
              <?php }?>
                </div>
              </div>
                <!-- Review Popup -->
                
            <!-- Comments -->
            <div class="my-8">
              <label for="PopupRev" class="ml-1 underline hover:cursor-pointer"><?php echo $stmt4->rowCount()<=1 ? $stmt4->rowCount() ." review" : $stmt4->rowCount() ." reviews" ?></label>
              <?php if ($stmt4->rowCount()>0) {?>
    
                  <div class="mb-8">
                    <div class="flex items-center mb-2">
                      <a href="profileUser.php?id_locataire=<?php echo $data4[0]["id_locataire"] ?>">
                        <img
                          src="../<?php echo $data4[0]["photo_profil"] ?>"
                          alt="<?php echo $data4[0]["nom"] ?>"
                          class="w-10 h-10 rounded-full mr-2"
                        />

                        <div>
                          <h4 class="font-medium"><?php echo $data4[0]["nom"] ?> <?php echo $data4[0]["prenom"] ?></h4>
                        </a>
                      </div>
                    </div>
                    <div class="flex items-center mb-2">
                      <?php for ($i=1; $i<=5; $i++) { 
                                  if ($i<=$data4[0]["note"]) {?>
                                  <i class="fas fa-star"></i>
                                  <?php } else { ?>
                                    <i class="fa-regular fa-star"></i>
                                  <?php };
                                } ?>
                      <span class="ml-2 text-gray-600"><?php echo explode(' ', $avis["created_at"])[0] ?></span>
                    </div>
                    <?php
                    $commentaire = $data4[0]["commentaire"];
                    $short = mb_strimwidth($commentaire, 0, 100, "...");
                    if (mb_strlen($commentaire) >= 100) {
                        echo $short;?>
                        <br><label for="PopupRev" class=" text-black underline hover:cursor-pointer">Read more</label>
                    <?php } else {
                        echo $commentaire;
                    }
                    ?>
                    </p>
                  </div>
    
                  
                  <?php } ?>
                  </div>

            <!-- The host -->
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-6">
                Meet your host
              </h3>

              <div class="flex flex-col md:flex-row bg-gray-50 rounded-xl p-4 md:p-6">
                <div class="md:w-1/3 flex flex-col items-center text-center mb-6 md:mb-0">
                  <a href="profileUser.php?id_hote=<?php echo $data["id_locataire"] ?>">
                    <img
                      src="../<?php echo $data["photo_profil"] ?>"
                      alt="<?php echo $data["nom"] ?>"
                      class="w-20 h-20 md:w-24 md:h-24 rounded-full mb-4"
                    />
                    <h4 class="text-xl md:text-2xl font-medium"><?php echo $data["nom"] ?> <?php echo $data["prenom"] ?></h4>
                  </a>
                  <p>Host</p>
                </div>

                <div class="md:w-2/3 md:pl-8">
                  <div class="flex flex-col sm:flex-row sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <div class="flex items-center mr-0 sm:mr-8 mb-2 sm:mb-0">
                      <span class="text-2xl font-medium mr-1"><?php echo $stmt4->rowCount() ?></span>
                      <span class="text-gray-600"><?php echo $stmt4->rowCount()<=1 ? " review" : " reviews" ?></span>
                    </div>
                    <div class="flex items-center">
                      <div class="flex items-center mr-1">
                        <span class="text-2xl font-medium mr-1"><?php echo $stmt4->rowCount()>=1 ? (array_sum($tabNotes))/$stmt4->rowCount() : 0 ?></span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-5 w-5"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                          />
                        </svg>
                      </div>
                      <span class="text-gray-600">overall rating</span>
                    </div>
                  </div>

                  <p class="mb-4">
                    <span class="font-medium mb-2"><?php echo $intervale->format('%y years, %m months, %d days'); ?></span>
                    <span class="text-gray-600"> as a host</span>
                  </p>

                  <div class="mt-6">
                    <button
                      class="text-white py-2 px-4 sm:py-3 sm:px-6 rounded-lg font-medium w-full sm:w-auto"
                      style="background-color: #005555"
                    >
                      Send a message to the host
                    </button>
                  </div>

                  <div class="flex items-center mt-6 text-xs sm:text-sm text-gray-600">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 mr-2 text-[#005555] flex-shrink-0"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    <p>
                      To protect your payment, always use NovaBook to send money and communicate with hosts.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Réservation -->
          <div class="lg:w-1/3 mt-8 lg:mt-0">
            <div class="sticky top-24 border border-gray-300 rounded-xl p-4 shadow-lg mx-auto lg:ml-0 max-w-sm">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold">
                  <span class="text-xxl font-normal">Enter your dates to <br> display prices</span>
                </h3>
              </div>
              <form action="paiement.php" method="post">
                <!-- Champs cachés pour stocker les données de réservation -->
                <input type="hidden" name="start_date" id="start_date_input">
                <input type="hidden" name="end_date" id="end_date_input">
                <input type="hidden" name="nights" id="nights_input">
                <input type="hidden" name="price_per_night" id="price_per_night_input" value="<?php echo $data["prix_nuit"]; ?>">
                <input type="hidden" name="service_fee" id="service_fee_input">
                <input type="hidden" name="total_price" id="total_price_input">
                <input type="hidden" name="accommodation_id" value="<?php echo $data["id_annonce"]; ?>">
                <input type="hidden" name="accommodation_title" value="<?php echo $data["titre"]; ?>">
                <input type="hidden" name="accommodation_city" value="<?php echo $data["nom_ville"]; ?>">
                
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-3">
                  <div class="grid grid-cols-2">
                    <div class="border-r border-b border-gray-300 p-2">
                      <p class="text-xs font-medium">ARRIVAL</p>
                      <p class="font-medium text-sm" id="startDate"></p>
                    </div>
                    <div class="border-b border-gray-300 p-2">
                      <p class="text-xs font-medium">DEPARTURE</p>
                      <p class="font-medium text-sm" id="endDate"></p>
                    </div>
                    <div class="col-span-2 p-2">
                      <div class="relative">
                        <p class="text-xs font-medium">TRAVELERS</p>
                        <select name="travelers" class="w-full appearance-none bg-transparent font-medium text-sm focus:outline-none">
                          <?php for ($i = 1; $i <= $data['capacite']; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> traveler<?php echo $i > 1 ? 's' : ''; ?></option>
                          <?php } ?>
                        </select>
                        <div
                          class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                          style="top: 15px"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between" id="price-per-night-row">
                  <!-- <p class="underline"></p> -->
                  <!-- <p>1 425 €</p> -->
                </div>
                <div class="flex justify-between" id="service-fee-row">
                  <p class="underline">Service fees</p>
                  <p>0 DH</p>
                </div>
                <div
                  class="flex justify-between border-t border-gray-300 pt-2 font-bold" id="total-price-row"
                >
                  <p>Total</p>
                  <p>0 DH</p>
                </div>
              </div>

              <button type="submit" id="book-now-btn"
                class="w-full text-white py-2 rounded-lg font-bold mt-3 disabled:opacity-50 disabled:cursor-not-allowed"
                style="background-color: #005555"
                disabled
              >
                Book now
              </button>
              
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Location -->
      <div class="py-6 border-t font-[Grotesk] border-gray-200">
        <h3 class="text-xl font-semibold mb-6">Where you'll be</h3>
        <div class="h-64 sm:h-80 md:h-96 bg-gray-200 rounded-xl mb-4 relative">
          <!-- Placeholder for the map -->
          <div class="absolute inset-0 flex items-center justify-center">
            <p class="text-gray-500">Map of <?php echo $data["nom_ville"] ?>, Morocco</p>
          </div>
        </div>
        <p class="text-lg mb-2"><?php echo $data["nom_ville"] ?>, Morocco</p>
      </div>
    </div>

    <!-- Modal gallery to display all photos -->
    <div
      id="photoGalleryModal"
      class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-auto"
    >
      <div class="container mx-auto px-4 py-8 modal-slide-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-white text-xl sm:text-2xl font-bold">
            Photos of Riad Chahd Palace
          </h2>
          <button id="closeGallery" class="text-white hover:text-gray-300">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6 sm:h-8 sm:w-8"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-gratuite/suite-luxe-dans-hotel-grande-hauteur-table-travail_105762-1783.jpg?t=st=1744394176~exp=1744397776~hmac=7d3fceffed8c8ce359bdf3d1d235c1161006aa8abf69cbf44234e905abdbcd1c&w=1380"
              alt="Luxury room"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-gratuite/rendu-3d-belle-suite-chambre-luxe-hotel-television-etagere_105762-2077.jpg?t=st=1744394207~exp=1744397807~hmac=25cbddcac8bb520e50ecfe8467d6c42e41b525a1d072044897a11e0ca039771b&w=1380"
              alt="Room"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Bathroom"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Living room"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrace"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Patio"
              class="w-full h-full object-cover"
            />
          </div>
        </div>
      </div>
    </div>
    <div><?php include "../includes/footer.html" ?></div>
  </body>
<script>
  const stars = document.querySelectorAll('.star');
  const ratingInput = document.getElementById('rating-value');

  stars.forEach((star, index) => {
    star.addEventListener('click', () => {
      const rating = index + 1;
      ratingInput.value = rating;

      stars.forEach((s, i) => {
        s.classList.toggle('text-yellow-400', i < rating);
        s.classList.toggle('text-gray-300', i >= rating);
      });
    });
  });
      document.addEventListener("DOMContentLoaded", function () {
      
  // Configuration initiale
  const PRICE_PER_NIGHT = <?php echo $data["prix_nuit"] ?>;
  const SERVICE_FEE = 7; // 7% de frais de service
  
  // État du calendrier
  let currentViewDate = new Date(); 
  let startDate = null;
  let endDate = null;
  let startMonth = null;
  let endMonth = null;
  
  // Éléments DOM
  const startDateElement = document.getElementById("startDate");
  const endDateElement = document.getElementById("endDate");
  const dateRangeDisplay = document.getElementById("dateRangeDisplay");
  const clearDatesBtn = document.getElementById("clearDatesBtn");
  const bookNowBtn = document.getElementById("book-now-btn");
  
  // Éléments pour le formulaire
  const startDateInput = document.getElementById("start_date_input");
  const endDateInput = document.getElementById("end_date_input");
  const nightsInput = document.getElementById("nights_input");
  const pricePerNightInput = document.getElementById("price_per_night_input");
  const serviceFeeInput = document.getElementById("service_fee_input");
  const totalPriceInput = document.getElementById("total_price_input");
  
  // Noms des mois en français
  const monthNames = [
    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
  ];
  
  // Jours de la semaine en français (version courte)
  const weekDaysShort = ["Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam.", "Dim."];
  
  // Récupération des dates indisponibles depuis PHP
  const unavailableDatesData = <?php echo $data3; ?>;
  console.log("Dates indisponibles récupérées:", unavailableDatesData);
  const unavailableDates = parseUnavailableDates(unavailableDatesData);

  // Fonction pour convertir les dates indisponibles du format JSON en objets Date
  function parseUnavailableDates(dateArray) {
    const unavailableDates = [];
    
    // Vérifier si dateArray est un tableau valide
    if (!Array.isArray(dateArray)) {
      console.error("Les données de dates indisponibles ne sont pas un tableau valide:", dateArray);
      return unavailableDates;
    }
    
    dateArray.forEach(item => {
      if (item && item.date_dispo) { // date_dispo est le nom du champ dans la base de données
        try {
          const dateParts = item.date_dispo.split('-');
          if (dateParts.length === 3) {
            const year = parseInt(dateParts[0]);
            const month = parseInt(dateParts[1]) - 1; // Les mois sont indexés à partir de 0
            const day = parseInt(dateParts[2]);
            
            // Vérifier que les valeurs sont valides
            if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
              unavailableDates.push(new Date(year, month, day));
            }
          }
        } catch (error) {
          console.error("Erreur lors du traitement de la date:", item.date_dispo, error);
        }
      }
    });
    
    console.log("Dates indisponibles traitées:", unavailableDates);
    return unavailableDates;
  }

  // Fonction pour vérifier si une date est indisponible
  function isDateUnavailable(date) {
    return unavailableDates.some(unavailableDate => 
      date.getDate() === unavailableDate.getDate() &&
      date.getMonth() === unavailableDate.getMonth() &&
      date.getFullYear() === unavailableDate.getFullYear()
    );
  }
  
  // Initialiser le calendrier
  function initCalendar() {
    // Mettre à jour le HTML pour correspondre au design souhaité
    updateCalendarHTML();
    
    // Générer les mois
    updateCalendarMonths();
    
    // Initialiser les dates par défaut
    setDefaultDates();
    
    // Ajouter les écouteurs d'événements
    setupEventListeners();
  }
  
  // Mettre à jour les mois affichés dans le calendrier
  function updateCalendarMonths() {
    // Générer le premier mois
    generateMonth(currentViewDate, "calendar1");
    
    // Générer le deuxième mois (mois suivant)
    const nextMonth = new Date(currentViewDate);
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    generateMonth(nextMonth, "calendar2");
    
    // Mettre à jour les titres des mois
    const month1 = document.querySelector("#month1Title");
    const month2 = document.querySelector("#month2Title");
    
    if (month1) {
      month1.textContent = `${monthNames[currentViewDate.getMonth()]} ${currentViewDate.getFullYear()}`;
    }
    
    if (month2) {
      month2.textContent = `${monthNames[nextMonth.getMonth()]} ${nextMonth.getFullYear()}`;
    }
    
    // Si des dates sont déjà sélectionnées, les marquer à nouveau
    if (startDate) {
      markSelectedDates();
    }
  }
  
  // Naviguer vers les mois précédents
  function navigatePreviousMonth() {
    currentViewDate.setMonth(currentViewDate.getMonth() - 1);
    updateCalendarMonths();
  }
  
  // Naviguer vers les mois suivants
  function navigateNextMonth() {
    currentViewDate.setMonth(currentViewDate.getMonth() + 1);
    updateCalendarMonths();
  }
  
  // Marquer les dates sélectionnées
  function markSelectedDates() {
    if (!startDate) return;
    
    const allDays = document.querySelectorAll(".calendar-day[data-day]");
    
    allDays.forEach(day => {
      if (!day.dataset.day) return;
      
      const dayNum = parseInt(day.dataset.day);
      const monthNum = parseInt(day.dataset.month);
      const yearNum = parseInt(day.dataset.year);
      const currentDate = new Date(yearNum, monthNum - 1, dayNum);
      
      // Marquer la date de début
      if (currentDate.getDate() === startDate.getDate() && 
          currentDate.getMonth() === startDate.getMonth() && 
          currentDate.getFullYear() === startDate.getFullYear()) {
        day.classList.add("day-selected");
      }
      
      // Marquer la date de fin
      if (endDate && 
          currentDate.getDate() === endDate.getDate() && 
          currentDate.getMonth() === endDate.getMonth() && 
          currentDate.getFullYear() === endDate.getFullYear()) {
        day.classList.add("day-selected");
      }
      
      // Marquer les jours entre les deux dates
      if (endDate && currentDate > startDate && currentDate < endDate) {
        day.classList.add("day-in-range");
      }
    });
  }
  
  // Mettre à jour le HTML du calendrier pour correspondre au design souhaité
  function updateCalendarHTML() {
    // Conteneur principal du calendrier
    const calendarContainer = document.querySelector(".flex.flex-col.md\\:flex-row.space-y-6.md\\:space-y-0.md\\:space-x-0");
    if (!calendarContainer) return;
    
    // Vider le conteneur
    calendarContainer.innerHTML = "";
    
    // Créer le conteneur principal avec les flèches de navigation
    const calendarWrapper = document.createElement("div");
    calendarWrapper.className = "w-full";
    
    // Ajouter les flèches de navigation et les titres des mois
    const navigationRow = document.createElement("div");
    navigationRow.className = "flex justify-between items-center mb-6";
    navigationRow.innerHTML = `
      <button id="prevMonthBtn" class="p-2 text-gray-600 hover:text-black focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <div class="flex justify-center space-x-32">
        <h4 id="month1Title" class="text-lg font-medium">Octobre 2025</h4>
        <h4 id="month2Title" class="text-lg font-medium">Novembre 2025</h4>
      </div>
      <button id="nextMonthBtn" class="p-2 text-gray-600 hover:text-black focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    `;
    
    calendarWrapper.appendChild(navigationRow);
    
    // Conteneur pour les deux mois
    const monthsContainer = document.createElement("div");
    monthsContainer.className = "flex flex-col md:flex-row";
    
    // Créer le premier mois
    const month1Container = document.createElement("div");
    month1Container.className = "md:w-1/2 pr-4";
    
    // Créer l'en-tête des jours de la semaine pour le premier mois
    const weekdays1 = document.createElement("div");
    weekdays1.className = "grid grid-cols-7 gap-4 mb-2";
    
    weekDaysShort.forEach(day => {
      const dayElement = document.createElement("div");
      dayElement.className = "text-center text-gray-500 text-sm";
      dayElement.textContent = day;
      weekdays1.appendChild(dayElement);
    });
    
    month1Container.appendChild(weekdays1);
    
    // Ajouter la grille des jours pour le premier mois
    const calendar1 = document.createElement("div");
    calendar1.id = "calendar1";
    calendar1.className = "grid grid-cols-7 gap-4";
    month1Container.appendChild(calendar1);
    
    // Créer le deuxième mois
    const month2Container = document.createElement("div");
    month2Container.className = "md:w-1/2 pl-4";
    
    // Créer l'en-tête des jours de la semaine pour le deuxième mois
    const weekdays2 = document.createElement("div");
    weekdays2.className = "grid grid-cols-7 gap-4 mb-2";
    
    weekDaysShort.forEach(day => {
      const dayElement = document.createElement("div");
      dayElement.className = "text-center text-gray-500 text-sm";
      dayElement.textContent = day;
      weekdays2.appendChild(dayElement);
    });
    
    month2Container.appendChild(weekdays2);
    
    // Ajouter la grille des jours pour le deuxième mois
    const calendar2 = document.createElement("div");
    calendar2.id = "calendar2";
    calendar2.className = "grid grid-cols-7 gap-4";
    month2Container.appendChild(calendar2);
    
    // Ajouter les mois au conteneur
    monthsContainer.appendChild(month1Container);
    monthsContainer.appendChild(month2Container);
    calendarWrapper.appendChild(monthsContainer);
    
    // Ajouter le wrapper au conteneur principal
    calendarContainer.appendChild(calendarWrapper);
    
    // Mettre à jour le bouton "Effacer les dates"
    const clearDatesBtnContainer = document.querySelector(".flex.items-center.mt-4");
    if (clearDatesBtnContainer) {
      clearDatesBtnContainer.innerHTML = `
        <button id="clearDatesBtn" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-black">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Effacer les dates
        </button>
      `;
    }
  }
  
  // Générer un mois du calendrier
  function generateMonth(date, containerId) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const container = document.getElementById(containerId);
    
    if (!container) return;
    
    // Vider le conteneur
    container.innerHTML = "";
    
    // Créer la grille du calendrier
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    
    // Déterminer le premier jour de la semaine (0 = dimanche, 1 = lundi, etc.)
    let firstDayOfWeek = firstDay.getDay() - 1; // Ajuster pour commencer par lundi
    if (firstDayOfWeek < 0) firstDayOfWeek = 6; // Si c'est dimanche (0), le mettre à la fin (6)
    
    // Ajouter les jours vides avant le premier jour du mois
    for (let i = 0; i < firstDayOfWeek; i++) {
      const emptyDay = document.createElement("div");
      emptyDay.className = "calendar-day text-center py-2 text-gray-300";
      container.appendChild(emptyDay);
    }
    
    // Ajouter les jours du mois
    const today = new Date();
    for (let day = 1; day <= daysInMonth; day++) {
      const dayElement = document.createElement("div");
      dayElement.className = "calendar-day text-center py-2 cursor-pointer";
      dayElement.textContent = day;
      
      // Créer un objet Date pour le jour actuel
      const currentDate = new Date(year, month, day);
      
      // Vérifier si le jour est dans le passé OU indisponible
      if (currentDate < today || isDateUnavailable(currentDate)) {
        dayElement.classList.add("text-gray-300");
        dayElement.classList.remove("cursor-pointer");
        
        // Ajouter une classe spécifique pour les dates indisponibles
        if (isDateUnavailable(currentDate)) {
          dayElement.classList.add("date-unavailable");
          
          // Ajouter une barre diagonale pour indiquer que la date est indisponible
          dayElement.innerHTML = `
            <div class="relative">
              ${day}
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-full h-0.5 bg-gray-300 transform rotate-45"></div>
              </div>
            </div>
          `;
        }
      } else {
        // Ajouter les attributs de données pour faciliter la sélection
        dayElement.dataset.day = day;
        dayElement.dataset.month = month + 1; // Mois 1-12 pour l'affichage
        dayElement.dataset.year = year;
        
        // Ajouter l'écouteur d'événements pour la sélection
        dayElement.addEventListener("click", handleDayClick);
      }
      
      container.appendChild(dayElement);
    }
    
    // Ajouter les jours vides après le dernier jour du mois pour compléter la grille
    const totalCells = firstDayOfWeek + daysInMonth;
    const remainingCells = 7 - (totalCells % 7);
    if (remainingCells < 7) {
      for (let i = 0; i < remainingCells; i++) {
        const emptyDay = document.createElement("div");
        emptyDay.className = "calendar-day text-center py-2 text-gray-300";
        container.appendChild(emptyDay);
      }
    }
  }
  
  // Gérer le clic sur un jour
  function handleDayClick(event) {
    const dayElement = event.currentTarget;
    if (dayElement.classList.contains("text-gray-300")) return;
    
    const day = parseInt(dayElement.dataset.day);
    const month = parseInt(dayElement.dataset.month);
    const year = parseInt(dayElement.dataset.year);
    
    // Premier clic - sélectionner la date de début
    if (startDate === null || (startDate !== null && endDate !== null)) {
      // Réinitialiser toutes les sélections
      clearSelection();
      
      dayElement.classList.add("day-selected");
      startDate = new Date(year, month - 1, day);
      startMonth = month;
      endDate = null;
      endMonth = null;
      
      // Mettre à jour l'affichage
      updateDateDisplay();
      
      console.log("Date de début sélectionnée:", formatDate(startDate));
    }
    // Deuxième clic - sélectionner la date de fin
    else if (endDate === null) {
      const clickedDate = new Date(year, month - 1, day);
      
      // Vérifier que la date de fin est chronologiquement après la date de début
      if (clickedDate > startDate) {
        // Vérifier qu'aucune date entre startDate et clickedDate n'est indisponible
        if (!areDatesInRangeUnavailable(startDate, clickedDate)) {
          dayElement.classList.add("day-selected");
          endDate = clickedDate;
          endMonth = month;
          
          // Marquer les jours entre les deux dates
          markDaysInRange();
          
          // Mettre à jour l'affichage
          updateDateDisplay();
          updatePriceDisplay();
          
          console.log("Plage sélectionnée:", formatDate(startDate), "à", formatDate(endDate));
        } else {
          alert("Il y a des dates indisponibles dans la plage sélectionnée. Veuillez choisir une autre plage.");
        }
      }
    }
  }
  
  // Vérifier si des dates dans la plage sont indisponibles
  function areDatesInRangeUnavailable(start, end) {
    const currentDate = new Date(start);
    currentDate.setDate(currentDate.getDate() + 1); // Commencer au jour après la date de début
    
    while (currentDate < end) {
      if (isDateUnavailable(currentDate)) {
        return true;
      }
      currentDate.setDate(currentDate.getDate() + 1);
    }
    
    return false;
  }
  
  // Marquer les jours dans la plage sélectionnée
  function markDaysInRange() {
    if (!startDate || !endDate) return;
    
    const allDays = document.querySelectorAll(".calendar-day[data-day]");
    
    allDays.forEach(day => {
      if (!day.dataset.day) return;
      
      const dayNum = parseInt(day.dataset.day);
      const monthNum = parseInt(day.dataset.month);
      const yearNum = parseInt(day.dataset.year);
      const currentDate = new Date(yearNum, monthNum - 1, dayNum);
      
      // Vérifier si ce jour est entre la date de début et la date de fin
      if (currentDate > startDate && currentDate < endDate) {
        day.classList.add("day-in-range");
      }
    });
  }
  
  // Effacer toutes les sélections
  function clearSelection() {
    const allDays = document.querySelectorAll(".calendar-day");
    allDays.forEach(day => {
      day.classList.remove("day-selected");
      day.classList.remove("day-in-range");
    });
  }
  
  // Mettre à jour l'affichage des dates
  function updateDateDisplay() {
    if (startDate) {
      if (startDateElement) startDateElement.textContent = formatDate(startDate, "dd/mm/yyyy");
      
      // Mettre à jour le champ caché pour le formulaire
      if (startDateInput) startDateInput.value = formatDate(startDate, "yyyy-mm-dd");
      
      if (endDate) {
        if (endDateElement) endDateElement.textContent = formatDate(endDate, "dd/mm/yyyy");
        if (dateRangeDisplay) dateRangeDisplay.textContent = `${formatDate(startDate, "d mmm yyyy")} - ${formatDate(endDate, "d mmm yyyy")}`;
        
        // Mettre à jour le champ caché pour le formulaire
        if (endDateInput) endDateInput.value = formatDate(endDate, "yyyy-mm-dd");
        
        // Activer le bouton de réservation
        if (bookNowBtn) bookNowBtn.disabled = false;
      } else {
        if (endDateElement) endDateElement.textContent = "--/--/----";
        if (dateRangeDisplay) dateRangeDisplay.textContent = `${formatDate(startDate, "d mmm yyyy")} - Sélectionnez une date de fin`;
        
        // Désactiver le bouton de réservation
        if (bookNowBtn) bookNowBtn.disabled = true;
      }
    } else {
      if (startDateElement) startDateElement.textContent = "--/--/----";
      if (endDateElement) endDateElement.textContent = "--/--/----";
      if (dateRangeDisplay) dateRangeDisplay.textContent = "";
      
      // Réinitialiser les champs cachés
      if (startDateInput) startDateInput.value = "";
      if (endDateInput) endDateInput.value = "";
      
      // Désactiver le bouton de réservation
      if (bookNowBtn) bookNowBtn.disabled = true;
    }
  }
  
  // Mettre à jour l'affichage du prix
  function updatePriceDisplay() {
    if (startDate && endDate) {
      // Calculer le nombre de nuits
      const nights = calculateNights(startDate, endDate);
      const totalPrice = nights * PRICE_PER_NIGHT;
      const serviceFee = totalPrice * (SERVICE_FEE/100);
      const totalPlusFees = totalPrice + serviceFee;
      
      // Mettre à jour les champs cachés pour le formulaire
      if (nightsInput) nightsInput.value = nights;
      if (pricePerNightInput) pricePerNightInput.value = PRICE_PER_NIGHT;
      if (serviceFeeInput) serviceFeeInput.value = serviceFee;
      if (totalPriceInput) totalPriceInput.value = totalPlusFees;
      
      // Mettre à jour le prix principal
      const nightsElement = document.querySelector(".text-xl.font-semibold.mb-4.nights");
      if (nightsElement) {
        nightsElement.innerHTML = `${nights} nuits à <?php echo $data["nom_ville"] ?>`;
      }
      
      const prixElement = document.querySelector(".text-lg.font-bold");
      if (prixElement) {
        prixElement.innerHTML = `${totalPrice} DH <span class="text-sm font-normal">pour ${nights} nuits</span>`;
      }
      
      // Mettre à jour le détail des prix
      const pricePerNightRow = document.getElementById("price-per-night-row");
      const serviceFeeRow = document.getElementById("service-fee-row");
      const totalPriceRow = document.getElementById("total-price-row");
      
      if (pricePerNightRow) {
        pricePerNightRow.innerHTML = `<p class="underline">${PRICE_PER_NIGHT} DH x ${nights} nuits</p><p>${totalPrice} DH</p>`;
      }
      
      if (serviceFeeRow) {
        serviceFeeRow.innerHTML = `<p class="underline">Frais de service</p><p>${serviceFee.toFixed(2)} DH</p>`;
      }
      
      if (totalPriceRow) {
        totalPriceRow.innerHTML = `<p>Total</p><p>${totalPlusFees.toFixed(2)} DH</p>`;
      }
    } else {
      const nightsElement = document.querySelector(".text-xl.font-semibold.mb-4.nights");
      if (nightsElement) {
        nightsElement.innerHTML = "Sélectionnez vos dates";
      }
      
      // Réinitialiser à la valeur par défaut
      const prixElement = document.querySelector(".text-lg.font-bold");
      if (prixElement) {
        prixElement.innerHTML = "<span class='text-xxl font-normal'>Indiquez vos dates pour <br> afficher les prix</span>";
      }
      
      // Réinitialiser les champs cachés
      if (nightsInput) nightsInput.value = "";
      if (serviceFeeInput) serviceFeeInput.value = "";
      if (totalPriceInput) totalPriceInput.value = "";
      
      // Réinitialiser les lignes de prix
      const pricePerNightRow = document.getElementById("price-per-night-row");
      const serviceFeeRow = document.getElementById("service-fee-row");
      const totalPriceRow = document.getElementById("total-price-row");
      
      if (pricePerNightRow) {
        pricePerNightRow.innerHTML = '<p class="underline"></p>';
      }
      
      if (serviceFeeRow) {
        serviceFeeRow.innerHTML = `<p class="underline">Frais de service</p><p>0 DH</p>`;
      }
      
      if (totalPriceRow) {
        totalPriceRow.innerHTML = '<p>Total</p><p>0 DH</p>';
      }
    }
  }
  
  // Calculer le nombre de nuits entre deux dates
  function calculateNights(start, end) {
    const oneDay = 24 * 60 * 60 * 1000; // heures*minutes*secondes*millisecondes
    const diffDays = Math.round(Math.abs((end - start) / oneDay));
    return diffDays;
  }
  
  // Formater une date
  function formatDate(date, format = "dd/mm/yyyy") {
    if (!date) return "--/--/----";
    
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    
    if (format === "dd/mm/yyyy") {
      return `${day < 10 ? '0' + day : day}/${month < 10 ? '0' + month : month}/${year}`;
    } else if (format === "d mmm yyyy") {
      return `${day} ${monthNames[month - 1].toLowerCase().substring(0, 3)}. ${year}`;
    } else if (format === "yyyy-mm-dd") {
      return `${year}-${month < 10 ? '0' + month : month}-${day < 10 ? '0' + day : day}`;
    }
    
    return `${day}/${month}/${year}`;
  }
  
  // Définir les dates par défaut
  function setDefaultDates() {
    // Pas de dates par défaut, l'utilisateur doit les sélectionner
    updateDateDisplay();
  }
  
  // Configurer les écouteurs d'événements
  function setupEventListeners() {
    // Gestion des boutons de navigation du calendrier
    const prevMonthBtn = document.getElementById("prevMonthBtn");
    const nextMonthBtn = document.getElementById("nextMonthBtn");
    
    if (prevMonthBtn) {
      prevMonthBtn.addEventListener("click", function() {
        navigatePreviousMonth();
      });
    }
    
    if (nextMonthBtn) {
      nextMonthBtn.addEventListener("click", function() {
        navigateNextMonth();
      });
    }
    
    // Gestion du bouton "Effacer les dates"
    const clearDatesBtn = document.getElementById("clearDatesBtn");
    if (clearDatesBtn) {
      clearDatesBtn.addEventListener("click", function () {
        clearSelection();
        startDate = null;
        endDate = null;
        startMonth = null;
        endMonth = null;
        
        // Mettre à jour l'affichage
        updateDateDisplay();
        updatePriceDisplay();
        
        console.log("Dates effacées");
      });
    }
    
    // Gestion de la galerie photos
    const showGalleryBtn = document.getElementById("showGalleryBtn");
    const galleryModal = document.getElementById("photoGalleryModal");
    const closeGalleryBtn = document.getElementById("closeGallery");
    const galleryItems = document.querySelectorAll(".gallery-item");
    
    if (showGalleryBtn) {
      showGalleryBtn.addEventListener("click", function () {
        galleryModal.classList.remove("hidden");
        galleryModal.classList.add("modal-fade-in");
        document.body.style.overflow = "hidden"; // Empêcher le défilement de la page
        
        // Animation séquentielle des images
        galleryItems.forEach((item, index) => {
          setTimeout(() => {
            item.classList.add("show");
          }, 100 + index * 100); // Délai progressif pour chaque image
        });
      });
    }
    
    if (closeGalleryBtn) {
      closeGalleryBtn.addEventListener("click", function () {
        // Réinitialiser les animations
        galleryItems.forEach(item => {
          item.classList.remove("show");
        });
        
        // Fermer la modale avec un délai pour voir l'animation de fermeture
        setTimeout(() => {
          galleryModal.classList.add("hidden");
          galleryModal.classList.remove("modal-fade-in");
          document.body.style.overflow = ""; // Réactiver le défilement
        }, 200);
      });
    }
    
    // Fermer la galerie en cliquant en dehors des images
    if (galleryModal) {
      galleryModal.addEventListener("click", function (e) {
        if (e.target === galleryModal) {
          // Réinitialiser les animations
          galleryItems.forEach(item => {
            item.classList.remove("show");
          });
          
          // Fermer la modale avec un délai
          setTimeout(() => {
            galleryModal.classList.add("hidden");
            galleryModal.classList.remove("modal-fade-in");
            document.body.style.overflow = "";
          }, 200);
        }
      });
    }
    
    // Fermer la galerie avec la touche Echap
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && galleryModal && !galleryModal.classList.contains("hidden")) {
        // Réinitialiser les animations
        galleryItems.forEach(item => {
          item.classList.remove("show");
        });
        
        // Fermer la modale avec un délai
        setTimeout(() => {
          galleryModal.classList.add("hidden");
          galleryModal.classList.remove("modal-fade-in");
          document.body.style.overflow = "";
        }, 200);
      }
    });
  }
  
  // Ajouter le style CSS pour les dates indisponibles
  const styleElement = document.createElement('style');
  styleElement.textContent = `
    .date-unavailable {
      position: relative;
      color: #ccc;
      cursor: not-allowed !important;
    }
    
    .date-unavailable::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      width: 100%;
      height: 1px;
      background-color: #ccc;
      transform: rotate(45deg);
    }
    
    .day-selected {
      background-color: #000;
      color: white;
      border-radius: 50%;
    }
    
    .day-in-range {
      background-color: #f3f4f6;
    }
  `;
  document.head.appendChild(styleElement);
  
  // Initialiser le calendrier
  initCalendar();
});
</script>

  <!-- <script src="../assets/js/detaille.js"></script>  -->
  <!-- <script src="../assets/js/api.js"></script>   -->
  </body>
</html>
