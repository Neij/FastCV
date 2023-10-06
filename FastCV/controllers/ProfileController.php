<?php

namespace Controllers;

class ProfileController extends AppController {
    public function __construct() {
        parent::__construct("profile", "profile.phtml");
    }
}


