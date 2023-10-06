<?php

namespace Controllers;

use Models\Users;
use helpers\Request;
use helpers\SessionManager;

class LoginController extends FormController {
    private $usersModel;
    public $errorMessage;

    public function __construct(Users $usersModel) {
        parent::__construct("login", "views/login.phtml");
        $this->usersModel = $usersModel;
    }

    public function displayLogin() {
        // Génération d'un nouveau jeton CSRF à chaque chargement de la page de connexion
        $csrfToken = bin2hex(random_bytes(32)); 
        SessionManager::set('csrf_token', $csrfToken);

        $this->displayPage();
    }

    public function getRegistrationSuccess() {
        return SessionManager::get('registration_success', false);
    }

    public function getCsrfToken() {
        return SessionManager::get('csrf_token', "bouh");
    }

    public function loginUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submittedCsrfToken = Request::post("csrfToken");
            $storedCsrfToken = SessionManager::get('csrf_token');
            
            // Validation sécurisée du jeton CSRF
            if (!hash_equals($storedCsrfToken, $submittedCsrfToken)) {
                // Jeton CSRF invalide
                // $ip = '';

                //     if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                //         $ip = $_SERVER['HTTP_CLIENT_IP'];
                //     } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                //         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                //     } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                //         $ip = $_SERVER['REMOTE_ADDR'];
                //     }
                //     error_log(date('d-m-Y H:i:s') . " Erreur: Tentative Faille CSRF détectée. IP: ". $ip . PHP_EOL, 3, "errors.log");
                    
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
                SessionManager::set('username', $username);
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
                    $this->errorMessage = "Votre compte est temporairement bloqué en raison de tentatives de connexion infructueuses.";
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








