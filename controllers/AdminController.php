<?php

namespace Controllers;

use Models\UsersAdmin;
use Helpers\SessionManager;

class AdminController extends WebController
{
    private $usersAdminModel;
    private $viewData = [];

    public function __construct(UsersAdmin $usersAdminModel)
    {
        parent::__construct("views/admin.phtml");
        $this->usersAdminModel = $usersAdminModel;
    }

    public function checkAdminAuthorization()
    {
        $username = SessionManager::get('username');
        if ($username !== 'admin') {
            header("Location: index.php?route=login");
            exit;
        }
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

    public function handleRequest()
    {
        $this->checkAdminAuthorization();
        $usersWithDetails = $this->usersAdminModel->getAllUsersWithDetails();
        $this->viewData['usersWithDetails'] = $usersWithDetails;
        

        include 'views/layout-web.phtml';
    }
}









