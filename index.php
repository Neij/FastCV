<?php

require('config/config.php');

require_once 'models/Database.php';

use helpers\Request;
use helpers\SessionManager;


spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

SessionManager::startSession();

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

            case 'create-cv':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->displayCreateCV();
                exit;
                break;

            case 'create-job':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->createJob();
                exit;
                break;

            case 'create-education':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->createEducation();
                exit;
                break;

            case 'create-personal-info':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->createPersonalInfo();
                exit;
                break;

            case 'delete-job':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->deleteJob();
                exit;
                break;

            case 'delete-education':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->deleteEducation();
                exit;
                break;

            case 'delete-personal-info':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->deletePersonalInfo();
                exit;
                break;

            case 'update-job':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->updateJob();
                exit;
                break;

            case 'update-education':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->updateEducation();
                exit;
                break;

            case 'update-personal-info':
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\CreateCVController($usersModel);
                $controller->updatePersonalInfo();
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

            case 'admin':
                $usersAdminModel = new \Models\UsersAdmin($database);
                $usersModel = new \Models\Users($database);
                $adminController = new \Controllers\AdminController($usersAdminModel);
                $adminController->handleRequest();
                exit;

            case 'delete-user':
                $usersAdminModel = new \Models\UsersAdmin($database); // Initialisez le modèle ici
                $usersModel = new \Models\Users($database); // Initialisez le modèle ici
                $adminController = new \Controllers\AdminController($usersAdminModel, $usersModel); // Initialisez le contrôleur ici
                $adminController->deleteUser();
                exit;
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
    echo 'Une erreur s\'est produite : ' . $e->getMessage();
}
