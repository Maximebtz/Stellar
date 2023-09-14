/****Ajax Window Animation Object****/

// Sélectionnez toutes les cartes avec la classe .slide-in
const slideIn = document.querySelectorAll('.slide-in');

// Créez une instance Intersection Observer
const observer = new IntersectionObserver((entries, observer) => {
    let delay = 0; // Délai initial
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Utilisez setTimeout pour ajouter la classe avec un délai croissant
            setTimeout(() => {
                entry.target.classList.add('slide-in-active');
                entry.target.classList.add('slide-in-active');
            }, delay);

            // Augmentez le délai pour le prochain objet
            delay += 75; // Vous pouvez ajuster la durée du délai (en millisecondes) entre les objets
            // Arrêtez d'observer cet objet une fois qu'elle a été animée
            observer.unobserve(entry.target);
        }
    });
});

// Observez chaque objet
slideIn.forEach(object => {
    observer.observe(object);
});
