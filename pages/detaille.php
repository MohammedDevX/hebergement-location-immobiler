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
  <body class="bg-white text-gray-900" style="width: 90%; margin: auto">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-medium ml-3">chambre luxury</h1>
    </div>

    <!-- Main Gallery -->
    <div class="container mx-auto px-4 pt-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <div class="md:col-span-2 h-96 overflow-hidden rounded-lg">
          <img
            src="https://img.freepik.com/photos-gratuite/suite-luxe-dans-hotel-grande-hauteur-table-travail_105762-1783.jpg?t=st=1744394176~exp=1744397776~hmac=7d3fceffed8c8ce359bdf3d1d235c1161006aa8abf69cbf44234e905abdbcd1c&w=1380"
            alt="Riad principal"
            class="w-full h-full object-cover"
          />
        </div>
        <div class="grid grid-cols-2 gap-2 h-96">
          <div class="overflow-hidden rounded-lg">
            <img
              src="https://img.freepik.com/photos-gratuite/rendu-3d-belle-suite-chambre-luxe-hotel-television-etagere_105762-2077.jpg?t=st=1744394207~exp=1744397807~hmac=25cbddcac8bb520e50ecfe8467d6c42e41b525a1d072044897a11e0ca039771b&w=1380"
              alt="Chambre"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="overflow-hidden rounded-lg">
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrasse"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="overflow-hidden rounded-lg">
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrasse soir"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="overflow-hidden rounded-lg">
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrasse soir"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="overflow-hidden rounded-lg">
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrasse soir"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="overflow-hidden rounded-lg relative">
            <img
              src="https://img.freepik.com/photos-premium/spa-rendu-3d-bien-etre-massage-dans-suite-hotel-baignoire_105762-2032.jpg?w=1380"
              alt="Terrasse jour"
              class="w-full h-full object-cover"
            />
            <button
              id="showGalleryBtn"
              class="absolute bottom-2 right-2 left-2 bg-white text-black py-2 px-4 rounded-lg font-medium text-sm"
            >
              Afficher toutes les photos
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Infos logement -->
    <div class="container mx-auto px-4 py-6">
      <div class="flex flex-col md:flex-row md:justify-between">
        <div class="md:w-2/3">
          <h2 class="text-2xl font-semibold mb-1 ML">Chambre - Fès, Maroc</h2>
          <p class="text-lg text-gray-700 mb-2">
            2 lits simples · Salle de bain partagée
          </p>
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

          <!-- Informations hôte -->
          <div class="border-t border-b border-gray-200 py-8 my-8">
            <div class="flex items-center mb-6">
              <img
                src="/api/placeholder/60/60"
                alt="Photo Karim"
                class="w-12 h-12 rounded-full mr-4"
              />
              <div>
                <h3 class="font-medium">Hôte : Karim</h3>
                <p class="text-gray-600">Hôte depuis 5 ans</p>
              </div>
            </div>

            <div class="space-y-6">
              <div class="flex items-start">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6 mr-4"
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
                  class="h-6 w-6 mr-4"
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
                  class="h-6 w-6 mr-4"
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
                  class="h-6 w-6 mr-4"
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
            </div>
          </div>

          <!-- Description -->
          <div class="my-8">
            <h3 class="text-xl font-semibold mb-4">À propos de ce logement</h3>
            <p class="mb-2">
              Riad chahd palace met à la disposition de ses voyageurs des
              chambres d'hôte bien équipée.
            </p>
            <p class="mb-2">
              Son architecture reflète l'histoire de la culture marocaine
              ancienne. L'emplacement est juste parfait.
            </p>
            <p class="mb-2">
              Le riad se situe dans le cœur de la medina de Fès. Le petit
              déjeuner marocain, *frais et copieux est servis avec...
            </p>
            <button class="font-medium text-black underline">
              Lire la suite
            </button>
          </div>

          <!-- Ce que propose ce logement -->
          <div class="my-8">
            <h3 class="text-xl font-semibold mb-4">
              Ce que propose ce logement
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6 mr-4"
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
                  class="h-6 w-6 mr-4"
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
          </div>

          <!-- Calendrier amélioré -->
          <div class="my-8">
            <h3 class="text-xl font-semibold mb-4">5 nuits à Fès</h3>
            <p class="text-gray-600 mb-4" id="dateRangeDisplay">11 avr. 2025 - 16 avr. 2025</p>
          
            <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-0">
              <!-- Premier mois -->
              <div class="md:w-1/2 p-6">
                <h4 class="month-title text-center mb-4" id="month1Title">Chargement...</h4>
                <div class="grid grid-cols-7 gap-2 mb-2">
                  <div class="calendar-header text-center">L</div>
                  <div class="calendar-header text-center">M</div>
                  <div class="calendar-header text-center">M</div>
                  <div class="calendar-header text-center">J</div>
                  <div class="calendar-header text-center">V</div>
                  <div class="calendar-header text-center">S</div>
                  <div class="calendar-header text-center">D</div>
          
                  <!-- Les jours seront générés dynamiquement -->
                  <div id="calendar1Grid"></div>
                </div>
              </div>
          
              <!-- Deuxième mois -->
              <div class="md:w-1/2 p-6">
                <h4 class="month-title text-center mb-4" id="month2Title">Chargement...</h4>
                <div class="grid grid-cols-7 gap-2 mb-2">
                  <div class="calendar-header text-center">L</div>
                  <div class="calendar-header text-center">M</div>
                  <div class="calendar-header text-center">M</div>
                  <div class="calendar-header text-center">J</div>
                  <div class="calendar-header text-center">V</div>
                  <div class="calendar-header text-center">S</div>
                  <div class="calendar-header text-center">D</div>
          
                  <!-- Les jours seront générés dynamiquement -->
                  <div id="calendar2Grid"></div>
                </div>
              </div>
            </div>
          
            <div class="flex items-center mt-4">
              <button id="clearDatesBtn" class="bg-white border border-gray-300 hover:bg-gray-50 text-black py-2 px-4 rounded-lg font-medium transition duration-150 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Effacer les dates</span>
              </button>
            </div>
          </div>

          <!-- Commentaires -->
          <div class="my-8">
            <h3 class="text-xl font-semibold mb-4">1 commentaire</h3>
            <p class="text-gray-600 mb-6">
              L'évaluation moyenne apparaîtra après 3 commentaires
            </p>

            <div class="mb-8">
              <div class="flex items-center mb-2">
                <img
                  src="/api/placeholder/40/40"
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

          <!-- L'hôte -->
          <div class="my-8">
            <h3 class="text-xl font-semibold mb-6">
              Faites connaissance avec votre hôte
            </h3>

            <div class="flex flex-col md:flex-row bg-gray-50 rounded-xl p-6">
              <div
                class="md:w-1/3 flex flex-col items-center text-center mb-6 md:mb-0"
              >
                <img
                  src="/api/placeholder/120/120"
                  alt="Photo Karim"
                  class="w-24 h-24 rounded-full mb-4"
                />
                <h4 class="text-2xl font-medium">Karim</h4>
                <p>Hôte</p>
              </div>

              <div class="md:w-2/3 flex flex-col md:pl-8">
                <div class="flex items-center mb-4">
                  <div class="flex items-center mr-8">
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
                      class="h-6 w-6 mr-2"
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
                      class="h-6 w-6 mr-2"
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
                    class="text-white py-3 px-6 rounded-lg font-medium"
                    style="background-color: #005555"
                  >
                    Envoyer un message à l'hôte
                  </button>
                </div>

                <div class="flex items-center mt-6 text-sm text-gray-600">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2 text-[#005555]"
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
        <div class="md:w-1/3">
          <div
            class="sticky top-24 border border-gray-300 rounded-xl p-4 shadow-lg max-w-xs mx-auto ml-14"
          >
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-lg font-bold">
                1426 € <span class="text-sm font-normal">pour 5 nuits</span>
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
            <p class="text-center text-xs text-gray-500 mb-3">
              Aucun montant ne vous sera débité
            </p>

            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <p class="underline">285 € x 5 nuits</p>
                <p>1 425 €</p>
              </div>
              <div class="flex justify-between">
                <p class="underline">Frais de service</p>
                <p>0 €</p>
              </div>
              <div
                class="flex justify-between border-t border-gray-300 pt-2 font-bold"
              >
                <p>Total</p>
                <p>1 426 €</p>
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
    <div class="container mx-auto px-4 py-6 border-t border-gray-200">
      <h3 class="text-xl font-semibold mb-6">Où vous serez</h3>
      <div class="h-96 bg-gray-200 rounded-xl mb-4 relative">
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

    <!-- Galerie modale pour afficher toutes les photos -->
    <div
      id="photoGalleryModal"
      class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-auto"
    >
      <div class="container mx-auto px-4 py-8 modal-slide-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-white text-2xl font-bold">
            Photos du Riad Chahd Palace
          </h2>
          <button id="closeGallery" class="text-white hover:text-gray-300">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-8 w-8"
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
              src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-liEdiWk5G1tH1KRRKn6aP1HPGcHYBI.png"
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
  <script src="../assets/js/detaille.js"></script>
  </body>
</html>
