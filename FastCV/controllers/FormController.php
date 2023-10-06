<?php

namespace Controllers;

class FormController extends AppController {
    public $csrfToken;

    public function __construct($pageActive, $template) {
        parent::__construct($pageActive, $template);

        $this->csrfToken = $this->getCsrfToken();
    }

    public function setCsrfToken($csrfToken) {
        $this->csrfToken = $csrfToken;
    }

    public function getCsrfToken() {
        if (isset($_SESSION['csrf_token'])) {
            $tokenData = $_SESSION['csrf_token'];
            $timestamp = $tokenData['timestamp'];
            $currentTime = time();
    
            // Vérifiez si le jeton est toujours valide en fonction du timeout
            $timeout = 3600; // Durée de validité en secondes (1 heure)
            if (($currentTime - $timestamp) <= $timeout) {
                return $tokenData['token'];
            }
        }
    
        // Générer un nouveau jeton CSRF
        $csrfToken = bin2hex(random_bytes(32)); // Génération d'un jeton de 256 bits (32 octets)
        $timestamp = time();
        $_SESSION['csrf_token'] = ['token' => $csrfToken, 'timestamp' => $timestamp];
    
        return $csrfToken;
    }
}    
