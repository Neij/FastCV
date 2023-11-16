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
                    $user = $controller->loginUser(); 
                    if ($user) {
                        \helpers\SessionManager::set('user_id', $user['id']);
                        header("Location: index.php?route=profile");
                        exit;
                    } else {
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
                $usersAdminModel = new \Models\UsersAdmin($database);
                $controller = new Controllers\ProfileController($usersAdminModel);
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
                SessionManager::destroy();
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
                $adminController->displayPage();
                exit;

            case 'delete-user':
                $usersAdminModel = new \Models\UsersAdmin($database);
                $usersModel = new \Models\Users($database);
                $adminController = new \Controllers\AdminController($usersAdminModel, $usersModel);
                $adminController->deleteUser();
                exit;
                break;

            case 'delete-account':
                $usersAdminModel = new \Models\UsersAdmin($database);
                $profileController = new \Controllers\ProfileController($usersAdminModel);
                $profileController->deleteAccount();
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
