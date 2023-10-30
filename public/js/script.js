/********************************************************
 ----------DELETE JOB - BIN EMOJI------------------------
 **********************************************************/

document.addEventListener("DOMContentLoaded", function() {
    var deleteButtons = document.querySelectorAll('.delete-button');

    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var jobId = this.getAttribute('data-jobid');

            // Effectuez une requête AJAX pour supprimer le métier
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete-job.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
                    console.log('Le métier a été supprimé avec succès.');
                }
            };
            xhr.send('jobId=' + jobId);
        });
    });
});

/********************************************************
 ----------DELETE JOB - BIN EMOJI------------------------
 **********************************************************/

 document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById("password");
    const plainPasswordInput = document.getElementById("plain_password");
    const togglePasswordButton = document.getElementById("togglePassword");

    const confirmPasswordInput = document.getElementById("confirm_password");
    const plainConfirmPasswordInput = document.getElementById("plain_confirm_password");
    const toggleConfirmPasswordButton = document.getElementById("toggleConfirmPassword");

    togglePasswordButton.addEventListener("click", function() {
        togglePasswordVisibility(passwordInput, plainPasswordInput, togglePasswordButton);
    });

    toggleConfirmPasswordButton.addEventListener("click", function() {
        togglePasswordVisibility(confirmPasswordInput, plainConfirmPasswordInput, toggleConfirmPasswordButton);
    });

    function togglePasswordVisibility(passwordInput, plainPasswordInput, toggleButton) {
        if (passwordInput.type === "password") {
            // Affiche le mot de passe en clair
            passwordInput.type = "text";
            plainPasswordInput.value = passwordInput.value;
            plainPasswordInput.style.display = "block";
            passwordInput.style.display = "none";
            toggleButton.textContent = "🙈";
        } else {
            // Masque le mot de passe
            passwordInput.type = "password";
            plainPasswordInput.value = "";
            plainPasswordInput.style.display = "none";
            passwordInput.style.display = "block";
            toggleButton.textContent = "👀";
        }
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.getElementById("password");
    const toggleLoginPasswordButton = document.getElementById("toggleLoginPassword");

    toggleLoginPasswordButton.addEventListener("click", function() {
        togglePasswordVisibility(passwordInput, toggleLoginPasswordButton);
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
});
