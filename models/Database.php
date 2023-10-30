<?php

namespace Models;

class Database {

    protected $bdd;

    public function __construct() {
        $this->bdd = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, // retourne un tableau indexé par le nom de la colonne
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION // lance PDOExeptions
        ]);
    }

    public function fetchAll($sql, $params = []) {
        $statement = $this->bdd->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(\PDO::FETCH_ASSOC); // Utilisation du namespace global PDO
    }
    
    public function getLastInsertId() {
        return $this->bdd->lastInsertId();
    }

    public function findOne($sql, $params = []) {
        $query = $this->bdd->prepare($sql);
        $query->execute($params);
        return $query->fetch();
    }

    public function execute($sql, $params = []) {
        $query = $this->bdd->prepare($sql);
        $query->execute($params);
        return $query;
    }
}
