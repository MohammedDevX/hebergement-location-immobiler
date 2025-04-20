document.addEventListener("DOMContentLoaded", function () {
  // Configuration initiale
  const PRICE_PER_NIGHT = 285;
  const SERVICE_FEE = 1;

  // État du calendrier
  let currentViewDate = new Date(2025, 9, 1); // Octobre 2025 (mois indexés à 0)
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
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre",
  ];

  // Jours de la semaine en français (version courte)
  const weekDaysShort = [
    "Lun.",
    "Mar.",
    "Mer.",
    "Jeu.",
    "Ven.",
    "Sam.",
    "Dim.",
  ];

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
      month1.textContent = `${
        monthNames[currentViewDate.getMonth()]
      } ${currentViewDate.getFullYear()}`;
    }

    if (month2) {
      month2.textContent = `${
        monthNames[nextMonth.getMonth()]
      } ${nextMonth.getFullYear()}`;
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

    allDays.forEach((day) => {
      if (!day.dataset.day) return;

      const dayNum = parseInt(day.dataset.day);
      const monthNum = parseInt(day.dataset.month);
      const yearNum = parseInt(day.dataset.year);
      const currentDate = new Date(yearNum, monthNum - 1, dayNum);

      // Marquer la date de début
      if (
        currentDate.getDate() === startDate.getDate() &&
        currentDate.getMonth() === startDate.getMonth() &&
        currentDate.getFullYear() === startDate.getFullYear()
      ) {
        day.classList.add("day-selected");
      }

      // Marquer la date de fin
      if (
        endDate &&
        currentDate.getDate() === endDate.getDate() &&
        currentDate.getMonth() === endDate.getMonth() &&
        currentDate.getFullYear() === endDate.getFullYear()
      ) {
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
    const calendarContainer = document.querySelector(
      ".flex.flex-col.md\\:flex-row.space-y-6.md\\:space-y-0.md\\:space-x-0"
    );
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

    weekDaysShort.forEach((day) => {
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

    weekDaysShort.forEach((day) => {
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
    const clearDatesBtnContainer = document.querySelector(
      ".flex.items-center.mt-4"
    );
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

      // Vérifier si le jour est dans le passé
      const currentDate = new Date(year, month, day);
      if (currentDate < today) {
        dayElement.classList.add("text-gray-300");
        dayElement.classList.remove("cursor-pointer");
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
        dayElement.classList.add("day-selected");
        endDate = clickedDate;
        endMonth = month;

        // Marquer les jours entre les deux dates
        markDaysInRange();

        // Mettre à jour l'affichage
        updateDateDisplay();
        updatePriceDisplay();

        console.log(
          "Plage sélectionnée:",
          formatDate(startDate),
          "à",
          formatDate(endDate)
        );
      }
    }
  }

  // Marquer les jours dans la plage sélectionnée
  function markDaysInRange() {
    if (!startDate || !endDate) return;

    const allDays = document.querySelectorAll(".calendar-day[data-day]");

    allDays.forEach((day) => {
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
    allDays.forEach((day) => {
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
        dateRangeDisplay.textContent = `${formatDate(
          startDate,
          "d mmm yyyy"
        )} - ${formatDate(endDate, "d mmm yyyy")}`;
      } else {
        endDateElement.textContent = "--/--/----";
        dateRangeDisplay.textContent = `${formatDate(
          startDate,
          "d mmm yyyy"
        )} - Sélectionnez une date de fin`;
      }
    } else {
      startDateElement.textContent = "--/--/----";
      endDateElement.textContent = "--/--/----";
      dateRangeDisplay.textContent = "Sélectionnez vos dates";
    }
  }

  // Mettre à jour l'affichage du prix
  function updatePriceDisplay() {
    if (startDate && endDate) {
      // Calculer le nombre de nuits
      const nights = calculateNights(startDate, endDate);
      const totalPrice = nights * PRICE_PER_NIGHT;

      // Mettre à jour le prix principal
      document.querySelector(
        ".text-lg.font-bold"
      ).innerHTML = `${totalPrice} € <span class="text-sm font-normal">pour ${nights} nuits</span>`;

      // Mettre à jour le détail des prix
      const priceDetails = document.querySelectorAll(
        ".space-y-2.text-sm .flex"
      );
      if (priceDetails.length >= 3) {
        priceDetails[0].innerHTML = `<p class="underline">${PRICE_PER_NIGHT} € x ${nights} nuits</p><p>${totalPrice} €</p>`;
        priceDetails[2].innerHTML = `<p>Total</p><p>${
          totalPrice + SERVICE_FEE
        } €</p>`;
      }
    } else {
      // Réinitialiser à la valeur par défaut
      document.querySelector(
        ".text-lg.font-bold"
      ).innerHTML = `1426 € <span class="text-sm font-normal">pour 5 nuits</span>`;

      const priceDetails = document.querySelectorAll(
        ".space-y-2.text-sm .flex"
      );
      if (priceDetails.length >= 3) {
        priceDetails[0].innerHTML =
          '<p class="underline">285 € x 5 nuits</p><p>1 425 €</p>';
        priceDetails[2].innerHTML = "<p>Total</p><p>1 426 €</p>";
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
      return `${day < 10 ? "0" + day : day}/${
        month < 10 ? "0" + month : month
      }/${year}`;
    } else if (format === "d mmm yyyy") {
      return `${day} ${monthNames[month - 1]
        .toLowerCase()
        .substring(0, 3)}. ${year}`;
    }

    return `${day}/${month}/${year}`;
  }

  // Définir les dates par défaut (11-16 avril 2025)
  function setDefaultDates() {
    startDate = new Date(2025, 3, 11); // 11 avril 2025
    startMonth = 4; // Avril
    endDate = new Date(2025, 3, 16); // 16 avril 2025
    endMonth = 4; // Avril

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
      prevMonthBtn.addEventListener("click", function () {
        navigatePreviousMonth();
      });
    }

    if (nextMonthBtn) {
      nextMonthBtn.addEventListener("click", function () {
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

    bookingBtns.forEach((btn) => {
      btn.addEventListener("click", function () {
        if (startDate && endDate) {
          alert(
            `Réservation initiée pour la période du ${formatDate(
              startDate
            )} au ${formatDate(endDate)}`
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
        galleryItems.forEach((item) => {
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
          galleryItems.forEach((item) => {
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
      if (
        e.key === "Escape" &&
        galleryModal &&
        !galleryModal.classList.contains("hidden")
      ) {
        // Réinitialiser les animations
        galleryItems.forEach((item) => {
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

  // Initialiser le calendrier
  initCalendar();
});
