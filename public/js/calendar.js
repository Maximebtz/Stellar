// Sélection des éléments HTML
const arrivalDateInput = document.getElementById("arrivalDate");
const departureDateInput = document.getElementById("departureDate");
const pricePerNight = document.getElementById("pricePerNight");
const totalPrice = document.getElementById("totalPrice");
const stellarFees = document.getElementById("stellarFees");
const numNights = document.getElementById("numNights");

// Prix par nuit et taux de frais Stellar (5%)
const nightlyPrice = parseFloat(pricePerNight.textContent); // Remplacez par votre propre prix par nuit
const stellarRate = 0.05;

// Fonction de calcul du prix
function updatePrice() {
  const arrivalDate = arrivalDateInput.valueAsDate;
  const departureDate = departureDateInput.valueAsDate;

  if (arrivalDate && departureDate) {
    const nights = Math.floor(
      (departureDate - arrivalDate) / (1000 * 60 * 60 * 24)
    );
    const totalPriceValue = nights * nightlyPrice;
    const fees = totalPriceValue * stellarRate;

    pricePerNight.innerText = `${nightlyPrice}€`;
    numNights.innerText = nights;
    totalPrice.innerText = `${totalPriceValue}€`;
    stellarFees.innerText = `${fees}€`;
  } else {
    pricePerNight.innerText = `${nightlyPrice}€`;
    numNights.innerText = "0";
    totalPrice.innerText = "0€";
    stellarFees.innerText = "0€";
  }
}



