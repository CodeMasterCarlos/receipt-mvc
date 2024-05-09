<?php

use Codemastercarlos\Receipt\Bootstrap\PdoClass;
use Codemastercarlos\Receipt\Migrations\Tables;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = require __DIR__ . '/../config/DiContainer.php';
/** @var ContainerInterface $container */
$diContainer = $builder->build();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$database = new Tables(PdoClass::getPdo());

echo "Tabelas criadas com sucesso!";
