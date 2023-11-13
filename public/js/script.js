function showEditForm(formId) {
  document.getElementById(formId).style.display = 'block';
}

function hideEditForm(formId) {
  document.getElementById(formId).style.display = 'none';
}

/********************************************************
 ----------DELETE JOB - BIN EMOJI------------------------
 **********************************************************/

document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const togglePasswordButton = document.getElementById("togglePassword");

  const confirmPasswordInput = document.getElementById("confirm_password");
  const toggleConfirmPasswordButton = document.getElementById(
    "toggleConfirmPassword"
  );

  togglePasswordButton.addEventListener("click", function () {
    togglePasswordVisibility(
      passwordInput,
      togglePasswordButton
    );
  });

  toggleConfirmPasswordButton.addEventListener("click", function () {
    togglePasswordVisibility(
      confirmPasswordInput,
      toggleConfirmPasswordButton
    );
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.getElementById("password");
  const toggleLoginPasswordButton = document.getElementById(
    "toggleLoginPassword"
  );

  toggleLoginPasswordButton.addEventListener("click", function () {
    togglePasswordVisibility(passwordInput, toggleLoginPasswordButton);
  });
});

function togglePasswordVisibility(passwordInput, toggleButton) {
  if (passwordInput.type === "password") {
    // Affiche le mot de passe en clair
    passwordInput.type = "text";
    toggleButton.textContent = "🙈";
  } else {
    // Masque le mot de passe
    passwordInput.type = "password";
    toggleButton.textContent = "👀";
  }
}
