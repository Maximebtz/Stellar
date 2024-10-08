document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si l'écran de chargement a déjà été montré
    const hasBeenShown = localStorage.getItem('loadingScreenShown');

    if (!hasBeenShown) {
        setTimeout(() => {
            const loadingScreen = document.getElementById('loading-screen');
            loadingScreen.style.backgroundColor = '#333333';
            loadingScreen.classList.add('hide');
            setTimeout(() => {
                loadingScreen.style.backgroundColor = 'transparent';
                loadingScreen.style.display = 'none';
            }, 3500);
        }, 3500);

        const svgObject = document.getElementById('my-svg');
        svgObject.classList.remove('hidden');
        svgObject.style.display = 'block';

        
        // Marque l'écran de chargement comme montré
        localStorage.setItem('loadingScreenShown', 'true');
    } else {
        // Cache immédiatement l'écran de chargement si déjà montré
        const loadingScreen = document.getElementById('loading-screen');
        loadingScreen.style.display = 'none';
    }
});
