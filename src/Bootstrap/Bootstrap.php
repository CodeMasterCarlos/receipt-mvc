<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Codemastercarlos\Receipt\Controller\ErrorController;
use Codemastercarlos\Receipt\Controller\NotFoundController;
use Equip\Dispatch\MiddlewareCollection;
use LogicException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class Bootstrap
{
    use Logger;

    private string $path;

    private string $httpMethod;

    private ServerRequestInterface $request;

    private RequestHandlerInterface $controller;

    private string $action;

    private MiddlewareCollection $middlewares;

    public function __construct(private readonly array $routes, private readonly array $middlewaresNames, private readonly ContainerInterface $diContainer)
    {
        $this->setInfoRequest();
        $this->setServerRequest();

        try {
            $this->validationRoute();
            $this->response();
        } catch (Throwable $e) {
            $this->reportError($e);
        }
    }

    private function reportError(Throwable $e): void
    {
        $this->logger()->error($e->getMessage(), ["exception" => $e]);

        $message = $_ENV['APP'] === "production" ? "" : $e->getMessage();
        $this->controller = new ErrorController($message);
        $this->action = "handle";
        $this->middlewares = new MiddlewareCollection([]);
        $this->response();
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
            $this->createController($route['controller'], $route['action']);
            $listMiddlewares = $route['middlewares'];
        } else {
            $this->createController(NotFoundController::class, 'handle');
            $listMiddlewares = [];
        }

        $this->createCollectionMiddlewares($listMiddlewares);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createController($controllerName, $action): void
    {
        /** @var RequestHandlerInterface $controller */
        $controller = $this->diContainer->get($controllerName);

        $this->action = $action;

        if (is_subclass_of($controller, RequestHandlerInterface::class) === false) {
            throw new LogicException("O controller $controllerName deve implementar a interface RequestHandlerInterface");
        }

        $this->controller = $controller;
    }

    private function createCollectionMiddlewares($middlewares): void
    {
        $middleware = array_map(/**
         * @throws ContainerExceptionInterface
         * @throws NotFoundExceptionInterface
         */ function(string $middleware) {
            return $this->diContainer->get($this->middlewaresNames[$middleware]);
        }, $middlewares);

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