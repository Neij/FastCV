<?php

namespace Controllers;

use Models\Register;
use helpers\Request;
use helpers\SessionManager;

class RegisterController extends FormController
{
    private $registerModel;
    public $errorMessage;

    public function __construct(Register $registerModel)
    {
        $this->registerModel = $registerModel;
        parent::__construct("register", "register.phtml");
    }

    public function displayRegister()
    {
        $csrfToken = bin2hex(random_bytes(32));
        SessionManager::set('csrf_token', ['token' => $csrfToken, 'timestamp' => time()]);

        $this->displayPage();
    }

    public function registerUser()
    {
        if (Request::isPost()) {
            $submittedCsrfToken = Request::post("csrfToken");
            $storedCsrfTokenData = SessionManager::get('csrf_token');

            if (is_array($storedCsrfTokenData) && isset($storedCsrfTokenData['token']) && isset($storedCsrfTokenData['timestamp'])) {
                $storedCsrfToken = $storedCsrfTokenData['token'];
                $storedTimestamp = $storedCsrfTokenData['timestamp'];

                if (hash_equals($storedCsrfToken, $submittedCsrfToken) && (time() - $storedTimestamp) <= 3600) {

                    $username = htmlspecialchars(Request::post("username"));
                    $password = htmlspecialchars(Request::post("password"));
                    $email = htmlspecialchars(Request::post("email"));
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    if (!preg_match('/^[a-zA-ZÀ-ÖØ-öø-ÿ0-9_-]+$/', $username)) {
                        $this->errorMessage = "Échec de l'inscription : nom d'utilisateur non valide. Utilisez uniquement des lettres, des chiffres, des tirets et des underscores.";
                        $this->displayRegister();
                        return;
                    }

                    if ($this->registerModel->isUsernameTaken($username)) {
                        $this->errorMessage = "Échec de l'inscription : le nom d'utilisateur est déjà pris. Veuillez en choisir un autre.";
                        $this->displayRegister();
                        return;
                    }

                    if (strlen($password) < 8 || !preg_match('/^(?=.*[a-zéèêàâ])(?=.*[A-ZÉÈÊÀÂ])(?=.*\d)(?=.*[@#$%^&+=!])/', $password)) {
                        $this->errorMessage = "Échec de l'inscription : le mot de passe doit comporter au moins 8 caractères et inclure des lettres majuscules, des lettres minuscules, des chiffres et des caractères spéciaux.";
                        $this->displayRegister();
                        return;
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $this->errorMessage = "Échec de l'inscription : adresse e-mail non valide.";
                        $this->displayRegister();
                        return;
                    }

                    try {
                        $result = $this->registerModel->createUser($username, $hashedPassword, $email);

                        if ($result) {
                            SessionManager::set('registration_success', true);
                            header("Location: index.php?route=login");
                            exit;
                        } else {
                            $this->errorMessage = "Erreur d'inscription : impossible de créer l'utilisateur.";
                            $this->displayRegister();
                        }
                    } catch (PDOException $e) {
                        error_log("Erreur PDO lors de l'inscription : " . $e->getMessage());
                        $this->errorMessage = "Erreur d'inscription : veuillez réessayer plus tard.";
                        $this->displayRegister();
                    }
                } else {
                    $this->errorMessage = "Échec de l'inscription : tentative CSRF détectée.";
                    $this->displayRegister();
                }
            } else {
                $this->errorMessage = "Échec de l'inscription : jeton CSRF non valide.";
                $this->displayRegister();
            }
        }
    }
}

