<?php

use Codemastercarlos\Receipt\bootstrap\Bootstrap;

require_once __DIR__ . '/../vendor/autoload.php';

$routes = require __DIR__ . '/../routes/routes.php';

$middlewares = require __DIR__ . '/../config/Middlewares.php';

new Bootstrap($routes, $middlewares);