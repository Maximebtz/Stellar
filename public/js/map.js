// Créez la carte Leaflet
const map = L.map("map").setView([40.7127281, -74.0060152], 12);

// Ajoutez une couche MapTiler à la carte
const mtLayer = L.maptilerLayer({
    apiKey: "VOTRE_CLE_MAPTILER",
    style: "streets-v2",
}).addTo(map);

// Fonction pour ajouter un marqueur sur la carte pour chaque annonce
function addMarker(advert) {
    // Utilisez l'adresse de l'annonce pour obtenir les coordonnées
    let address = advert.address + ", " + advert.city + ", " + advert.country;

    // Effectuez une requête de géocodage à l'aide de Google Maps Geocoding API
    // Utilisez votre clé API Google Maps ici
    let geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURIComponent(address) + "&key=AIzaSyBwRdXofYPItKiTcLb5T2j7ABGrRnbhhyc";

    fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data.results.length > 0) {
                let location = data.results[0].geometry.location;
                let lat = location.lat;
                let lng = location.lng;

                // Ajoutez un marqueur pour l'annonce sur la carte
                let marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<strong>" + advert.title + "</strong><br>" + address).openPopup();
            }
        })
        .catch(error => {
            console.error("Erreur de géocodage : " + error);
        });
}

// Récupérez vos annonces depuis votre backend (par exemple, PHP) et ajoutez les marqueurs
fetch("api/obtenir_annonces.php")
    .then(response => response.json())
    .then(data => {
        data.forEach(advert => {
            addMarker(advert);
        });
    })
    .catch(error => {
        console.error("Erreur lors de la récupération des annonces : " + error);
    });