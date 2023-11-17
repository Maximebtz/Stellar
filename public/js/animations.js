/****Ajax Window Animation Object****/

// Sélectionnez toutes les cartes avec la classe .slide-in
const slideIn = document.querySelectorAll(".slide-in");

// Créez une instance Intersection Observer
const observer = new IntersectionObserver((entries, observer) => {
  let delay = 0; // Délai initial
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      // Utilisez setTimeout pour ajouter la classe avec un délai croissant
      setTimeout(() => {
        entry.target.classList.add("slide-in-active");
        entry.target.classList.add("slide-in-active");
      }, delay);

      // Augmentez le délai pour le prochain objet
      delay += 75; // Vous pouvez ajuster la durée du délai (en millisecondes) entre les objets
      // Arrêtez d'observer cet objet une fois qu'elle a été animée
      observer.unobserve(entry.target);
    }
  });
});

// Observez chaque objet
slideIn.forEach((object) => {
  observer.observe(object);
});

// /****Checkbox Slide****/
// function enableCheckboxSlideByContainerId(idContainer) {
//   const container = document.getElementById(idContainer);

//   let isHorizontalMouseDown = false;
//   let startX;
//   let scrollLeft;

//   container.addEventListener("mousedown", (e) => {
//     isHorizontalMouseDown = true;
//     startX = e.pageX - container.offsetLeft;
//     scrollLeft = container.scrollLeft;
//     container.style.scrollBehavior = "unset";
//   });

//   container.addEventListener("mouseup", () => {
//     isHorizontalMouseDown = false;
//     container.style.scrollBehavior = "smooth";
//   });

//   container.addEventListener("mouseleave", () => {
//     isHorizontalMouseDown = false;
//     container.style.scrollBehavior = "smooth";
//   });

//   container.addEventListener("mousemove", (e) => {
//     if (!isHorizontalMouseDown) return;
//     e.preventDefault();
//     const x = e.pageX - container.offsetLeft;
//     const walk = (x - startX) * 1.5;
//     container.scrollLeft = scrollLeft - walk;
//   });
// }

// enableCheckboxSlideByContainerId("checkbox-wrapper-type");
// enableCheckboxSlideByContainerId("checkbox-wrapper-categories");

// const verticalCheckboxContainer = document.querySelector(
//   ".checkbox-wrapper-17"
// );
// let isVerticalMouseDown = false;
// let startY;
// let scrollTop;

// verticalCheckboxContainer.addEventListener("mousedown", (e) => {
//   isVerticalMouseDown = true;
//   startY = e.pageY - verticalCheckboxContainer.offsetTop;
//   scrollTop = verticalCheckboxContainer.scrollTop;
//   verticalCheckboxContainer.style.scrollBehavior = "unset";
// });

// verticalCheckboxContainer.addEventListener("mouseup", () => {
//   isVerticalMouseDown = false;
//   verticalCheckboxContainer.style.scrollBehavior = "smooth";
// });

// verticalCheckboxContainer.addEventListener("mouseleave", () => {
//   isVerticalMouseDown = false;
//   verticalCheckboxContainer.style.scrollBehavior = "smooth";
// });

// verticalCheckboxContainer.addEventListener("mousemove", (e) => {
//   if (!isVerticalMouseDown) return;
//   e.preventDefault();
//   const y = e.pageY - verticalCheckboxContainer.offsetTop;
//   const walk = (y - startY) * 1.5;
//   verticalCheckboxContainer.scrollTop = scrollTop - walk;
// });
