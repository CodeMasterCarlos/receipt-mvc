<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Psr\Container\{ContainerExceptionInterface, ContainerInterface, NotFoundExceptionInterface};
use Codemastercarlos\Receipt\Bootstrap\Http\{Controller, Middleware, Request};
use Codemastercarlos\Receipt\Controller\NotFoundController;
use Codemastercarlos\Receipt\Interfaces\Bootstrap\Http\HttpInterface;
use Equip\Dispatch\MiddlewareCollection;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class HttpDiContainer implements HttpInterface
{
    private readonly Request $request;
    private readonly Controller $controller;
    private readonly Middleware $middlewares;
    private string $routeControllerName = NotFoundController::class;
    private array $routeMiddlewares = [];

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        ContainerInterface $diContainer,
        array $routes,
        array $mapMiddlewares,
    )
    {
        $this->request = new Request();
        $this->controller = new Controller($diContainer);
        $this->middlewares = new Middleware($diContainer, $mapMiddlewares);

        $this->findingRoute($routes);

        $this->controller->createController($this->routeControllerName);
        $this->middlewares->createCollectionMiddlewares($this->routeMiddlewares);
    }

    private function findingRoute($routes): void
    {
        $route = $routes[$this->request->method()][$this->request->path()] ?? false;

        if ($route) {
            $this->routeControllerName = $route['controller'];
            $this->routeMiddlewares = $route['middlewares'];
        }
    }

    public function getMiddlewares(): MiddlewareCollection
    {
        return $this->middlewares->middlewares();
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request->request();
    }

    public function createController(ServerRequestInterface $request): ResponseInterface
    {
        return $this->controller->controller()->handle($request);
    }
}
