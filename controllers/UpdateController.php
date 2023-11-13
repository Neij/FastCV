<?php

namespace Controllers;

use Models\Users;
use helpers\Request;
use helpers\SessionManager;

class UpdateController extends FormController
{
    private $usersModel;

    public function __construct(Users $usersModel)
    {
        $this->usersModel = $usersModel;
        SessionManager::startSession();
    }



    
}
