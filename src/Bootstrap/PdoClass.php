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

        return new PDO("mysql:host23=$host;dbname=$database", $user, $password);
    }
}