<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use PDO;

class PdoClass
{
    public static function getPdo(): PDO {
        $host = $_ENV['DB_HOST'];
        $database = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $pdo = new PDO("mysql:host23=$host;dbname=$database", $user, $password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}