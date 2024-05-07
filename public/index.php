<?php

use Codemastercarlos\Receipt\Bootstrap\Bootstrap;
use Psr\Container\ContainerInterface;

$_SESSION ?? session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$routes = require __DIR__ . '/../routes/routes.php';

$middlewares = require __DIR__ . '/../config/Middlewares.php';

$builder = require __DIR__ . '/../config/DiContainer.php';
/** @var ContainerInterface $container */
$diContainer = $builder->build();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

new Bootstrap($routes, $middlewares, $diContainer);
