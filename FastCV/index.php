<?php

// Démarrage de la session PHP
session_start();

// Inclusion du fichier de configuration
require('config/config.php');

// Inclusion de la classe Database
require_once 'models/Database.php';

// Utilisation des classes SessionManager et Request depuis le namespace helpers
use helpers\SessionManager;
use helpers\Request;

// Autoload : Cette fonction est utilisée pour inclure automatiquement les classes lorsqu'elles sont nécessaires
spl_autoload_register(function ($class) {
    // Transforme le namespace en chemin de fichier et inclut le fichier correspondant
    require_once lcfirst(str_replace('\\', '/', $class)) . '.php';
});

try {
    // Création d'une instance de la classe Database pour la gestion de la base de données
    $database = new \Models\Database();

    // Router : Gestion des différentes routes de l'application
    if (Request::get('route')) {

        switch (Request::get('route')) {
            case 'home':
                $controller = new Controllers\HomeController();
                break;

            case 'about':
                $controller = new Controllers\AboutController();
                break;

            case 'login':
                // Création d'une instance du modèle Users pour la gestion des utilisateurs
                $usersModel = new \Models\Users($database);
                $controller = new Controllers\LoginController($usersModel);
                if (Request::isPost()) {
                    $controller->loginUser();
                } else {
                    $controller->displayLogin();
                }
                break;

            case 'register':
                // Création d'une instance du modèle Register pour l'inscription des utilisateurs
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
                $controller = new Controllers\CreateCVController();
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
                // Redirection vers la page d'accueil si la route n'est pas valide
                header('Location: index.php?route=home');
                exit;
        }

        $controller->displayPage();
    } else {
        // Redirection vers la page d'accueil par défaut si aucune route n'est spécifiée
        header('Location: index.php?route=home');
        exit;
    }
} catch (Exception $e) {
    // Gestion des erreurs : Afficher un message d'erreur ou enregistrer les erreurs dans un journal
    echo 'Une erreur s\'est produite : ' . $e->getMessage();
}


