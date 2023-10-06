<?php

namespace Controllers;

use helpers\SessionManager;

class AppController extends WebController {
    public $pageActive;
    public $isLoggedIn;

    public function __construct($pageActive, $template) {
        parent::__construct($template);

        $this->pageActive = $pageActive;
        $this->isLoggedIn = SessionManager::get('user_authenticated', false);
    }

    public function setPageActive($pageActive) {
        $this->pageActive = $pageActive;
    }

    public function setIsLoggedIn($isLoggedIn) {
        $this->isLoggedIn = $isLoggedIn;
    }

    public function getUsername() {
        // Utiliser SessionManager pour obtenir le nom d'utilisateur depuis la session
        $username = SessionManager::get('username');
        return $username ?? null;
    }

    public function hasConsent() {
        // Fonction pour vérifier si l'utilisateur a donné son consentement
        return isset($_COOKIE["consent_cookie"]) && $_COOKIE["consent_cookie"] === "accepted";
    }

    public function deleteConsentCookie() {
        // Fonction pour supprimer le cookie de consentement
        setcookie("consent_cookie", "", time() - 3600, "/");
    }

    public function isPageActive($pageName) {
        return $this->pageActive === $pageName;
    }
}



