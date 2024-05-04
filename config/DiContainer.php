<?php

use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions([
    PDO::class => static function (): PDO {
        $host = $_ENV['db_host'];
        $database = $_ENV['db_database'];
        $user = $_ENV['db_username'];
        $password = $_ENV['db_password'];

        return new PDO("mysql:host=$host;dbname=$database", $user, $password);
    },
]);

return $builder;
