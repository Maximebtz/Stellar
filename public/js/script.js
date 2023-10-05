document.addEventListener("DOMContentLoaded", function () {
    
tarteaucitron.init({
    privacyUrl: '/politique-de-confidentialite', // Lien vers ta page de politique de confidentialité
    hashtag: '#cookies',
    cookieName: 'monsite_cookies',
    orientation: 'bottom', // Position du panneau de consentement
    showAlertSmall: false,
    cookieslist: true,
    adblocker: false,
    DenyAllCta: true,
    AcceptAllCta: true,
    highPrivacy: false,
    handleBrowserDNTRequest: false,
    removeCredit: false,
    moreInfoLink: true,
    useExternalCss: false,
    mandatory: false,
    language: 'fr',
    // accepted : function () { // Cette fonction est appelée lorsque l'utilisateur accepte les cookies
    // Intégrer ici les services de cookies
    // },

});


    // Vérifie si les cookies sont acceptés
    if (tarteaucitron.userInterface.respondAll == "tarteaucitronHidden" || tarteaucitron.userInterface.respondAll == "tarteaucitronOpen") {
        // Les cookies sont acceptés, tu peux activer des fonctionnalités basées sur les cookies ici.
        console.log("Cookies acceptés, fonctionnalités basées sur les cookies activées.");
    } else {
        // Les cookies sont refusés, tu peux afficher un message d'information pour les utilisateurs, mais ne bloque pas les fonctionnalités essentielles.
        console.log("Cookies refusés, ajustement du comportement sans cookies.");
        
        // Exemple : Affiche un message d'information pour les utilisateurs
        const cookieMessage = document.createElement("div");
        cookieMessage.textContent = "Les cookies sont refusés, mais vous pouvez toujours utiliser le site.";
        document.body.appendChild(cookieMessage);
        
        // Tu n'as pas besoin de désactiver des fonctionnalités majeures du site en cas de refus de cookies.
    }
});