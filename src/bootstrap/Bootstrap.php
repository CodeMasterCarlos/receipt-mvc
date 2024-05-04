<?php

namespace Codemastercarlos\Receipt\bootstrap;

use Equip\Dispatch\MiddlewareCollection;
use LogicException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Bootstrap
{
    private string $path;

    private string $httpMethod;

    private ServerRequestInterface $request;

    private RequestHandlerInterface $controller;

    private string $action;

    private MiddlewareCollection $middlewares;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(private readonly array $routes, private readonly array $middlewaresNames, private readonly ContainerInterface $diContainer)
    {
        $this->setInfoRequest();
        $this->setServerRequest();

        $this->validationRoute();
    }

    private function setInfoRequest(): void
    {
        $this->path = $_SERVER['PATH_INFO'] ?? '/';
        $this->httpMethod = strtolower($_SERVER['REQUEST_METHOD']);
    }

    private function setServerRequest(): void
    {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $this->request = $creator->fromGlobals();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function validationRoute(): void
    {
        $route = $this->routes[$this->httpMethod][$this->path];
        if (isset($route)) {
            $this->createController($route);
            $this->createCollectionMiddlewares($route);

            $this->response();
        } else {
            echo '<h1>404, NOT FOUND!</h1>';
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createController($route): void
    {
        /** @var RequestHandlerInterface $controller */
        $controller = $this->diContainer->get($route['controller']);

        $this->action = $route['action'];

        if (is_subclass_of($controller, RequestHandlerInterface::class) === false) {
            throw new LogicException("O controller {$route['controller']} deve implementar a interface RequestHandlerInterface");
        }

        $this->controller = $controller;
    }

    private function createCollectionMiddlewares($route): void
    {
        $middleware = array_map(function(string $middleware) {
            return new $this->middlewaresNames[$middleware]();
        }, $route['middlewares']);

        $this->middlewares = new MiddlewareCollection($middleware);
    }

    private function response(): void
    {
        $response = $this->middlewares->dispatch($this->request, function(ServerRequestInterface $request) {
            $action = $this->action;
            return $this->controller->$action($request);
        });

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        http_response_code($response->getStatusCode());

        echo $response->getBody();
    }
}