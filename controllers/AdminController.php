<?php

namespace Controllers;

use Models\UsersAdmin;
use Helpers\SessionManager;

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
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

            if (!empty($user_id)) {
                $success = $this->usersAdminModel->deleteUserWithJobs($user_id);

                if ($success) {
                    header("Location: index.php?route=admin");
                    exit;
                }
            }
        }
    }
}









