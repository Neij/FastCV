/********************************************************
 ------------------------CAROUSEL---------------------------
 **********************************************************/

 $(document).ready(function(){
  $('.your-carousel-container').slick({
    autoplay: true,
    autoplaySpeed: 3000,
    dots: true,
    slidesToShow: 1, 
    slidesToScroll: 1,
  });
});


/********************************************************
 ----------AFFICHER FORMULAIRE UPDATE--------------------
 **********************************************************/


function showEditForm(formId) {
  const form = document.getElementById(formId);
  if (form) {
    form.style.display = 'block';
  }
}

function hideEditForm(formId) {
  const form = document.getElementById(formId);
  if (form) {
    form.style.display = 'none';
  }
}

/********************************************************
 --------------AFFCIHER/CACHER MDP------------------------
 **********************************************************/

document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const togglePasswordButton = document.getElementById("togglePassword");

  const confirmPasswordInput = document.getElementById("confirm_password");
  const toggleConfirmPasswordButton = document.getElementById(
    "toggleConfirmPassword"
  );

  if (togglePasswordButton) {
    togglePasswordButton.addEventListener("click", function () {
      togglePasswordVisibility(
        passwordInput,
        togglePasswordButton
      );
    });
  }

  if (toggleConfirmPasswordButton) {
    toggleConfirmPasswordButton.addEventListener("click", function () {
      togglePasswordVisibility(
        confirmPasswordInput,
        toggleConfirmPasswordButton
      );
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const toggleLoginPasswordButton = document.getElementById(
    "toggleLoginPassword"
  );

  if (toggleLoginPasswordButton) {
    toggleLoginPasswordButton.addEventListener("click", function () {
      togglePasswordVisibility(passwordInput, toggleLoginPasswordButton);
    });
  }
});

function togglePasswordVisibility(passwordInput, toggleButton) {
  if (passwordInput && toggleButton) {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleButton.textContent = "🙈";
    } else {
      passwordInput.type = "password";
      toggleButton.textContent = "👀";
    }
  }
}

/********************************************************
 ----------------------COOKIES------------------------
 **********************************************************/


function checkConsentCookie() {
  const consentCookie = getCookie("consent_cookie");
  const popup = document.getElementById("cookie-consent-popup");

  if (consentCookie === "accepted") {
      popup.classList.add("hidden-popup");
  } else if (consentCookie !== "refused") {
      popup.classList.remove("hidden-popup");
  }
}

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

function hideCookiePopup() {
  document.getElementById("cookie-consent-popup").style.display = "none";

  const consentCookieValue =
    this.id === "accept-cookies" ? "accepted" : "refused";
  document.cookie =
    "consent_cookie=" +
    consentCookieValue +
    "; expires=Sun, 31 Dec 2034 23:59:59 UTC; path=/";
}

document
  .getElementById("accept-cookies")
  .addEventListener("click", hideCookiePopup);

document
  .getElementById("refuse-cookies")
  .addEventListener("click", hideCookiePopup);

checkConsentCookie();



