document.addEventListener("DOMContentLoaded", function () {

  /****Profil Menu****/
  function displayProfilMenu() {
    const menu = document.getElementById("profil-menu");
    if (menu.style.display === "flex") {
      // Si le menu est déjà ouvert, le fermer
      menu.style.display = "none";
    } else {
      // Sinon, ouvrir le menu
      menu.style.display = "flex";
    }
  }

  const profilPicture = document.getElementById("profil-picture");
  const menu = document.getElementById("profil-menu");

  profilPicture.addEventListener("click", function (event) {
    event.stopPropagation(); // Éviter que le clic ne se propage à la page et ferme immédiatement le menu
    displayProfilMenu();
  });

  /****Annonces cliquées****/
  const adverts = document.querySelectorAll(".card-click");

  // Charger les états précédents depuis le localStorage
  adverts.forEach((advert, index) => {
    if (localStorage.getItem("advertClicked-" + index)) {
      advert.classList.add("clicked");
    }
  });

  // Ajouter un écouteur d'événements pour chaque annonce
  adverts.forEach((advert, index) => {
    advert.addEventListener("click", function () {
      setTimeout(() => {
        this.classList.add("clicked");
      }, 3500);

      localStorage.setItem("advertClicked-" + index, true);
    });
  });
  

  /****Delete Confirmation****/
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

  
  /****Checkbox Slide****/
  function enableCheckboxSlideByContainerId(idContainer) {
    const container = document.getElementById(idContainer);

    let isHorizontalMouseDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener("mousedown", (e) => {
      isHorizontalMouseDown = true;
      startX = e.pageX - container.offsetLeft;
      scrollLeft = container.scrollLeft;
      container.style.scrollBehavior = "unset";
    });

    container.addEventListener("mouseup", () => {
      isHorizontalMouseDown = false;
      container.style.scrollBehavior = "smooth";
    });

    container.addEventListener("mouseleave", () => {
      isHorizontalMouseDown = false;
      container.style.scrollBehavior = "smooth";
    });

    container.addEventListener("mousemove", (e) => {
      if (!isHorizontalMouseDown) return;
      e.preventDefault();
      const x = e.pageX - container.offsetLeft;
      const walk = (x - startX) * 1.5;
      container.scrollLeft = scrollLeft - walk;
    });
  }

  enableCheckboxSlideByContainerId("checkbox-wrapper-type");
  enableCheckboxSlideByContainerId("checkbox-wrapper-categories");

  const verticalCheckboxContainer = document.querySelector(
    ".checkbox-wrapper-17"
  );
  let isVerticalMouseDown = false;
  let startY;
  let scrollTop;

  verticalCheckboxContainer.addEventListener("mousedown", (e) => {
    isVerticalMouseDown = true;
    startY = e.pageY - verticalCheckboxContainer.offsetTop;
    scrollTop = verticalCheckboxContainer.scrollTop;
    verticalCheckboxContainer.style.scrollBehavior = "unset";
  });

  verticalCheckboxContainer.addEventListener("mouseup", () => {
    isVerticalMouseDown = false;
    verticalCheckboxContainer.style.scrollBehavior = "smooth";
  });

  verticalCheckboxContainer.addEventListener("mouseleave", () => {
    isVerticalMouseDown = false;
    verticalCheckboxContainer.style.scrollBehavior = "smooth";
  });

  verticalCheckboxContainer.addEventListener("mousemove", (e) => {
    if (!isVerticalMouseDown) return;
    e.preventDefault();
    const y = e.pageY - verticalCheckboxContainer.offsetTop;
    const walk = (y - startY) * 1.5;
    verticalCheckboxContainer.scrollTop = scrollTop - walk;
  });
});
