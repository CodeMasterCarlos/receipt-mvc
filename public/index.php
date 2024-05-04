<?php

use Codemastercarlos\Receipt\bootstrap\Bootstrap;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$routes = require __DIR__ . '/../routes/routes.php';

$middlewares = require __DIR__ . '/../config/Middlewares.php';

try {
    $builder = require __DIR__ . '/../config/DiContainer.php';
    /** @var ContainerInterface $container */
    $diContainer = $builder->build();

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    new Bootstrap($routes, $middlewares, $diContainer);
} catch(Throwable $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
    echo '<p>' . $e->getLine() . '</p>';
    echo '<p>' . $e->getFile() . '</p>';
    exit();
}
