document.addEventListener("DOMContentLoaded", function () {
  /*******Calendar*******/
  $(document).ready(function () {
    let arrivalDateInput = $('[name="reservation[arrivalDate]"]');
    let departureDateInput = $('[name="reservation[departureDate]"]');
    let advertId = $("#calendar").data("advert-id");
    let reservedDates = $("#calendar").data("reserved-dates");

    $("#calendar").datepicker({
      // Paramétrer les différentes options de Bootstrap Datepicker
      maxViewMode: 0,
      language: "fr",
      multidate: 2,
      daysOfWeekHighlighted: "0,6",
      startDate: "today",
      todayHighlight: true,
      beforeShowDay: function (date) {
        let formattedDate = $.datepicker.formatDate("yy-mm-dd", date);

        // Parcourir les dates réservées pour l'annonce
        for (let i = 0; i < reservedDates.length; i++) {
          let reservedStartDate = reservedDates[i][0];
          let reservedEndDate = reservedDates[i][1];

          // Comparer la date actuelle avec les dates réservées
          if (
            formattedDate >= reservedStartDate &&
            formattedDate <= reservedEndDate
          ) {
            // Render la date non cliquable (désactivée) la griser
            return {
              enabled: false,
              classes: "disabled-date",
            };
          }
        }

        // Les dates non réservées resteront activées et ne seront pas grises
        return {
          enabled: true,
          classes: "",
        };
      },
    });

    // Fonction pour mettre à jour l'état du bouton de réservation
    // (Utilisable si les dates sont choisis et inversement pour le cas contraire)
    function updateReservationButton() {
      if (arrivalDateInput && departureDateInput) {
        $("#start-button").prop("disabled", false); // Activer le bouton de réservation
      } else {
        $("#start-button").prop("disabled", true); // Désactiver le bouton de réservation
      }
    }

    document
      .getElementById("close-popup")
      .addEventListener("click", function () {
        // Masquer l'alerte #alert-dates
        $("#alert-dates").hide();
      });

    $("#calendar").on("changeDate", function (e) {
      let selectedDates = e.dates;

      if (selectedDates.length === 2) {
        arrivalDateInput = selectedDates[0];
        departureDateInput = selectedDates[1];

        if (departureDateInput <= arrivalDateInput) {
          $("#alert-dates").fadeIn();
          $("#alert-dates").show();
          // Réinitialiser les champs de date et annuler la sélection
          $("#calendar").datepicker("clearDates");
          return;
        }

        // Vérifier si arrivalDate est définie avant de l'utiliser
        if (arrivalDateInput) {
          // Formatage de la date au format "yyyy-MM-dd"
          let formattedDate =
            arrivalDateInput.getFullYear() +
            "-" +
            (arrivalDateInput.getMonth() + 1).toString().padStart(2, "0") +
            "-" +
            arrivalDateInput.getDate().toString().padStart(2, "0");
          $('[name="reservation[arrivalDate]"]').val(formattedDate); // Mettre à jour la valeur du champ d'arrivée
        }
        // Vérifier si departureDate est définie avant de l'utiliser
        if (departureDateInput) {
          // Formatage de la date au format "yyyy-MM-dd"
          let formattedDate =
            departureDateInput.getFullYear() +
            "-" +
            (departureDateInput.getMonth() + 1).toString().padStart(2, "0") +
            "-" +
            departureDateInput.getDate().toString().padStart(2, "0");
          $('[name="reservation[departureDate]"]').val(formattedDate); // Mettre à jour la valeur du champ de départ
        }

        $("#calendar")
          .find(".day")
          .each(function () {
            let currentDate = new Date($(this).data("date"));
            if (
              currentDate > arrivalDateInput &&
              currentDate < departureDateInput
            ) {
              $(this).addClass("range");
            } else {
              $(this).removeClass("range");
            }
          });

        // Calcul du nombre de nuits
        let numNights = Math.floor(
          (departureDateInput - arrivalDateInput) / (1000 * 60 * 60 * 24)
        );
        $("#numNights").text(numNights);

        // Calcul du montant total des nuits
        let pricePerNight = parseFloat($("#pricePerNight").text());
        let totalNightPrice = (pricePerNight * numNights).toFixed(2);
        $("#totalNightPrice").text(totalNightPrice + "€");

        // Calcul du montant des frais Stellar (5%)
        let stellarFees = (totalNightPrice * 0.05).toFixed(2);
        $("#stellarFees").text(stellarFees + "€");

        // Calcul du total (nuits + frais)
        let totalPrice = (
          parseFloat(totalNightPrice) + parseFloat(stellarFees)
        ).toFixed(2);
        $("#totalPrice").text(totalPrice + "€");

        updateReservationButton();
      } else {
        arrivalDateInput = null;
        departureDateInput = null;
        $("#calendar").find(".day").removeClass("range");
        $('[name="reservation[arrivalDate]"]').val("");
        $('[name="reservation[departureDate]"]').val("");
        $("#numNights").text("0");
        $("#totalNightPrice").text("0€");
        $("#stellarFees").text("0€");
        $("#totalPrice").text("0€");
      }

      updateReservationButton();
    });

    function formatDate(date) {
      let day = date.getDate();
      let month = date.getMonth() + 1;
      let year = date.getFullYear();

      if (day < 10) {
        day = "0" + day;
      }
      if (month < 10) {
        month = "0" + month;
      }

      return day + "/" + month + "/" + year;
    }
  });

  /*******Form MultiStep*******/
  // letiables for tracking the current step
  let currentStep = 1;
  const totalSteps = 4;

  // Function to show the next step
  function showNextStep() {
    if (currentStep < totalSteps) {
      currentStep++;
      updateStepDisplay();
    }
  }

  // Function to show the previous step
  function showPreviousStep() {
    if (currentStep > 1) {
      currentStep--;
      updateStepDisplay();
    }
  }

  // Function to update the display based on the current step
  function updateStepDisplay() {
    // Masquer toutes les étapes
    for (let i = 1; i <= totalSteps; i++) {
      $(`#step-${i}`).hide(); // Masquer l'étape
    }

    // Afficher la nouvelle étape avec une animation de fondu enchaîné
    $(`#step-${currentStep}`).fadeOut(800); // Vous pouvez ajuster la durée de l'animation ici (300 ms dans cet exemple)
    $(`#step-${currentStep}`).fadeIn(800); // Vous pouvez ajuster la durée de l'animation ici (300 ms dans cet exemple)
    $(`#step-${currentStep}`).show(); // Vous pouvez ajuster la durée de l'animation ici (300 ms dans cet exemple)

    // Pour afficher un élément
  }

  // Event listener for the "start-button" element (Step 1)
  document
    .getElementById("start-button")
    .addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the default link behavior (don't navigate)
      showNextStep(); // Transition to the next step
    });

  // Event listeners for the Next and Previous buttons
  document
    .getElementById("next-button-step-2")
    .addEventListener("click", showNextStep);
  document
    .getElementById("prev-button-step-2")
    .addEventListener("click", showPreviousStep);

  document
    .getElementById("next-button-step-3")
    .addEventListener("click", showNextStep);
  document
    .getElementById("prev-button-step-3")
    .addEventListener("click", showPreviousStep);

  document
    .getElementById("prev-button-step-4")
    .addEventListener("click", showPreviousStep);

  // Initialize the display to the first step
  updateStepDisplay();
});
