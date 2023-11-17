<?php

namespace Controllers;

use Models\UsersAdmin;

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
        $userId = \helpers\SessionManager::get('user_id');

        $success = $this->usersAdminModel->deleteUserWithAllInfos($userId);

        if ($success) {

            \helpers\SessionManager::destroy();

            header("Location: index.php");
            exit;
        } else {
            echo "La suppression du compte a échoué.";
        }
    }
}
