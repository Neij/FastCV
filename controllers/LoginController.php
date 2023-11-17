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
        if (Request::isPost()) {
            $submittedCsrfToken = Request::post("csrfToken");
            $storedCsrfToken = SessionManager::get('csrf_token');

            if (is_array($storedCsrfToken)) {
                $storedCsrfToken = $storedCsrfToken['token'];
            }

            if (!hash_equals($storedCsrfToken, $submittedCsrfToken)) {
                $this->errorMessage = "Échec de la connexion : tentative CSRF détectée.";
                $this->displayLogin();
                return;
            }

            $username = Request::post("username");
            $password = Request::post("password");
            $user = $this->usersModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                
                SessionManager::set('user_authenticated', true);
                SessionManager::set('user_id', $user['id']); 
                SessionManager::set('username', $username);

                SessionManager::set('login_attempts', 0);

                if ($username === 'admin' && $password === 'Admin29@') {
                    header("Location: index.php?route=admin");
                } else {
                    header("Location: index.php?route=profile");
                }
                exit;
            } else {

                $loginAttempts = SessionManager::get('login_attempts', 0);
                $loginAttempts++;
                SessionManager::set('login_attempts', $loginAttempts);

                if ($loginAttempts >= 3) {
                    
                    $this->errorMessage = "Le nom d'utilisateur ou le mot de passe est incorrect.";
                    $this->displayLogin();
                    return;
                } else {
                    
                    $remainingAttempts = 3 - $loginAttempts;
                    $this->errorMessage = "Échec de la connexion : identifiants incorrects. Il vous reste {$remainingAttempts} essais.";
                    $this->displayLogin();
                    return;
                }
            }
        } else {
            
            header("Location: index.php?route=login");
            exit;
        }
    }
}
