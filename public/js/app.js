document.addEventListener("DOMContentLoaded", function () {
  /****Profil Menu****/
  function displayProfilMenu() {
    const menu = document.getElementById("profil-menu");
    if (menu.style.display === "flex") {
      menu.style.display = "none";
    } else {
      menu.style.display = "flex";
    }
  }

  const profilPicture = document.getElementById("profil-picture");
  const menu = document.getElementById("profil-menu");

  profilPicture.addEventListener("click", function (event) {
    event.stopPropagation(); // Éviter que le clic ne se propage à la page et ferme immédiatement le menu
    displayProfilMenu();
  });

  document.addEventListener("click", function (event) {
    if (!menu.contains(event.target) && !profilPicture.contains(event.target)) {
      menu.style.display = "none"; // Fermer le menu
    }
  });

  /****Annonces cliquées****/
  const adverts = document.querySelectorAll(".card-click");

  // Charger les états précédents depuis le sessionStorage
  adverts.forEach((advert, index) => {
    if (sessionStorage.getItem(userId + "-advertClicked-" + index)) {
      advert.classList.add("clicked");
    }
  });

  // Ajouter un écouteur d'événements pour chaque annonce
  adverts.forEach((advert, index) => {
    advert.addEventListener("click", function () {
      setTimeout(() => {
        this.classList.add("clicked");
      }, 3500);

      sessionStorage.setItem(userId + "-advertClicked-" + index, true);
    });
  });

  /***Delete Confirmation****/
  // Sélectionne tous les boutons "supprimer" ayant la classe .modif
  const deleteButtons = document.querySelectorAll(".delete");

  // Attache un événement click à chaque bouton "supprimer"
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      // Empêche le comportement par défaut (si c'est un lien ou un bouton de formulaire)
      event.preventDefault();

      // Trouve le parent le plus proche ayant la classe .advert-card
      const advertCard = button.closest(".advert-card");

      // Trouve le div .delete-conf dans ce .advert-card
      const deleteConf = advertCard.querySelector(".delete-conf");

      // Toggle la visibilité
      if (deleteConf.style.display === "none" || !deleteConf.style.display) {
        deleteConf.style.display = "flex";
      } else {
        deleteConf.style.display = "none";
      }
    });
  });

  // Même chose pour les boutons "Retour"
  const retourButtons = document.querySelectorAll(".delete-conf span");

  retourButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const advertCard = button.closest(".advert-card");
      const deleteConf = advertCard.querySelector(".delete-conf");
      deleteConf.style.display = "none";
    });
  });
});
