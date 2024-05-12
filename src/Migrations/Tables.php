<?php

namespace Codemastercarlos\Receipt\Migrations;

use PDO;

class Tables
{
    public function __construct(private readonly PDO $pdo)
    {
        $this->createTables();
    }

    public function createTables(): void
    {
        $this->createTableUser();
        $this->createTableReceipt();
    }

    private function createTableUser(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS user(
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->query($sql);
    }

    private function createTableReceipt(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS receipt(
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            image VARCHAR(255) NOT NULL,
            id_user INT NOT NULL,
            date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (id_user) REFERENCES user(id)
        )";
        $this->pdo->query($sql);
    }
}
