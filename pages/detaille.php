<?php  
require "../includes/connection.php";

$query = "SELECT annonce.*, hote.created_at, ville.nom_ville, utilisateur.nom, utilisateur.prenom, utilisateur.photo_profil
          FROM annonce INNER JOIN ville ON annonce.id_ville=ville.id_ville 
          INNER JOIN hote ON annonce.id_hote=hote.id_hote 
          INNER JOIN utilisateur ON hote.id_user=utilisateur.id_user
          WHERE annonce.id_annonce=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($data);

$today = new DateTime();
$created = new DateTime($data[0]["created_at"]);
$intervale = date_diff($today, $created);

$query2 = "SELECT photo, titre
            FROM photos 
            WHERE id_annonce=1";
$stmt = $conn->prepare($query2);
$stmt->execute();
$data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tab = end($data2);
// print_r($data2);

$query3 = "SELECT date_indispo
            FROM disponibilite
            WHERE id_annonce=1";
$stmt=$conn->prepare($query3);
$stmt->execute();
$data3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data3 = json_encode($data3);
// print_r($data3);

?>
<script>
// Ce tableau contiendra toutes les dates indisponibles récupérées de la base de données
const unavailableDates = <?php echo $data3; ?>;

// Convertir le format de date de la base de données en objets Date JavaScript
const parsedUnavailableDates = unavailableDates.map(item => {
  const dateStr = item.date_indispo;
  const [year, month, day] = dateStr.split('-').map(Number);
  // Créer un objet Date (mois -1 car en JavaScript les mois commencent à 0)
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
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <link rel="stylesheet" href="../assets/css/detaille.css">
  </head>
  <body class="bg-white text-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="py-4">
        <h1 class="text-2xl md:text-3xl font-medium"><?php echo $data[0]["titre"] ?></h1>
      </div>

      <!-- Main Gallery -->
      <div class="pt-4">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
    <div class="md:col-span-2 h-64 sm:h-80 md:h-96 overflow-hidden rounded-lg">
      <img
        src="<?php echo $data2[0]["photo"] ?>"
        alt="<?php echo $data2[0]["titre"] ?>"
        class="w-full h-full object-cover"
      />
    </div>
    <div class="grid grid-cols-2 gap-2 h-64 sm:h-80 md:h-96">
      <?php for ($i=1; $i< $stmt->rowCount()-2; $i++) { ?>
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
          Afficher toutes les photos
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Photo Gallery Modal -->
<div id="photoGalleryModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center p-4">
  <div class="relative w-full max-w-7xl mx-auto">
    <!-- Close button -->
    <button id="closeGallery" class="absolute -top-12 right-2 text-white p-2 z-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
    
    <!-- Gallery grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto max-h-[80vh]">
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

      <!-- Infos logement -->
      <div class="py-6">
        <div class="flex flex-col lg:flex-row lg:justify-between">
          <div class="lg:w-2/3 pr-0 lg:pr-8">
            <h2 class="text-2xl font-semibold mb-1"><?php echo $data[0]["type_logement"] ?> - <?php echo $data[0]["nom_ville"] ?>, Maroc</h2>
            <p class="text-lg text-gray-700 mb-2">
              Pour <?php echo $data[0]["capacite"] ?> voyageurs
            </p>
            <label for="avis">
              <div class="flex items-center mb-6">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 text-black"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  />
                </svg>
                <span class="ml-1 underline">1 commentaire</span>
              </div>
            </label>

            <!-- Informations hôte -->
            <div class="border-t border-b border-gray-200 py-8 my-8">
              <div class="flex items-center mb-6">
                <img
                  src="<?php echo $data[0]["photo_profil"] ?>"
                  alt="<?php echo $data[0]["nom"] ?> <?php echo $data[0]["prenom"] ?>"
                  class="w-12 h-12 rounded-full mr-4"
                />
                <div>
                  <h3 class="font-medium">Hôte : <?php echo $data[0]["nom"] ?> <?php echo $data[0]["prenom"] ?></h3>
                  <p class="text-gray-600">Hôte depuis <?php echo $intervale->format('%y ans, %m mois, %d jours'); ?></p>
                </div>
              </div>

              <!-- <div class="space-y-6">
                <div class="flex items-start">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-4 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                    />
                  </svg>
                  <div>
                    <h4 class="font-medium">Chambre dans chambre d'hôtes</h4>
                    <p class="text-gray-600">
                      Votre chambre privée dans un logement, avec accès à des
                      espaces partagés.
                    </p>
                  </div>
                </div>

                <div class="flex items-start">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-4 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />
                  </svg>
                  <div>
                    <h4 class="font-medium">Espaces communs partagés</h4>
                    <p class="text-gray-600">
                      Vous partagerez certaines parties du logement.
                    </p>
                  </div>
                </div>

                <div class="flex items-start">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-4 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                    />
                  </svg>
                  <div>
                    <h4 class="font-medium">Salle de bain partagée</h4>
                    <p class="text-gray-600">
                      Vous partagerez la salle de bain avec d'autres personnes.
                    </p>
                  </div>
                </div>

                <div class="flex items-start">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-4 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                    />
                  </svg>
                  <div>
                    <h4 class="font-medium">Arrivée autonome</h4>
                    <p class="text-gray-600">
                      Vous pouvez accéder au logement en faisant appel au
                      personnel de l'immeuble.
                    </p>
                  </div>
                </div>
              </div> -->
            </div>

            <!-- Description -->
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-4">À propos de ce logement</h3>
              <p class="mb-2">
                <?php echo $data[0]["description_annonce"] ?>
              <!-- <button class="font-medium text-black underline">
                Lire la suite
              </button> -->
            </div>

            <!-- Ce que propose ce logement -->
            <!-- <div class="my-8">
              <h3 class="text-xl font-semibold mb-4">
                Ce que propose ce logement
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-4 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                    />
                  </svg>
                  <span>Serrure ou verrou sur la porte de la chambre</span>
                </div>
                <div class="flex items-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-4 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                    />
                  </svg>
                  <span>Cuisine</span>
                </div>
              </div>
            </div> -->

            <!-- Calendrier amélioré -->
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-4 nights"> </h3>
              <p class="text-gray-600 mb-4" id="dateRangeDisplay">11 avr. 2025 - 16 avr. 2025</p>

              <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-0">
                <!-- Navigation du calendrier -->
                <div class="w-full flex justify-between items-center mb-4">
                  <button id="prevMonthBtn" class="p-2 text-gray-600 hover:text-black focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                  <div class="flex justify-center space-x-4 sm:space-x-8 md:space-x-16 lg:space-x-32">
                    <h4 id="month1Title" class="text-base sm:text-lg font-medium">Octobre 2025</h4>
                    <h4 id="month2Title" class="text-base sm:text-lg font-medium">Novembre 2025</h4>
                  </div>
                  <button id="nextMonthBtn" class="p-2 text-gray-600 hover:text-black focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </div>

                <!-- Premier mois -->
                <div class="w-full md:w-1/2 p-2 md:p-4">
                  <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
                    <div class="calendar-header text-center">L</div>
                    <div class="calendar-header text-center">M</div>
                    <div class="calendar-header text-center">M</div>
                    <div class="calendar-header text-center">J</div>
                    <div class="calendar-header text-center">V</div>
                    <div class="calendar-header text-center">S</div>
                    <div class="calendar-header text-center">D</div>
                  </div>
                  <div id="calendar1" class="grid grid-cols-7 gap-1 sm:gap-2"></div>
                </div>

                <!-- Deuxième mois -->
                <div class="w-full md:w-1/2 p-2 md:p-4">
                  <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
                    <div class="calendar-header text-center">L</div>
                    <div class="calendar-header text-center">M</div>
                    <div class="calendar-header text-center">M</div>
                    <div class="calendar-header text-center">J</div>
                    <div class="calendar-header text-center">V</div>
                    <div class="calendar-header text-center">S</div>
                    <div class="calendar-header text-center">D</div>
                  </div>
                  <div id="calendar2" class="grid grid-cols-7 gap-1 sm:gap-2"></div>
                </div>
              </div>

              <div class="flex items-center mt-4">
                <button id="clearDatesBtn" class="bg-white border border-gray-300 hover:bg-gray-50 text-black py-2 px-4 rounded-lg font-medium transition duration-150 flex items-center text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  <span>Effacer les dates</span>
                </button>
              </div>
            </div>

            <!-- Commentaires -->
             <label for="avis">

            
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-4">1 commentaire</h3>
              <p class="text-gray-600 mb-6">
                L'évaluation moyenne apparaîtra après 3 commentaires
              </p>

              <div class="mb-8">
                <div class="flex items-center mb-2">
                  <img
                    src="/placeholder.svg?height=40&width=40"
                    alt="Photo Kim"
                    class="w-10 h-10 rounded-full mr-2"
                  />
                  <div>
                    <h4 class="font-medium">Kim</h4>
                    <p class="text-sm text-gray-600">10 ans sur Airbnb</p>
                  </div>
                </div>
                <div class="flex items-center mb-2">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-black"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    />
                  </svg>
                  <span class="ml-2 text-gray-600">mai 2023</span>
                </div>
                <p class="mb-2">
                  Aucune chambre disponible à l'arrivée Une journée exagérée à la
                  recherche d'un autre logement sans récupérer l'argent.
                </p>
                <p class="mb-4">
                  C'est vraiment une arnaque d'emmener les touristes dans u...
                </p>
                <button class="text-black underline">Lire la suite</button>
              </div>

              <p class="text-gray-600">Fonctionnement des commentaires</p>
            </div>
            </label>

            <!-- L'hôte -->
            <div class="my-8">
              <h3 class="text-xl font-semibold mb-6">
                Faites connaissance avec votre hôte
              </h3>

              <div class="flex flex-col md:flex-row bg-gray-50 rounded-xl p-4 md:p-6">
                <div class="md:w-1/3 flex flex-col items-center text-center mb-6 md:mb-0">
                  <img
                    src="/placeholder.svg?height=120&width=120"
                    alt="Photo Karim"
                    class="w-20 h-20 md:w-24 md:h-24 rounded-full mb-4"
                  />
                  <h4 class="text-xl md:text-2xl font-medium">Karim</h4>
                  <p>Hôte</p>
                </div>

                <div class="md:w-2/3 md:pl-8">
                  <div class="flex flex-col sm:flex-row sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <div class="flex items-center mr-0 sm:mr-8 mb-2 sm:mb-0">
                      <span class="text-2xl font-medium mr-1">7</span>
                      <span class="text-gray-600">évaluations</span>
                    </div>
                    <div class="flex items-center">
                      <div class="flex items-center mr-1">
                        <span class="text-2xl font-medium mr-1">3,57</span>
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
                      <span class="text-gray-600">en note globale</span>
                    </div>
                  </div>

                  <p class="mb-4">
                    <span class="font-medium block mb-2">5</span>
                    <span class="text-gray-600">ans en tant qu'hôte</span>
                  </p>

                  <div class="space-y-6">
                    <div class="flex items-start">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2 flex-shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                      </svg>
                      <div>
                        <h5 class="font-medium">
                          Ma profession : directeur générale du RIAD CHAHD PALACE
                        </h5>
                      </div>
                    </div>

                    <div class="flex items-start">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2 flex-shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                        />
                      </svg>
                      <div>
                        <h5 class="font-medium">
                          Langues parlées : Arabe, Anglais et Français
                        </h5>
                      </div>
                    </div>
                  </div>

                  <div class="mt-6">
                    <button
                      class="text-white py-2 px-4 sm:py-3 sm:px-6 rounded-lg font-medium w-full sm:w-auto"
                      style="background-color: #005555"
                    >
                      Envoyer un message à l'hôte
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
                      Afin de protéger votre paiement, utilisez toujours Airbnb
                      pour envoyer de l'argent et communiquer avec les hôtes.
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
                  <span class="text-xxl font-normal">Indiquez vos dates pour <br> afficher les prix</span>
                </h3>
              </div>

              <div class="border border-gray-300 rounded-lg overflow-hidden mb-3">
                <div class="grid grid-cols-2">
                  <div class="border-r border-b border-gray-300 p-2">
                    <p class="text-xs font-medium">ARRIVÉE</p>
                    <p class="font-medium text-sm" id="startDate">11/04/2025</p>
                  </div>
                  <div class="border-b border-gray-300 p-2">
                    <p class="text-xs font-medium">DÉPART</p>
                    <p class="font-medium text-sm" id="endDate">16/04/2025</p>
                  </div>
                  <div class="col-span-2 p-2">
                    <div class="relative">
                      <p class="text-xs font-medium">VOYAGEURS</p>
                      <select
                        class="w-full appearance-none bg-transparent font-medium text-sm focus:outline-none"
                      >
                        <option>1 voyageur</option>
                        <option>2 voyageurs</option>
                        <option>3 voyageurs</option>
                        <option>4 voyageurs</option>
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

              <button
                class="w-full bg-rose-600 hover:bg-rose-700 text-white py-2 rounded-lg font-bold mb-2 transition duration-150"
              >
                Réserver
              </button>

              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <!-- <p class="underline"></p> -->
                  <!-- <p>1 425 €</p> -->
                </div>
                <div class="flex justify-between">
                  <p class="underline">Frais de service</p>
                  <p>0 DH</p>
                </div>
                <div
                  class="flex justify-between border-t border-gray-300 pt-2 font-bold"
                >
                  <p>Total</p>
                  <p>0 DH</p>
                </div>
              </div>

              <button
                class="w-full text-white py-2 rounded-lg font-bold mt-3"
                style="background-color: #005555"
              >
                Réserver maintenant
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Emplacement -->
      <div class="py-6 border-t border-gray-200">
        <h3 class="text-xl font-semibold mb-6">Où vous serez</h3>
        <div class="h-64 sm:h-80 md:h-96 bg-gray-200 rounded-xl mb-4 relative">
          <!-- Placeholder pour la carte -->
          <div class="absolute inset-0 flex items-center justify-center">
            <p class="text-gray-500">Carte de Fès, Maroc</p>
          </div>
        </div>
        <p class="text-lg mb-2">Fès, Maroc</p>
        <p class="text-gray-600 mb-6">
          Le riad se situe dans le cœur de la medina de Fès, à proximité des
          principaux sites touristiques et des souks traditionnels.
        </p>
        <button class="font-medium text-black underline">
          Afficher plus d'informations sur l'emplacement
        </button>
      </div>
    </div>

    <!-- Galerie modale pour afficher toutes les photos -->
    <div
      id="photoGalleryModal"
      class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-auto"
    >
      <div class="container mx-auto px-4 py-8 modal-slide-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-white text-xl sm:text-2xl font-bold">
            Photos du Riad Chahd Palace
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
              alt="Chambre luxury"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-gratuite/rendu-3d-belle-suite-chambre-luxe-hotel-television-etagere_105762-2077.jpg?t=st=1744394207~exp=1744397807~hmac=25cbddcac8bb520e50ecfe8467d6c42e41b525a1d072044897a11e0ca039771b&w=1380"
              alt="Chambre"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Salle de bain"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Salon"
              class="w-full h-full object-cover"
            />
          </div>
          <div
            class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden gallery-item"
          >
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrasse"
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


      <script>
        document.addEventListener("DOMContentLoaded", function () {
  // Configuration initiale
  const PRICE_PER_NIGHT = <?php echo $data[0]["prix_nuit"] ?>;
  const SERVICE_FEE = 10;
  
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
  
  // Noms des mois en français
  const monthNames = [
    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
  ];
  
  // Jours de la semaine en français (version courte)
  const weekDaysShort = ["Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam.", "Dim."];
  
  // NOUVELLE PARTIE: Récupération des dates indisponibles depuis PHP
  // ---------------------------------------------------------------
  // Déclaration de la variable $data3 (simulée pour l'exemple)
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
    if (item && item.date_indispo) {
      try {
        const dateParts = item.date_indispo.split('-');
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
        console.error("Erreur lors du traitement de la date:", item.date_indispo, error);
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
  // ---------------------------------------------------------------
  // FIN DE LA NOUVELLE PARTIE
  
  // Initialiser le calendrier
  function initCalendar() {
    // Mettre à jour le HTML pour correspondre au design souhaité
    updateCalendarHTML();
    
    // Générer les mois
    updateCalendarMonths();
    
    // Initialiser les dates par défaut (11-16 avril 2025)
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
  
  // FONCTION MODIFIÉE: Générer un mois du calendrier
  // ---------------------------------------------------------------
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
      
      // MODIFICATION: Vérifier si le jour est dans le passé OU indisponible
      if (currentDate < today || isDateUnavailable(currentDate)) {
        dayElement.classList.add("text-gray-300");
        dayElement.classList.remove("cursor-pointer");
        
        // NOUVEAU: Ajouter une classe spécifique pour les dates indisponibles
        if (isDateUnavailable(currentDate)) {
          dayElement.classList.add("date-unavailable");
          
          // NOUVEAU: Ajouter une barre diagonale pour indiquer que la date est indisponible
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
  // ---------------------------------------------------------------
  // FIN DE LA FONCTION MODIFIÉE
  
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
        dayElement.classList.add("day-selected");
        endDate = clickedDate;
        endMonth = month;
        
        // Marquer les jours entre les deux dates
        markDaysInRange();
        
        // Mettre à jour l'affichage
        updateDateDisplay();
        updatePriceDisplay();
        
        console.log("Plage sélectionnée:", formatDate(startDate), "à", formatDate(endDate));
      }
    }
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
      startDateElement.textContent = formatDate(startDate, "dd/mm/yyyy");
      
      if (endDate) {
        endDateElement.textContent = formatDate(endDate, "dd/mm/yyyy");
        dateRangeDisplay.textContent = `${formatDate(startDate, "d mmm yyyy")} - ${formatDate(endDate, "d mmm yyyy")}`;
      } else {
        endDateElement.textContent = "--/--/----";
        dateRangeDisplay.textContent = `${formatDate(startDate, "d mmm yyyy")} - Sélectionnez une date de fin`;
      }
    } else {
      startDateElement.textContent = "--/--/----";
      endDateElement.textContent = "--/--/----";
      dateRangeDisplay.textContent = "";
    }
  }
  
  // Mettre à jour l'affichage du prix
  function updatePriceDisplay() {
    if (startDate && endDate) {
      // Calculer le nombre de nuits
      const nights = calculateNights(startDate, endDate);
      const totalPrice = nights * PRICE_PER_NIGHT;
      const totalPlusFrais = totalPrice + (totalPrice * (SERVICE_FEE/100));
      const fraisServ =  totalPlusFrais - totalPrice;
      
      // Mettre à jour le prix principal
      document.querySelector(".text-xl.font-semibold.mb-4.nights").innerHTML = 
      `${nights} nuits à <?php echo $data[0]["nom_ville"] ?>`;
      
      document.querySelector(".text-lg.font-bold").innerHTML =
        `${totalPrice} DH <span class="text-sm font-normal">pour ${nights} nuits</span>`;
      
      // Mettre à jour le détail des prix
      const priceDetails = document.querySelectorAll(".space-y-2.text-sm .flex");
      if (priceDetails.length >= 3) {
        priceDetails[0].innerHTML = `<p class="underline">${PRICE_PER_NIGHT} DH x ${nights} nuits</p><p>${totalPrice} DH</p>`;
        priceDetails[1].innerHTML = `<p class="underline">Frais de service</p><p >${fraisServ} DH</p>`;
        priceDetails[2].innerHTML = `<p>Total</p><p>${totalPlusFrais} €</p>`;
      }
    } else {
      document.querySelector(".text-xl.font-semibold.mb-4.nights").innerHTML = "Sélectionnez vos dates";
      // Réinitialiser à la valeur par défaut
      document.querySelector(".text-lg.font-bold").innerHTML =
        "<span class='text-xxl font-normal'>Indiquez vos dates pour <br> afficher les prix</span>";
      
      const priceDetails = document.querySelectorAll(".space-y-2.text-sm .flex");
      // if (priceDetails.length >= 3) {
        // priceDetails[0].innerHTML = '<p class="underline"></p>';
        // priceDetails[2].innerHTML = "<p>Total</p><p>1 426 €</p>";
      // }
      priceDetails[0].innerHTML = '<p class="underline"></p>';
      priceDetails[1].innerHTML = `<p class="underline">Frais de service</p><p >0 DH</p>`;
      priceDetails[2].innerHTML = '<p>Total</p><p>0 DH</p>';
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
    }
    
    return `${day}/${month}/${year}`;
  }
  
  // Définir les dates par défaut (11-16 avril 2025)
  function setDefaultDates() {
    // startDate = new Date(); // 11 avril 2025
    // startMonth = 4; // Avril
    // endDate = new Date(); // 16 avril 2025
    // endMonth = 4; // Avril
    
    // Marquer les jours sélectionnés
    markSelectedDates();
    
    // Mettre à jour l'affichage
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
    
    // Gestion des boutons de réservation
    const bookingBtns = document.querySelectorAll(
      'button.bg-rose-600, button[style*="background-color:#005555"]'
    );
    
    bookingBtns.forEach(btn => {
      btn.addEventListener("click", function () {
        if (startDate && endDate) {
          alert(
            `Réservation initiée pour la période du ${formatDate(startDate)} au ${formatDate(endDate)}`
          );
        } else {
          alert("Veuillez sélectionner vos dates de séjour avant de réserver");
        }
      });
    });
    
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
  
  // NOUVEAU: Ajouter le style CSS pour les dates indisponibles
  // ---------------------------------------------------------------
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
  `;
  document.head.appendChild(styleElement);
  // ---------------------------------------------------------------
  // FIN DU NOUVEAU STYLE CSS
  
  // Initialiser le calendrier
  initCalendar();
});
      </script>

  <!-- <script src="../assets/js/detaille.js"></script>  -->
  <!-- <script src="../assets/js/api.js"></script>   -->
  </body>
</html>
