<?php

namespace Codemastercarlos\Receipt\Bootstrap\Http;

use LogicException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Controller
{
    private RequestHandlerInterface $controller;
    private ContainerInterface $diContainer;

    public function __construct(ContainerInterface $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createController($controllerName): void
    {
        /** @var RequestHandlerInterface $controller */
        $controller = $this->diContainer->get($controllerName);

        if (is_subclass_of($controller, RequestHandlerInterface::class) === false) {
            throw new LogicException("O controller $controllerName deve implementar a interface RequestHandlerInterface");
        }

        $this->controller = $controller;
    }

    public function controller(): RequestHandlerInterface
    {
        return $this->controller;
    }
}
