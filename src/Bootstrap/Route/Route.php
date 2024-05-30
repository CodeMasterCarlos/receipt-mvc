<?php

namespace Codemastercarlos\Receipt\Bootstrap\Route;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Route\RouteFileInterface;
use InvalidArgumentException;

class Route
{
    private static array $routes = [];

    public static function get(
        string $route,
        string $controller,
        array $middlewares = []
    ): void
    {
        self::createRoute('get', $route, $controller, $middlewares);
    }

    public static function post(
        string $route,
        string $controller,
        array $middlewares = []
    ): void
    {
        self::createRoute('post', $route, $controller, $middlewares);
    }

    public static function allRoutes(): array
    {
        return self::$routes;
    }

    public static function requiredFileRoutes(RouteFileInterface ...$fileRoutes): void
    {
        foreach ($fileRoutes as $fileRoute) {
            $fileRoute->requiredRoute();
        }
    }

    private static function createRoute(
        string $method,
        string $route,
        string $controller,
        array $middlewares
    ): void
    {
        if (isset(self::$routes[$method][$route])) {
            throw new InvalidArgumentException("Rota duplicada: '$route' já está registrada.");
        }

        self::$routes[$method][$route] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'middlewares' => $middlewares,
        ];
    }
}
