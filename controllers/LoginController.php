<?php

namespace Controllers;

use Models\Users;
use helpers\Request;
use helpers\SessionManager;

class LoginController extends FormController
{
    private $usersModel;
    public $errorMessage;

    public function __construct(Users $usersModel)
    {
        parent::__construct("login", "views/login.phtml");
        $this->usersModel = $usersModel;
    }

    public function displayLogin()
    {
        $csrfToken = $this->csrfToken;
        SessionManager::set('csrf_token', $csrfToken);

        $this->displayPage();
    }

    public function getRegistrationSuccess()
    {
        return SessionManager::get('registration_success', false);
    }

    public function loginUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submittedCsrfToken = Request::post("csrfToken");
            $storedCsrfToken = SessionManager::get('csrf_token');

            // Assurez-vous que $storedCsrfToken est une chaîne de caractères
            if (is_array($storedCsrfToken)) {
                $storedCsrfToken = $storedCsrfToken['token'];
            }
            // Validation sécurisée du jeton CSRF
            if (!hash_equals($storedCsrfToken, $submittedCsrfToken)) {
                $this->errorMessage = "Échec de la connexion : tentative CSRF détectée.";
                $this->displayLogin();
                return;
            }

            $username = Request::post("username");
            $password = Request::post("password");
            $user = $this->usersModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                SessionManager::set('user_authenticated', true);
                SessionManager::set('user_id', $user['id']); // Stockez l'ID de l'utilisateur dans la session
                SessionManager::set('username', $username);

                $jobs = []; // Récupérez les métiers de l'utilisateur depuis la base de données
                $educations = []; // Récupérez les formations de l'utilisateur depuis la base de données

                // Stockez les métiers et les formations dans la session
                SessionManager::set('jobs', $jobs);
                SessionManager::set('educations', $educations);

                // Réinitialisation du compteur de tentatives de connexion
                SessionManager::set('login_attempts', 0);
                header("Location: index.php?route=profile");
                exit;
            } else {
                // Le mot de passe ne correspond pas ou l'utilisateur n'existe pas
                // Échec de la connexion : gestion des tentatives
                $loginAttempts = SessionManager::get('login_attempts', 0);
                $loginAttempts++;
                SessionManager::set('login_attempts', $loginAttempts);

                if ($loginAttempts >= 3) {
                    // Compte temporairement bloqué
                    $this->errorMessage = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                    $this->displayLogin();
                    return;
                } else {
                    // Affichage d'un message d'erreur avec le nombre de tentatives restantes
                    $remainingAttempts = 3 - $loginAttempts;
                    $this->errorMessage = "Échec de la connexion : identifiants incorrects. Il vous reste {$remainingAttempts} essais.";
                    $this->displayLogin();
                    return;
                }
            }
            
        } else {
            // Redirection vers la page de connexion si le formulaire n'a pas été soumis
            header("Location: index.php?route=login");
            exit;
        }
    }
}

