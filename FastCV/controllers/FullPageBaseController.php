<?php

// namespace Controllers;

// class FullPageBaseController extends BaseController {
//     public function __construct() {
//         parent::__construct("about", "views/about.phtml"); 
//     }
// }

// class WebController {
//     public $template;

//     public function __construct($template) {
//         $this->template = $template;
//     }

//     public function displayPage() {
//         require_once $this->template;
//     }
// }

// class AppController extends WebController {
//     public $pageActive;
//     public $isLoggedIn;
//     public $csrfToken;

//     public function __construct($pageActive, $template) {
//         parent::__construct($template);
//         $this->pageActive = $pageActive;
//         $this->isLoggedIn = SessionManager::get('user_authenticated', false); 
//     }

//     public function setCsrfToken($csrfToken) {
//         $this->csrfToken = $csrfToken;
//     }

//     public function getUsername() {
//         // Utiliser SessionManager pour obtenir le nom d'utilisateur depuis la session
//         $username = SessionManager::get('username');
//         return $username ?? null;
//     }

//     public function displayPage() {
//         // Inclure le layout
//         require_once 'views/layout.phtml';
//     }

//     // Fonction pour vérifier si l'utilisateur a donné son consentement
//     public function hasConsent() {
//         return isset($_COOKIE["consent_cookie"]) && $_COOKIE["consent_cookie"] === "accepted";
//     }

//     // Fonction pour supprimer le cookie de consentement
//     public function deleteConsentCookie() {
//         setcookie("consent_cookie", "", time() - 3600, "/");
//     }

//     public function isPageActive($pageName) {
//         return $this->pageActive === $pageName;
//     }
// }


// class AboutController extends AppController {
//     public function __construct() {
//         parent::__construct("about", "views/about.phtml"); 
//     }
// }

// class PolitiqueController extends WebController {
//     public function __construct() {
//         parent::__construct("views/politique.phtml"); 
//     }
// }


// $aboutController = new AboutController();
// $aboutController.displayPage()

// $politiqueController = new PolitiqueController();
// $politiqueController.displayPage()






