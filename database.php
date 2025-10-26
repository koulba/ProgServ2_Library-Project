<?php

class Database {
    const DATABASE_FILE = '../booksmanager.db';

    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("sqlite:" . self::DATABASE_FILE);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "CREATE TABLE IF NOT EXISTS books (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            author TEXT NOT NULL,
            releasedate DATE,
            isbn TEXT UNIQUE,
            editor TEXT,
            saga INTEGER,
            note INTEGER
        );";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();
    }

    public function getPdo() {
        return $this->pdo;
    }
}