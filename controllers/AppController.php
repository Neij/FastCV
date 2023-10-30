<?php

namespace Controllers;

use helpers\SessionManager;

class AppController extends WebController {
    public $pageActive;
    public $isLoggedIn;
    public $view;
    protected $viewData;

    public function __construct($pageActive, $view) {
        parent::__construct("views/layout-app.phtml");

        $this->view = $view;
        $this->pageActive = $pageActive;
        $this->isLoggedIn = SessionManager::get('user_authenticated', false);
        $this->viewData = [];
    }

    public function setPageActive($pageActive) {
        $this->pageActive = $pageActive;
    }

    public function setIsLoggedIn($isLoggedIn) {
        $this->isLoggedIn = $isLoggedIn;
    }

    public function getUsername() {
        $username = SessionManager::get('username');
        return $username ?? null;
    }

    public function hasConsent() {
        // Fonction pour vérifier si l'utilisateur a donné son consentement
        return isset($_COOKIE["consent_cookie"]) && $_COOKIE["consent_cookie"] === "accepted";
    }

    public function isPageActive($pageName) {
        return $this->pageActive === $pageName;
    }

}

?>




