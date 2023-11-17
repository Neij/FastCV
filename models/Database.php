<?php

namespace Models;

class Database {

    protected $bdd;

    public function __construct() {
        $this->bdd = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function prepare($sql) {
        return $this->bdd->prepare($sql);
    }

    public function fetchAll($sql, $params = []) {
        $statement = $this->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(); 
    }
    
    public function fetchColumn($sql, $params = []) {
        $statement = $this->prepare($sql);
        $statement->execute($params);
        return $statement->fetchColumn();
    }
    
    public function getLastInsertId() {
        return $this->bdd->lastInsertId();
    }

    public function findOne($sql, $params = []) {
        $query = $this->prepare($sql);
        $query->execute($params);
        return $query->fetch();
    }

    public function execute($sql, $params = []) {
        $query = $this->prepare($sql);
        $query->execute($params);
        return $query;
    }
}
