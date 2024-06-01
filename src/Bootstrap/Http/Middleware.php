<?php

namespace Codemastercarlos\Receipt\Bootstrap\Http;

use Equip\Dispatch\MiddlewareCollection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Middleware
{
    private MiddlewareCollection $middlewares;
    private array $mapMiddlewares;
    private ContainerInterface $diContainer;

    public function __construct(ContainerInterface $diContainer, array $mapMiddlewares)
    {
        $this->mapMiddlewares = $mapMiddlewares;
        $this->diContainer = $diContainer;
    }

    public function createCollectionMiddlewares($middlewares): void
    {
        $middleware = array_map([$this, 'createMiddlewareInstance'], $middlewares);

        $this->middlewares = new MiddlewareCollection($middleware);
    }

    public function middlewares(): MiddlewareCollection
    {
        return $this->middlewares;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createMiddlewareInstance(string $middleware)
    {
        return $this->diContainer->get($this->mapMiddlewares[$middleware]);
    }
}
