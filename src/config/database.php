<?php

class Database {
    private const DB_HOST = 'root'; 
    private const DB_NAME = 'root'; 
    private const DB_USER = 'root'; 
    private const DB_PASS = 'root'; 
    private const DB_CHARSET = 'utf8mb4';

    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}