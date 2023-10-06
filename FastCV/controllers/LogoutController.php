<?php

namespace Controllers;

class LogoutController extends AppController {

    public function displayPage() {
     
        session_destroy();
        header("Location: index.php?route=home");
        exit;
    }
}

