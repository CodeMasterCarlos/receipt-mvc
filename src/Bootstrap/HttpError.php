<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Codemastercarlos\Receipt\Controller\ErrorController;
use Codemastercarlos\Receipt\Bootstrap\Http\Request;
use Codemastercarlos\Receipt\Interfaces\Bootstrap\Http\HttpInterface;
use Equip\Dispatch\MiddlewareCollection;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HttpError implements HttpInterface
{
    private readonly Request $request;
    private readonly Throwable $e;

    public function __construct(Throwable $e)
    {
        $this->e = $e;
        $this->request = new Request();
    }

    public function getMiddlewares(): MiddlewareCollection
    {
        return new MiddlewareCollection([]);
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request->request();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function createController(ServerRequestInterface $request): ResponseInterface
    {
        return (new ErrorController($this->e))->handle($request);
    }
}
