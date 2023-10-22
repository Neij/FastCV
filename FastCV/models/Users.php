<?php

namespace Models;

class Users {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // public function getUserJobs($username) {

    // }

    public function addJob($username, $jobTitle) {
        $user = $this->getUserByUsername($username);
        
        $sql = "INSERT INTO jobs VALUES (NULL, ?, ?);";
        $params = [$user->id, $jobTitle];
        return $this->db->execute($sql, $params)
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        return $this->db->findOne($sql, $params);
    }

    public function resetLoginAttempts($username) {
        $sql = "UPDATE users SET login_attempts = 0, last_failed_login = NULL WHERE username = ?";
        $params = [$username];
        return $this->db->execute($sql, $params);
    }

    public function updateLoginAttempts($username) {
        // Obtenez le nombre de tentatives actuel et la date de la dernière tentative
        $currentAttemptsSql = "SELECT login_attempts, last_failed_login FROM users WHERE username = ?";
        $params = [$username];
        $userData = $this->db->findOne($currentAttemptsSql, $params);

        if (!$userData) {
            return false; // L'utilisateur n'existe pas
        }

        $currentAttempts = $userData['login_attempts'];
        $lastFailedLogin = $userData['last_failed_login'];

        // Incrémente le compteur de tentatives
        $currentAttempts++;

        // Mettez à jour la date de la dernière tentative infructueuse
        $sql = "UPDATE users SET login_attempts = ?, last_failed_login = NOW() WHERE username = ?";
        $params = [$currentAttempts, $username];
        return $this->db->execute($sql, $params);
    }

}

