const daysTag = document.querySelector(".days");
const currentDate = document.querySelector(".current-date");
const prevNextIcon = document.querySelectorAll(".icons span");

const arrivalDateInput = document.getElementById("arrivalDate");
const departureDateInput = document.getElementById("departureDate");
const pricePerNight = document.getElementById("pricePerNight");
const totalPrice = document.getElementById("totalPrice");
const stellarFees = document.getElementById("stellarFees");

// Prix par nuit et taux de frais Stellar (5%)
const nightlyPrice = 100; // Remplacez par votre propre prix par nuit
const stellarRate = 0.05;

// Storing full name of all months in array
const months = [
  "January", "February", "March", "April", "May", "June", "July",
  "August", "September", "October", "November", "December"
];

const renderCalendar = () => {
  let date = new Date();
  let currYear = date.getFullYear();
  let currMonth = date.getMonth();

  let firstDayOfMonth = new Date(currYear, currMonth, 1).getDay();
  let lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate();
  let lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay();
  let lastDateOfLastMonth = new Date(currYear, currMonth, 0).getDate();
  let liTag = "";

  for (let i = firstDayOfMonth; i > 0; i--) {
    liTag += `<li class="inactive">${lastDateOfLastMonth - i + 1}</li>`;
  }

  for (let i = 1; i <= lastDateOfMonth; i++) {
    let isToday = i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear() ? "active" : "";
    liTag += `<li class="${isToday}">${i}</li>`;
  }

  for (let i = lastDayOfMonth; i < 6; i++) {
    liTag += `<li class="inactive">${i - lastDayOfMonth + 1}</li>`;
  }
  currentDate.innerText = `${months[currMonth]} ${currYear}`;
  daysTag.innerHTML = liTag;
};
renderCalendar();

prevNextIcon.forEach(icon => {
  icon.addEventListener("click", () => {
    currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

    if (currMonth < 0 || currMonth > 11) {
      date = new Date(currYear, currMonth, new Date().getDate());
      currYear = date.getFullYear();
      currMonth = date.getMonth();
    } else {
      date = new Date();
    }
    renderCalendar();
    updatePrice();
  });
});

arrivalDateInput.addEventListener("change", () => {
  updatePrice();
});

departureDateInput.addEventListener("change", () => {
  updatePrice();
});

function updatePrice() {
    const arrivalDate = new Date(arrivalDateInput.value);
    const departureDate = new Date(departureDateInput.value);
    const nights = Math.floor((departureDate - arrivalDate) / (1000 * 60 * 60 * 24));

    const nightlyPrice = 100; // Remplacez par votre propre prix par nuit
    const totalPriceValue = nights * nightlyPrice;
    const fees = totalPriceValue * stellarRate;

    pricePerNight.innerText = nightlyPrice + '€';
    numNights.innerText = nights;
    totalPrice.innerText = totalPriceValue + '€';
    stellarFees.innerText = fees + '€';
}
