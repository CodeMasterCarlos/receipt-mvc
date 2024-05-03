<?php

namespace Codemastercarlos\Receipt\bootstrap;

use InvalidArgumentException;

class Route
{
    private static array $routes = [];

    public static function get(string $route, string $controller, string $action = 'handle'): void
    {
        self::createRoute('get', $route, $controller, $action);
    }

    public static function post(string $route, string $controller, string $action = 'handle'): void
    {
        self::createRoute('post', $route, $controller, $action);
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function run(array $fileRoutes): void
    {
        foreach($fileRoutes as $fileRoute) {
            require_once __DIR__ . "/../../routes/" . $fileRoute;
        }
    }

    private static function createRoute(string $method, string $route, string $controller, string $action): void
    {
        if (isset(self::$routes[$method][$route])) {
            throw new InvalidArgumentException("Rota duplicada: '$route' já está registrada.");
        }

        self::$routes[$method][$route] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'action' => $action,
        ];
    }
}
