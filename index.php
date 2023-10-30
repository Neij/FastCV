<?php

session_start();

require('config/config.php');

require_once 'models/Database.php';

use helpers\Request;

spl_autoload_register(function ($class) {
    require_once lcfirst(str_replace('\\', '/', $class)) . '.php';
});

try {

    $database = new \Models\Database();

    if (Request::get('route')) {

        switch (Request::get('route')) {
            case 'home':
                $controller = new Controllers\HomeController();
                break;

            case 'about':
                $controller = new Controllers\AboutController();
                break;

            case 'login':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\LoginController($usersModel);
                if (Request::isPost()) {
                    $user = $controller->loginUser(); // Appel de la méthode loginUser() qui retourne l'utilisateur s'il est authentifié
                    if ($user) {
                        // L'utilisateur est connecté avec succès, stockez l'ID de l'utilisateur dans la session
                        \helpers\SessionManager::set('user_id', $user['id']);
                        // Redirigez l'utilisateur vers la page de profil
                        header("Location: index.php?route=profile");
                        exit;
                    } else {
                        // Identifiants incorrects, affichez un message d'erreur
                        \helpers\SessionManager::set('error_message', 'Identifiants incorrects. Veuillez réessayer.');
                        header("Location: index.php?route=login");
                        exit;
                    }
                } else {
                    $controller->displayLogin();
                }
                break;

            case 'register':
                $registerModel = new \Models\Register($database);
                $controller = new Controllers\RegisterController($registerModel);
                if (Request::isPost()) {
                    $controller->registerUser();
                    exit;
                } else {
                    $controller->displayRegister();
                }
                break;

            case 'profile':
                $controller = new Controllers\ProfileController();
                break;

                // ...
            case 'create-cv':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->handleRequest();
                exit;
                break;

            case 'logout':
                session_destroy();
                header("Location: index.php?route=home");
                exit;

            case 'politique':
                $controller = new Controllers\PolitiqueController();
                break;

            case 'good-cv':
                $controller = new Controllers\GoodCVController();
                break;

            default:
                header('Location: index.php?route=home');
                exit;
        }

        $controller->displayPage();
    } else {
        header('Location: index.php?route=home');
        exit;
    }
} catch (Exception $e) {
    // Gestion des erreurs : Afficher un message d'erreur ou enregistrer les erreurs dans un journal
    echo 'Une erreur s\'est produite : ' . $e->getMessage();
}
