
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

const profilPicture = document.getElementById('profil-picture');
const menu = document.getElementById("profil-menu");

profilPicture.addEventListener('click', function(event) {
    event.stopPropagation(); // Éviter que le clic ne se propage à la page et ferme immédiatement le menu
    displayProfilMenu();
});

// Écouteur de clics sur le document pour fermer le menu si nécessaire
document.addEventListener('click', function(event) {
    if (menu.style.display === "flex" && !menu.contains(event.target)) {
        menu.style.display = "none";
    }
});


/****ShowMorePictures****/
function displayMorePictures() {
    const showMoreBtn = document.getElementById("showMoreBtn");
    if (showMoreBtn.style.display === "flex") {
        showMoreBtn.style.display = "none";
    } else {
        showMoreBtn.style.display = "flex";
    }
}

// const profilPicture = document.getElementById('profil-picture');
const showMoreBtn = document.getElementById("showMoreBtn");

// profilPicture.addEventListener('click', function(event) {
//     event.stopPropagation(); 
//     displayProfilMenu();
// });


document.addEventListener('click', function(event) {
    if (showMoreBtn.style.display === "flex" && !showMoreBtn.contains(event.target)) {
        showMoreBtn.style.display = "none";
    }
});
