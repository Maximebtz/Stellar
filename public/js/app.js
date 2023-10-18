document.addEventListener("DOMContentLoaded", function () {
  // document ready function (JQuery)
  /****ProfilMenu****/
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

  // Écouteur de clics sur le document pour fermer le menu si nécessaire
  document.addEventListener("click", function (event) {
    if (menu.style.display === "flex" && !menu.contains(event.target)) {
      menu.style.display = "none";
    }
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
