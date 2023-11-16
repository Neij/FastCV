<?php

// ProfileController.php

namespace Controllers;

use Models\UsersAdmin;
use Helpers\SessionManager;

class ProfileController extends AppController
{
    private $usersAdminModel;

    public function __construct(UsersAdmin $usersAdminModel)
    {
        parent::__construct("profile", "profile.phtml");
        $this->usersAdminModel = $usersAdminModel;
    }

    public function deleteAccount()
    {
        // Récupérez l'ID de l'utilisateur actuel
        $userId = \helpers\SessionManager::get('user_id');

        // Supprimez l'utilisateur et toutes ses informations associées
        $success = $this->usersAdminModel->deleteUserWithAllInfos($userId);

        if ($success) {

            // Déconnectez l'utilisateur
            \helpers\SessionManager::destroy();

            // Redirigez l'utilisateur vers la page d'accueil
            header("Location: index.php");
            exit;
        } else {
            // Gestion de l'échec de la suppression (par exemple, affichage d'un message d'erreur)
            echo "La suppression du compte a échoué.";
        }
    }

    // ... (autres méthodes)
}
