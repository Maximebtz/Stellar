const key = 'UsTkArdJgWSIZOEqSvTe';
const map = L.map('map').setView([0, 0], 1);

const mtLayer = L.maptilerLayer({
  apiKey: key,
  style: "streets-v2", //optional
}).addTo(map);

// Définir un niveau de zoom minimum pour la carte
map.setMinZoom(5); // Remplacez 1 par le niveau de zoom minimum souhaité