
// Fonction pour vérifier si l'utilisateur a déjà donné son consentement
function checkConsentCookie() {
    const consentCookie = getCookie("consent_cookie");
    const popup = document.getElementById("cookie-consent-popup");

    if (consentCookie === "accepted") {
        // Si le consentement a été donné, masquez le pop-up
        popup.classList.add("hidden-popup");
    } else if (consentCookie !== "refused") {
        // Si le consentement n'a pas été refusé (c'est-à-dire qu'il est indéfini ou autre), affichez le pop-up
        popup.classList.remove("hidden-popup");
    }
}

  // Fonction pour obtenir la valeur d'un cookie
  function getCookie(name) {
    const cookieName = name + "=";
    const cookieArray = document.cookie.split(";");

    for (let i = 0; i < cookieArray.length; i++) {
      let cookie = cookieArray[i];
      while (cookie.charAt(0) === " ") {
        cookie = cookie.substring(1);
      }
      if (cookie.indexOf(cookieName) === 0) {
        return cookie.substring(cookieName.length, cookie.length);
      }
    }
    return "";
  }

  // Fonction pour cacher le pop-up et définir le cookie de consentement
  function hideCookiePopup() {
    document.getElementById("cookie-consent-popup").style.display = "none";

    // Définir le cookie de consentement à "accepted" ou "refused" en fonction du bouton cliqué
    const consentCookieValue =
      this.id === "accept-cookies" ? "accepted" : "refused";
    document.cookie =
      "consent_cookie=" +
      consentCookieValue +
      "; expires=Sun, 31 Dec 2034 23:59:59 UTC; path=/";
  }

  // Écoutez le clic sur le bouton "Accepter"
  document
    .getElementById("accept-cookies")
    .addEventListener("click", hideCookiePopup);

  // Écoutez le clic sur le bouton "Refuser"
  document
    .getElementById("refuse-cookies")
    .addEventListener("click", hideCookiePopup);

  // Vérifiez le consentement à chaque chargement de page
  checkConsentCookie();
