<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Messagerie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/94d90b0a3f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/messagerie.css">
</head>
<body>
<div class="navbar-container">
        <?php include "../includes/navbar.html" ?>
    </div>
    <div class="flex h-full" id="main-container">
        <!-- Sidebar de messages -->
        <div class="w-1/4 bg-white border-r border-gray-300">
            <div class="p-4 border-b border-gray-200" id="header-container">
                <!-- En-tête avec titre et boutons -->
                <!-- En-tête avec titre et boutons -->
                <div class="flex items-center justify-between mb-4" id="header-content">
                    <h1 class="text-2xl font-semibold">Messages</h1>
                    <div class="flex">
                        <button class="p-2 rounded-full hover:bg-gray-100 mr-2" onclick="toggleSearchBar()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Zone de recherche (cachée par défaut) -->
                <div id="search-container">
                    <div class="flex items-center justify-between h-9 mt-1 mb-1">
                        <div class="flex-1 mr-4">
                            <div class="border-2 border-gray-300 rounded-full flex items-center py-2 px-3 bg-gray-100 inp-ss">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="search-input" class="w-full bg-transparent outline-none" placeholder="Rechercher dans Tous" onfocus="focusInpT()" onblur="onFocusInpT()">
                            </div>
                        </div>
                        <button onclick="toggleSearchBar()" class="text-black font-medium">Annuler</button>
                    </div>
                </div>
                
                <!-- Boutons de filtrage (toujours visibles, jamais animés) -->
                <div class="flex space-x-2 mt-2">
                    <button class="px-4 py-2 bg-[#005555] text-white rounded-full text-sm font-medium">Tous</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium">Non lus</button>
                </div>
            </div>
            
            <!-- Liste des conversations -->
            <div class="overflow-y-auto" style="height: calc(100% - 110px);">
                <!-- Conversation active -->
                <div class="p-4 border-l-4 border-[#005555] bg-gray-50 hover:bg-gray-100 cursor-pointer">
                    <div class="flex">
                        <div class="mr-3">
                            <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white">
                                S
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-between items-start">
                                <div class="font-medium">Sirine</div>
                                <div class="text-sm text-gray-500">17:18</div>
                            </div>
                            <div class="text-sm text-gray-600 truncate">Bonjour</div>
                            <div class="flex items-center mt-1">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="text-xs text-gray-500">Demande d'information envoyée · 3...</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Autre conversation -->
                <div class="p-4 hover:bg-gray-100 cursor-pointer">
                    <div class="flex">
                        <div class="mr-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                                M
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-between items-start">
                                <div class="font-medium">Mohamed Y Nabila</div>
                                <div class="text-sm text-gray-500">14:51</div>
                            </div>
                            <div class="text-sm text-gray-600 truncate">Que puis-je faire pour vous ?</div>
                            <div class="flex items-center mt-1">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="text-xs text-gray-500">Demande d'information envoyée · 6...</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Autre conversation -->
                <div class="p-4 hover:bg-gray-100 cursor-pointer">
                    <div class="flex">
                        <div class="mr-3">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white">
                                K
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-between items-start">
                                <div class="font-medium">Kaoutar</div>
                                <div class="text-sm text-gray-500">14:44</div>
                            </div>
                            <div class="text-sm text-gray-600 truncate">Vous: Demande d'information e...</div>
                            <div class="flex items-center mt-1">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                <span class="text-xs text-gray-500">Demande d'information envoyée · 4...</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Autre conversation -->
                <div class="p-4 hover:bg-gray-100 cursor-pointer">
                    <div class="flex">
                        <div class="mr-3">
                            <div class="w-10 h-10 overflow-hidden rounded-full">
                                <img src="/api/placeholder/40/40" alt="Kate and Nick" class="w-full h-full object-cover" />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-between items-start">
                                <div class="font-medium">Kate and Nick, Nick</div>
                                <div class="text-sm text-gray-500">21/12/24</div>
                            </div>
                            <div class="text-sm text-gray-600 truncate">Kate and Nick: Bonjour, comme...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conversation active -->
        <div class="conversation-area w-1/2 flex flex-col bg-white border-r border-gray-300" id="conversation-area">
            <!-- En-tête avec le nom de l'utilisateur et bouton toggle -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white mr-3">
                        S
                    </div>
                    <div>
                        <h2 class="font-semibold text-lg">Sirine</h2>
                    </div>
                </div>
                <button class="toggle-button" id="toggle-sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            
            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 bg-white">
                <div class="mb-4 text-center text-sm text-gray-500">
                    Aujourd'hui
                </div>
                
                <!-- Message système -->
                <div class="mb-6 text-center">
                    <p class="text-gray-600 text-sm">
                        Votre demande d'information pour 1 voyageur <br>
                        le 3-8 avr. a été envoyée. <a href="#" class="text-gray-800 underline">Afficher l'annonce</a>
                    </p>
                </div>
                
                <!-- Message de Sirine -->
                <div class="flex mb-4">
                    <div class="mr-2">
                        <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white text-sm">
                            S
                        </div>
                    </div>
                    <div class="max-w-xs">
                        <div class="bg-gray-100 rounded-lg p-3">
                            <p>Bonjour</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">17:18</div>
                    </div>
                </div>
                
                <!-- Message de l'utilisateur -->
                <div class="flex flex-row-reverse mb-4">
                    <div class="ml-2">
                        <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center text-white text-sm">
                            bnj
                        </div>
                    </div>
                    <div class="max-w-xs">
                        <div class="bg-green-100 text-gray-800 rounded-lg p-3">
                            <p></p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 text-right">
                            11:15 · Lu par Sirine
                        </div>
                    </div>
                </div>
                
                <!-- Délai de réponse moyen -->
                <div class="flex items-center justify-center mt-6 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-gray-500">Délai de réponse moyen: 3 heures</span>
                </div>
            </div>
            
            <!-- Saisie de message -->
            <div class="p-4 border-gray-200">
                <div class="btns flex items-center bg-white border-2 border-gray-300 rounded-full pl-4 pr-2 py-2">
                    <input type="text" id="saisie" class="flex-1 outline-none" placeholder="Saisir un message..." style="height: 30px;" onkeyup="showSend()" onfocus="focusInp()" onblur="onFocusInp()">
                    <button class="send">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Panneau de détails de réservation -->
        <div class="sidebar-right w-1/4 bg-white" id="reservation-sidebar">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="font-semibold text-lg" style="padding: 5.9px;">Réservation</h2>
            </div>
            
            <div class="overflow-y-auto" style="height: calc(100% - 57px);">
                <!-- Image de la propriété -->
                <div class="mb-4 relative">
                    <img src="/api/placeholder/400/300" alt="Logement" class="w-full h-48 object-cover">
                    <div class="absolute top-4 left-0 bg-white px-3 py-1 text-sm font-semibold">
                        DEMANDE D'INFORMATION ENVOYÉE
                    </div>
                </div>
                
                <!-- Informations de réservation -->
                <div class="px-4">
                    <h3 class="text-xl font-bold mb-4">Vous avez envoyé un message à Sirine à propos de son logement.</h3>
                    
                    <p class="text-gray-600 mb-6">
                        La plupart des hôtes répondent sous 24 heures. Si ce logement a votre préférence, entrez vos informations de paiement pour faire une demande de réservation officielle.
                    </p>
                    
                    <!-- Bouton de réservation -->
                    <button class="w-full bg-[#005555] text-white font-semibold py-3 rounded-lg hover:bg-gray-800 mb-8">
                        Demande de réservation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/messagerie.js"></script>
    <script>
        // Script pour ajuster la hauteur du conteneur principal en fonction de la navbar
        document.addEventListener('DOMContentLoaded', function() {
            function adjustHeight() {
                const navbarHeight = document.querySelector('.navbar-container').offsetHeight;
                const windowHeight = window.innerHeight;
                document.getElementById('main-container').style.height = (windowHeight - navbarHeight) + 'px';
            }
            
            // Ajuster la hauteur au chargement et au redimensionnement
            adjustHeight();
            window.addEventListener('resize', adjustHeight);
        });
    </script>
</body>
</html>