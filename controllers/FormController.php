<?php

namespace Controllers;

class FormController extends AppController {
    public $csrfToken;

    public function __construct($pageActive, $template) {
        parent::__construct($pageActive, $template);

        // Vérifiez si le jeton CSRF existe déjà dans la session
        if (isset($_SESSION['csrf_token'])) {
            $this->csrfToken = $_SESSION['csrf_token'];
        } else {
            // S'il n'existe pas, générez un nouveau jeton CSRF
            $this->csrfToken = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $this->csrfToken;
        }
    }

    public function setCsrfToken($csrfToken) {
        $this->csrfToken = $csrfToken;
    }
    
    public function getCsrfToken() {
        if (isset($_SESSION['csrf_token']) && is_array($_SESSION['csrf_token'])) {
            $tokenData = $_SESSION['csrf_token'];
            $timestamp = $tokenData['timestamp'];
            $currentTime = time();
    
            // Vérifiez si le jeton est toujours valide en fonction du timeout
            $timeout = 3600; // (1 heure)
            if (($currentTime - $timestamp) <= $timeout) {
                return $tokenData['token'];
            }
        }
    
        // Générer un nouveau jeton CSRF si la session n'a pas de jeton valide
        $csrfToken = bin2hex(random_bytes(32)); // Génération d'un jeton de 256 bits (32 octets)
        $timestamp = time();
        $_SESSION['csrf_token'] = ['token' => $csrfToken, 'timestamp' => $timestamp];
    
        return $csrfToken;
    }
    
}
