// Script pour gérer le toggle de la barre latérale
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('reservation-sidebar');
    const conversationArea = document.getElementById('conversation-area');
    
    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('hidden-sidebar');
        
        // Ajuster la largeur de la zone de conversation quand la sidebar est masquée
        if (sidebar.classList.contains('hidden-sidebar')) {
            conversationArea.style.width = '75%';
            // Changer l'icône pour indiquer qu'on peut ouvrir la sidebar
            toggleButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            `;
        } else {
            conversationArea.style.width = '50%';
            // Changer l'icône pour indiquer qu'on peut fermer la sidebar
            toggleButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            `;
        }
    });
});

function showSend() {
    const inp = document.getElementById("saisie").value;
    const send = document.querySelector(".send");
    
    if (inp.trim().length > 0) {
        // Affiche le conteneur
        send.style.display = "flex";
        // Active l'animation après un léger délai
        setTimeout(() => {
            send.classList.add("active");
        }, 10);
    } else {
        // Désactive l'animation et cache instantanément
        send.classList.remove("active");
        setTimeout(() => {
            send.style.display = "none";
        }, 300); // Attend que l'animation se termine avant de cacher
    }
}

function focusInp() {
    let saisieBar = document.querySelector(".btns");
    saisieBar.style.setProperty("border", "2px solid black", "important");
}

function onFocusInp() {
    let saisieBar = document.querySelector(".btns");
    saisieBar.style.setProperty("border", "", "important"); 
}

function focusInpT() {
    let searchInp = document.querySelector(".inp-ss");
    searchInp.style.setProperty("border", "2px solid black", "important");
}

function onFocusInpT() {
    let saisieBar = document.querySelector(".inp-ss");
    saisieBar.style.setProperty("border", "", "important"); 
}

// Fonction pour gérer l'animation de la barre de recherche
// Dans la section script, modifiez la fonction toggleSearchBar() comme ceci:
function toggleSearchBar() {
const searchContainer = document.getElementById('search-container');
const searchInput = document.getElementById('search-input');
const headerContent = document.getElementById('header-content');

if (!searchContainer.classList.contains('visible')) {
    // Ouvrir la recherche
    searchContainer.classList.add('visible');
    headerContent.style.display = 'none';
    
    setTimeout(() => {
        searchInput.classList.add('expanded');
        searchInput.focus();
    }, 10); // Réduire le délai à 10ms
} else {
    // Fermer la recherche
    searchInput.classList.remove('expanded');
    
    // Attendre que l'animation de fermeture soit terminée avant de réafficher le header
    setTimeout(() => {
        searchContainer.classList.remove('visible');
        searchInput.value = '';
        headerContent.style.display = 'flex'; // Réafficher seulement après la fermeture
    }, 300); // Correspond à la durée de la transition CSS
}
}