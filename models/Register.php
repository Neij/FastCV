<?php

namespace Models;

class Register {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function createUser($username, $hashedPassword, $email) {
        
        $currentDateTime = date("Y-m-d H:i:s");

        $sql = "INSERT INTO users (username, password, email, date_creation) VALUES (?, ?, ?, ?)";
        $params = [$username, $hashedPassword, $email, $currentDateTime];
        return $this->db->execute($sql, $params);
    }

    public function isUsernameTaken($username) {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $params = [$username];
        $count = $this->db->fetchColumn($sql, $params);
        return $count > 0;
    }
}
