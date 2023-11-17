<?php

namespace Controllers;

use Models\UsersAdmin;
use Helpers\SessionManager;
use Helpers\Request;

class AdminController extends WebController
{
    private $usersAdminModel;
    public $usersWithDetails;

    public function __construct(UsersAdmin $usersAdminModel)
    {
        $username = SessionManager::get('username');
        if ($username !== 'admin') {
            header("Location: index.php?route=login");
            exit;
        }
        
        parent::__construct("views/admin.phtml");

        $this->usersAdminModel = $usersAdminModel;
        $this->usersWithDetails = $this->usersAdminModel->getAllUsersWithDetails();
    }

    public function deleteUser()
    {
        if (Request::get('user_id')) {
            $user_id = Request::get('user_id');

            $success = $this->usersAdminModel->deleteUserWithAllInfos($user_id);

            if ($success) {
                header("Location: index.php?route=admin");
                exit;
            }
        }
    }
}









