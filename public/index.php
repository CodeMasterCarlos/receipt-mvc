<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $routes = require __DIR__ . '/../routes/routes.php';

    $pathInfo = $_SERVER['PATH_INFO'] ?? '/';
    $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

    $psr17Factory = new Psr17Factory();

    $creator = new ServerRequestCreator(
        $psr17Factory,
        $psr17Factory,
        $psr17Factory,
        $psr17Factory
    );

    $serverRequest = $creator->fromGlobals();

    $route = $routes[$httpMethod][$pathInfo];
    if (isset($route)) {
        $controller = new $route['controller']();
        $action = $route['action'];

        /** @var ResponseInterface $response */
        $response = $controller->$action($serverRequest);

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        echo $response->getBody();
    } else {
        echo '<h1>404, NOT FOUND!</h1>';
    }
} catch(Throwable $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
    exit();
}
