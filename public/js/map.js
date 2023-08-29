// Création de la carte
var map = L.map('map').setView([51.5, -0.09], 13);

// Ajout d'une couche de tuiles OpenStreetMap à la carte
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

// Définir le style de la carte à partir du JSON fourni
var styleArray = [
  {
    featureType: "administrative",
    elementType: "all",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "administrative",
    elementType: "geometry.fill",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "administrative",
    elementType: "geometry.stroke",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "administrative",
    elementType: "labels.text",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "landscape",
    elementType: "all",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "poi",
    elementType: "all",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "poi",
    elementType: "geometry",
    stylers: [
      {
        lightness: "0",
      },
    ],
  },
  {
    featureType: "poi.medical",
    elementType: "geometry.fill",
    stylers: [
      {
        lightness: "-5",
      },
    ],
  },
  {
    featureType: "poi.park",
    elementType: "geometry.fill",
    stylers: [
      {
        visibility: "on",
      },
      {
        color: "#a7ce95",
      },
      {
        lightness: "45",
      },
    ],
  },
  {
    featureType: "poi.school",
    elementType: "geometry",
    stylers: [
      {
        color: "#be9b7b",
      },
      {
        lightness: "70",
      },
    ],
  },
  {
    featureType: "poi.sports_complex",
    elementType: "geometry",
    stylers: [
      {
        color: "#5d4b46",
      },
      {
        lightness: "60",
      },
    ],
  },
  {
    featureType: "road",
    elementType: "all",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "transit.station",
    elementType: "geometry",
    stylers: [
      {
        saturation: "23",
      },
      {
        lightness: "10",
      },
      {
        gamma: "0.8",
      },
      {
        hue: "#b000ff",
      },
    ],
  },
  {
    featureType: "water",
    elementType: "all",
    stylers: [
      {
        visibility: "on",
      },
    ],
  },
  {
    featureType: "water",
    elementType: "geometry.fill",
    stylers: [
      {
        color: "#a2daf2",
      },
    ],
  },
];

var styleEditor = new L.StyleEditor(map, {
    position: 'topleft', // Positionnez le panneau d'édition de style à l'emplacement souhaité
});
styleEditor.setStyle(styleArray);