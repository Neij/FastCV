<?php

namespace Controllers;

use helpers\SessionManager;

class FormController extends AppController {
    public $csrfToken;

    public function __construct($pageActive, $template) {
        parent::__construct($pageActive, $template);

        $storedCsrfToken = SessionManager::get('csrf_token');
        if ($storedCsrfToken !== null) {
            $this->csrfToken = $storedCsrfToken;
        } else {
            
            $this->csrfToken = bin2hex(random_bytes(32));
            SessionManager::set('csrf_token', $this->csrfToken);
        }
    }

    public function setCsrfToken($csrfToken) {
        $this->csrfToken = $csrfToken;
    }
    
    public function getCsrfToken() {
        $storedCsrfToken = SessionManager::get('csrf_token');
        if ($storedCsrfToken !== null && is_array($storedCsrfToken)) {
            $tokenData = $storedCsrfToken;
            $timestamp = $tokenData['timestamp'];
            $currentTime = time();
    
            $timeout = 3600; 
            if (($currentTime - $timestamp) <= $timeout) {
                return $tokenData['token'];
            }
        }
    
        $csrfToken = bin2hex(random_bytes(32)); 
        $timestamp = time();
        SessionManager::set('csrf_token', ['token' => $csrfToken, 'timestamp' => $timestamp]);
    
        return $csrfToken;
    }
}
