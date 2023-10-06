<?php

namespace Controllers;

use Models\Register;
use helpers\Request;
use helpers\SessionManager;

class RegisterController extends FormController
{
    private $registerModel;

    public function __construct(Register $registerModel)
    {
        $this->registerModel = $registerModel;
        parent::__construct("register", "register.phtml");
    }

    public function displayRegister()
    {
        // Génération d'un nouveau jeton CSRF à chaque chargement de la page d'inscription
        $csrfToken = bin2hex(random_bytes(32));
        SessionManager::set('csrf_token', $csrfToken);

        $this->displayPage();
    }

    public function registerUser()
    {
        if (Request::isPost()) {
            $submittedCsrfToken = Request::post("csrfToken");
            $storedCsrfToken = SessionManager::get('csrf_token');

            // Validation sécurisée du jeton CSRF
            if (!hash_equals($storedCsrfToken, $submittedCsrfToken)) {
                $this->displayRegister("Échec de l'inscription : tentative CSRF détectée.");
                return;
            }

            // Assainissement des données d'entrée
            $username = htmlspecialchars(Request::post("username"));
            $password = htmlspecialchars(Request::post("password"));
            $email = htmlspecialchars(Request::post("email"));
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Validation du nom d'utilisateur
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
                $this->displayRegister("Échec de l'inscription : nom d'utilisateur non valide. Utilisez uniquement des lettres, des chiffres, des tirets et des underscores.");
                return;
            }

            // Validation du mot de passe
            if (strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])/', $password)) {
                $this->displayRegister("Échec de l'inscription : le mot de passe doit comporter au moins 8 caractères et inclure des lettres majuscules, des lettres minuscules, des chiffres et des caractères spéciaux.");
                return;
            }

            // Validation de l'adresse e-mail
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->displayRegister("Échec de l'inscription : adresse e-mail non valide.");
                return;
            }

            try {
                $result = $this->registerModel->createUser($username, $hashedPassword, $email);

                if ($result) {
                    // Redirection vers la page de connexion avec un message de succès
                    SessionManager::set('registration_success', true);
                    header("Location: index.php?route=login");
                    exit;
                } else {
                    $this->displayRegister("Erreur d'inscription : impossible de créer l'utilisateur.");
                }
            } catch (PDOException $e) {
                // Gérer l'exception et afficher un message générique
                error_log("Erreur PDO lors de l'inscription : " . $e->getMessage());
                $this->displayRegister("Erreur d'inscription : veuillez réessayer plus tard.");
            }
        }
    }
}
