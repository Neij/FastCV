<?php

namespace Models;

class Users {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function addJob($userId, $jobTitle, $jobDate, $jobDescription) {
        $sql = "INSERT INTO jobs (user_id, title, date, description) VALUES (?, ?, ?, ?)";
        $params = [$userId, $jobTitle, $jobDate, $jobDescription];
        return $this->db->execute($sql, $params);
    }

    public function deleteJob($jobId, $userId) {
        $sql = "DELETE FROM jobs WHERE id = ? AND user_id = ?";
        $params = [$jobId, $userId];
        return $this->db->execute($sql, $params);
    }
    

    public function getJobsByUserId($userId) {
        $sql = "SELECT * FROM jobs WHERE user_id = ?";
        $params = [$userId];
        $jobs = $this->db->fetchAll($sql, $params);
        return $jobs;
    }
    

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        return $this->db->findOne($sql, $params);
    }

    public function getUserById($userId) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
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

