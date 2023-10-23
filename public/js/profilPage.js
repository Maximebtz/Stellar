document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("showAdverts").addEventListener("click", function() {
        toggleVisibility("advertsSection");
    });

    document.getElementById("showReservedAdverts").addEventListener("click", function() {
        toggleVisibility("reservedAdvertsSection");
    });

    document.getElementById("showReservations").addEventListener("click", function() {
        toggleVisibility("reservationsSection");
    });
});

function toggleVisibility(id) {
    const allSections = ["advertsSection", "reservedAdvertsSection", "reservationsSection"];
    
    allSections.forEach(function(sectionId) {
        const elem = document.getElementById(sectionId);
        elem.style.display = 'none';
    });
    
    const elem = document.getElementById(id);
    elem.style.display = 'block';
}
