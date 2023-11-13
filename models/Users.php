<?php

namespace Models;

class Users
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createUser($username, $hashedPassword, $email) {
        // Obtenir la date et l'heure actuelles
        $currentDateTime = date("Y-m-d H:i:s");

        // Insérez les données dans la base de données, y compris la date de création
        $sql = "INSERT INTO users (username, password, email, date_creation) VALUES (?, ?, ?, ?)";
        $params = [$username, $hashedPassword, $email, $currentDateTime];
        return $this->db->execute($sql, $params);
    }
    
    public function addJob($userId, $jobTitle, $jobDate, $jobDescription)
    {
        $sql = "INSERT INTO jobs (user_id, title, date, description) VALUES (?, ?, ?, ?)";
        $params = [$userId, $jobTitle, $jobDate, $jobDescription];
        return $this->db->execute($sql, $params);
    }

    public function deleteJob($jobId, $userId)
    {
        $sql = "DELETE FROM jobs WHERE id = ? AND user_id = ?";
        $params = [$jobId, $userId];
        return $this->db->execute($sql, $params);
    }

    public function updateJob($jobId, $title, $date, $description, $userId)
    {
        $sql = "UPDATE jobs SET title = ?, date = ?, description = ? WHERE id = ? AND user_id = ?";
        $params = [$title, $date, $description, $jobId, $userId];
        return $this->db->execute($sql, $params);
    }

    public function getJobsByUserId($userId)
    {
        $sql = "SELECT * FROM jobs WHERE user_id = ?";
        $params = [$userId];
        $jobs = $this->db->fetchAll($sql, $params);
        return $jobs;
    }

    public function addEducation($userId, $institution, $degree, $graduationYear)
    {
        $sql = "INSERT INTO educations (user_id, institution, degree, graduation_year) VALUES (?, ?, ?, ?)";
        $params = [$userId, $institution, $degree, $graduationYear];
        return $this->db->execute($sql, $params);
    }

    public function deleteEducation($educationId, $userId)
    {
        $sql = "DELETE FROM educations WHERE id = ? AND user_id = ?";
        $params = [$educationId, $userId];
        return $this->db->execute($sql, $params);
    }

    public function updateEducation($educationId, $userId, $institution, $degree, $graduationYear)
    {
        $sql = "UPDATE educations SET institution = ?, degree = ?, graduation_year = ? WHERE id = ? AND user_id = ?";
        $params = [$institution, $degree, $graduationYear, $educationId, $userId];
        return $this->db->execute($sql, $params);
    }

    public function getEducationsByUserId($userId)
    {
        $sql = "SELECT * FROM educations WHERE user_id = ?";
        $params = [$userId];
        $educations = $this->db->fetchAll($sql, $params);
        return $educations;
    }

    public function addPersonalInfo($userId, $firstName, $lastName, $address, $description)
    {
        $sql = "INSERT INTO personal_info (user_id, first_name, last_name, address, description) VALUES (?, ?, ?, ?, ?)";
        $params = [$userId, $firstName, $lastName, $address, $description];
        return $this->db->execute($sql, $params);
    }

    public function getPersonalInfoByUserId($userId)
    {
        $sql = "SELECT * FROM personal_info WHERE user_id = ?";
        $params = [$userId];
        return $this->db->fetchAll($sql, $params);
    }

    public function updatePersonalInfo($personalInfoId, $userId, $firstName, $lastName, $address, $description)
    {
        $sql = "UPDATE personal_info SET first_name = ?, last_name = ?, address = ?, description = ? WHERE id = ? AND user_id = ?";
        $params = [$firstName, $lastName, $address, $description, $personalInfoId, $userId];
        return $this->db->execute($sql, $params);
    }
    
    public function deletePersonalInfo($personalInfoId, $userId)
    {
        $sql = "DELETE FROM personal_info WHERE id = ? AND user_id = ?";
        $params = [$personalInfoId, $userId];
        return $this->db->execute($sql, $params);
    }


    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        return $this->db->findOne($sql, $params);
    }

    public function getUserById($userId)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = [$userId];
        return $this->db->findOne($sql, $params);
    }

    public function resetLoginAttempts($username)
    {
        $sql = "UPDATE users SET login_attempts = 0, last_failed_login = NULL WHERE username = ?";
        $params = [$username];
        return $this->db->execute($sql, $params);
    }

    public function updateLoginAttempts($username)
    {
        // Obtenez le nombre de tentatives actuel et la date de la dernière tentative
        $currentAttemptsSql = "SELECT login_attempts, last_failed_login FROM users WHERE username = ?";
        $params = [$username];
        $userData = $this->db->findOne($currentAttemptsSql, $params);

        if (!$userData) {
            return false; // L'utilisateur n'existe pas
        }

        $currentAttempts = $userData['login_attempts'];

        // Incrémente le compteur de tentatives
        $currentAttempts++;

        // Mettez à jour la date de la dernière tentative infructueuse
        $sql = "UPDATE users SET login_attempts = ?, last_failed_login = NOW() WHERE username = ?";
        $params = [$currentAttempts, $username];
        return $this->db->execute($sql, $params);
    }
}
