/****Filter Bar****/
document.addEventListener("DOMContentLoaded", function () {
  function toggleSearchPopup() {
    const popup = document.getElementById("searchPopup");
    if (popup.style.display === "flex") {
      popup.style.display = "none";
    } else {
      popup.style.display = "flex";
    }
  }

  const searchButton = document.getElementById("searchButton");
  const searchPopup = document.getElementById("searchPopup");

  searchButton.addEventListener("click", function (event) {
    event.stopPropagation(); // Éviter que le clic ne se propage et ferme immédiatement la popup
    toggleSearchPopup();
  });

  document.addEventListener("click", function (event) {
    if (
      !searchPopup.contains(event.target) &&
      !searchButton.contains(event.target)
    ) {
      searchPopup.style.display = "none"; // Fermer la popup
    }
  });

  // Fonction pour appliquer les filtres
  function applyFilters() {
    console.log("applyFilters function called");
    const cityTags = Array.from(
      document.querySelectorAll("#cityTags .tag")
    ).map((tag) => tag.innerText);
    const countryTags = Array.from(
      document.querySelectorAll("#countryTags .tag")
    ).map((tag) => tag.innerText);
    const priceRange = document.getElementById("drag");
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    const minPrice = document.getElementById("minPrice").value;
    const maxPrice = document.getElementById("maxPrice").value;
    const url = "/filter";

    console.log(
      `Sending POST request to ${url} with body:`,
      JSON.stringify({
        cities: cityTags,
        countries: countryTags,
        minPrice: minPrice,
        maxPrice: maxPrice,
        startDate: startDate,
        endDate: endDate,
      })
    );

    fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        cities: cityTags,
        countries: countryTags,
        minPrice: minPrice,
        maxPrice: maxPrice,
        startDate: startDate,
        endDate: endDate,
      }),
    })
    
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        console.log("Data received from server:", data);
        console.log(url);

        // Appeler la fonction pour mettre à jour les annonces sur la page
        updateAdverts(data);
      })
      .catch((error) => {
        console.error("Fetch error:", error);
      });
  }

  // Fonction pour mettre à jour les annonces sur la page
  function updateAdverts(data) {
    const grid = document.getElementById("grid");

    // Vider le conteneur actuel
    while (grid.firstChild) {
      grid.removeChild(grid.firstChild);
    }

    // Remplir avec les nouvelles annonces
    data.forEach((advert) => {
      const advertCard = document.createElement("div");
      advertCard.className = "advert-card";

      const advertHTML = `
            <a href="${advert.detailURL}" class="clickable-card">
              <figure>
                <img src="${advert.imgSrc}" alt="${advert.title} image">
              </figure>
              <div class="card-content">
                <h3 class="card-title">${advert.title}</h3>
                <p class="card-text">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                  </svg>
                  ${advert.city}, ${advert.country}
                </p>
                <p class="card-text">
                  <span class="price-card">${advert.price}€</span>/nuit
                </p>
              </div>
            </a>`;

      advertCard.innerHTML = advertHTML;
      grid.appendChild(advertCard);
    });
  }

  function addTag(inputId, tagContainerId) {
    const input = document.getElementById(inputId);
    const tagContainer = document.getElementById(tagContainerId);

    const tag = document.createElement("span");
    tag.className = "tag";
    tag.innerText = input.value;

    const closeBtn = document.createElement("span");
    closeBtn.className = "tag-close";
    closeBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor"
                                class="bi bi-x" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 
                                            8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>`;
    closeBtn.onclick = function () {
      tagContainer.removeChild(tag);
    };

    tag.appendChild(closeBtn);
    tagContainer.appendChild(tag);

    input.value = "";
  }

  document
    .getElementById("apply-filter-btn")
    .addEventListener("click", function () {
      applyFilters();
      document.getElementById("searchPopup").style.display = "none";
    });

  // Ajouter une étiquette lorsque l'utilisateur appuie sur Entrée
  document
    .getElementById("cityInput")
    .addEventListener("keyup", function (event) {
      if (event.key === "Enter") {
        addTag("cityInput", "cityTags");
      }
    });

  document
    .getElementById("countryInput")
    .addEventListener("keyup", function (event) {
      if (event.key === "Enter") {
        addTag("countryInput", "countryTags");
      }
    });

  $("#price-range-submit").hide();

  $("#minPrice,#maxPrice").on("change", function () {
    $("#price-range-submit").show();
    var min_price_range = parseInt($("#minPrice").val());
    var max_price_range = parseInt($("#maxPrice").val());
    if (min_price_range > max_price_range) {
      $("#maxPrice").val(min_price_range);
    }
    $("#rangePrice").slider({
      values: [min_price_range, max_price_range],
    });
  });

  $("#minPrice,#maxPrice").on("paste keyup", function () {
    $("#price-range-submit").show();
    var min_price_range = parseInt($("#minPrice").val());
    var max_price_range = parseInt($("#maxPrice").val());
    if (min_price_range == max_price_range) {
      max_price_range = min_price_range + 100;
      $("#minPrice").val(min_price_range);
      $("#maxPrice").val(max_price_range);
    }
    $("#rangePrice").slider({
      values: [min_price_range, max_price_range],
    });
  });

  $("#rangePrice").slider({
    range: true,
    orientation: "horizontal",
    min: 0,
    max: 10000,
    values: [0, 10000],
    step: 100,
    slide: function (event, ui) {
      if (ui.values[0] == ui.values[1]) {
        return false;
      }
      $("#minPrice").val(ui.values[0]);
      $("#maxPrice").val(ui.values[1]);
      console.log($("#minPrice").val(), $("#maxPrice").val());
    },
  });

  $("#minPrice").val($("#rangePrice").slider("values", 0));
  $("#maxPrice").val($("#rangePrice").slider("values", 1));

  $("#rangePrice,#price-range-submit").click(function () {
    var minPrice = $("#minPrice").val();
    var maxPrice = $("#maxPrice").val();
    $("#searchResults").text(
      "Here List of products will be shown which are cost between " +
        minPrice +
        " " +
        "and" +
        " " +
        maxPrice +
        "."
    );
  });
});
