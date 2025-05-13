<?php 
session_start();

// Système de notification
$notificationHTML = "";
$hasNotification = false;
$type = "";
$message = "";

// Vérifier s'il y a une notification dans les cookies
if (isset($_COOKIE["notif"])) {
    $message = $_COOKIE["notif"];
    $hasNotification = true;
    
    // Déterminer le type de notification basé sur le contenu du message
    $type = "success"; // Par défaut
    if (strpos($message, "Erreur") !== false || strpos($message, "introuvable") !== false) {
        $type = "error";
    }
    
    // Supprimer le cookie après l'avoir utilisé
    setcookie("notif", "", 1);
}

// Vérifier s'il y a une notification dans la session (pour la réinitialisation de mot de passe)
if (isset($_SESSION['notification'])) {
    $hasNotification = true;
    $type = $_SESSION['notification']['type'];
    $message = $_SESSION['notification']['message'];
    
    // Supprimer la notification après l'avoir récupérée
    unset($_SESSION['notification']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaNook - Accueil</title>
    <script src="https://kit.fontawesome.com/5300af1428.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .background {
            height: 1000px;
            background-image: url('../assets/images/Bg.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        @keyframes moveUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .search-bar {
            animation: moveUp 1s ease-out;
        }
        
        /* Styles pour les notifications */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
            max-width: 400px;
            width: 350px;
            transform: translateY(100px);
            transition: transform 0.3s ease-in-out;
            opacity: 0; /* Ajout de l'opacité à 0 par défaut */
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1; /* Rendre visible avec l'opacité */
        }
        
        .notification-success {
            background-color: #10b981;
        }
        
        .notification-error {
            background-color: #ef4444;
        }
        
        .notification-icon {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .notification-message {
            flex-grow: 1;
            font-size: 14px;
        }
        
        .notification-close {
            margin-left: 12px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        /* Responsive pour petits écrans */
        @media (max-width: 480px) {
            .notification {
                width: calc(100% - 40px);
                right: 20px;
                left: 20px;
            }
        }
    </style>
</head>
<body class="h-[2000px]">
    <?php if ($hasNotification): ?>
    <!-- Notification -->
    <div id="notification" class="notification notification-<?php echo $type; ?>">
        <div class="notification-icon">
            <?php if ($type === 'success'): ?>
                <i class="fas fa-check-circle"></i>
            <?php else: ?>
                <i class="fas fa-exclamation-circle"></i>
            <?php endif; ?>
        </div>
        <div class="notification-message"><?php echo $message; ?></div>
        <div class="notification-close" onclick="closeNotification()">
            <i class="fas fa-times"></i>
        </div>
    </div>
    <!-- Message de débogage temporaire -->
    <script>console.log("Notification détectée: <?php echo $type; ?> - <?php echo addslashes($message); ?>");</script>
    <?php else: ?>
    <!-- Message de débogage temporaire -->
    <script>console.log("Aucune notification détectée");</script>
    <?php endif; ?>

    <div class="background">
        <!-- NavBar -->
        <div class="navbar-container">
            <?php include "../includes/navbar.php" ?>
        </div>
        <!-- NavBar -->
        
        <!-- Search Bar -->
        <div class="mx-auto font-[Krylon] text-md max-w-7xl sm:px-6 lg:px-8 search-bar">
            <div class="relative isolate overflow-hidde px-6 py-20 text-center sm:px-16">
                <form action="results.php" method="post">
                    <label
                        class="mx-auto mt-[300px] relative bg-white max-w-2xl flex flex-col md:flex-row items-center justify-center py-2 px-2 rounded-2xl gap-2 focus-within:border-gray-300"
                        for="search-bar">
                        <input id="search-bar" placeholder="your keyword here" name="results"
                            class="px-6 py-2 w-full rounded-md flex-1 outline-none bg-white" required="">
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 bg-[#005555] text-white fill-white active:scale-95 duration-100 border will-change-transform overflow-hidden relative rounded-xl transition-all">
                            <div class="flex items-center transition-all">
                                <span class="text-lg font-semibold whitespace-nowrap truncate mx-auto">
                                    Search
                                </span>
                            </div>
                        </button>
                    </label>
                </form>
            </div>
        </div>
        <!-- Search Bar -->
    </div>

    <div class="bg-white w-full font-[Grotesk]">
        <div class="mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:max-w-8xl">
            <h2 class="text-6xl pl-10 font-[Krylon] tracking-tight text-gray-900">Proprietes</h2>
            
            <div class="mt-10 grid px- grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-1 xl:grid-cols-4 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
                <!-- Start Propr... -->
                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1199429021378461460/original/558c7d3b-950e-4187-8cad-118efd655601.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    El Jadida, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Mohammed</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,000 night</p>
                        </div>
                    </div>
                </div>
                <!-- End Propr... -->
                
                <!-- Start Propr... -->
                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-937785838738660685/original/c22ecdcf-5a23-443b-98bd-f07fe4fe0dac.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    El Jadida, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Ala</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,000 night</p>
                        </div>
                    </div>
                </div>
                <!-- End Propr... -->
                
                <!-- Start Propr... -->
                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-637397063507953305/original/5af16cfd-8687-4013-8e1b-36e904291b43.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Ouassane, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Ismaïl</p>
                            <p class="text-sm font-bold text-gray-900">MAD 900 night</p>
                        </div>
                    </div>
                </div>
                <!-- End Propr... -->
                
                <!-- Start Propr... -->
                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-1074399088127650340/original/7cbe0179-fd17-45f1-8963-5072e658ff55.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Boulemane, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Houda</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,400 night</p>
                        </div>
                    </div>
                </div>
                <!-- End Propr... -->
                
                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-1074399088127650340/original/7cbe0179-fd17-45f1-8963-5072e658ff55.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Legzira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Jamal</p>
                            <p class="text-sm font-bold text-gray-900">MAD 900 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-U3RheVN1cHBseUxpc3Rpbmc6MTIyMzQzODM2MTQwODkwODU4Nw%3D%3D/original/14a3e4cc-374e-48e7-96e8-231d41ca17c3.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Sidi Kaouki, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Esmail</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,560 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1228238556563772249/original/8a2683ed-c1bd-4f10-8ec9-3bde672f855f.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    El Jadida, Maroc
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Malak</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,320 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-U3RheVN1cHBseUxpc3Rpbmc6MTE3OTY4MTE0ODQ5ODg0MjUwNQ%3D%3D/original/27958a58-ef46-4d3a-898a-60c2f6419016.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    El Jadida, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Sara</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,900 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-796814091838783799/original/f5a86066-ceec-4dad-a5f8-2cb694742518.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Essaouira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Teldj</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,210 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1288628204597007434/original/a2d479e5-ee47-4dcf-aa1f-6b03b503b7ac.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Essaouira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Jamal</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,117 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-U3RheVN1cHBseUxpc3Rpbmc6MTM1MTM0MTczMDIzNzY5ODA4Mw==/original/3f54f030-1d5a-4fba-82bc-cfda7b4c1fc4.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Essaouira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Vivastay</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,460 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1220158840872179250/original/a41e35ca-668b-4dac-a1cf-6df94f200cc8.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Essaouira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Hamza</p>
                            <p class="text-sm font-bold text-gray-900">MAD 2,000 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1151243444543656926/original/e43484a8-3f83-45d7-a689-f0fcc96835d4.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Marrakech, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Rachid</p>
                            <p class="text-sm font-bold text-gray-900">MAD 800 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-694236684008723810/original/2b5fa3af-0eb3-4f83-96dd-ae46a67aa133.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Ouled Bourahema, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Otmane</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,790 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1340785444947149967/original/9bb83e26-b453-48cb-b76f-1a5a501f6a1d.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Mnar, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Mohammed Ouriaghli</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,580 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/fd85bcf9-43d5-4529-8add-1bdd98b913d0.jpg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Ghazoua, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Zineeddine</p>
                            <p class="text-sm font-bold text-gray-900">MAD 2,120 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1133485475723901101/original/ab894a01-87bf-4808-a92f-550da1a9b8e9.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Bni Said Tetouan, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Fahd</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,060 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1211611715058344314/original/06f1d084-7f19-4db1-885d-69cfb1fb3e60.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Ounagha, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Nouhaila</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,115 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/airflow/Hosting-1328229080476276008/original/bb1cf4aa-c267-45a2-9c2d-7da79ae7654e.jpg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Temara, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Youssef</p>
                            <p class="text-sm font-bold text-gray-900">MAD 3,000 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-770282039655089476/original/0d6c9b62-fe86-49f3-9457-d352cbd45b4c.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Essaouira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Hamid</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,120 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-42033226/original/18959e51-2f6f-4aff-a79e-edda21fbbd63.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Essaouira, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Omar</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,300 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1313746085715935580/original/cfbbde32-bf90-41cb-ab6d-1f8233f9b4d3.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Bouzama, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Nouaman</p>
                            <p class="text-sm font-bold text-gray-900">MAD 2,500 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-1215260351412133051/original/6b5d4590-28cd-41ca-adcc-7adedf9b6c57.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Fez, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Wissam Eddine</p>
                            <p class="text-sm font-bold text-gray-900">MAD 2,000 night</p>
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/airflow/Hosting-52465748/original/541c28bf-bc35-49dd-904c-948f968d85c2.jpg?im_w=720" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    El Jadida, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Najat</p>
                            <p class="text-sm font-bold text-gray-900">MAD 670 night</p>
                        </div>
                    </div>
                </div>
                
                <div class="group relative">
                    <img src="https://a0.muscache.com/im/pictures/hosting/Hosting-632290752683186392/original/052f8b86-4a3a-4a54-a8fb-92a45fd724f8.jpeg?im_w=960" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 duration-300">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">
                                <a href="detaille.php">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    Tetouan, Morocco
                                </a>
                            </h3>
                            <p class="mt-1 text-md text-gray-500">Hosted By Wissam Yassin</p>
                            <p class="text-sm font-bold text-gray-900">MAD 1,430 night</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div><?php include "../includes/footer.html" ?></div>
    <!-- Footer -->

    <!-- Script pour les notifications -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    
    if (notification) {
        // Afficher la notification immédiatement
        notification.classList.add('show');
        
        // Fermer automatiquement après 5 secondes
        setTimeout(function() {
            closeNotification();
        }, 5000);
    }
});

function closeNotification() {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.classList.remove('show');
        
        // Supprimer l'élément après la fin de l'animation
        setTimeout(function() {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
}
</script>
</body>
</html>
