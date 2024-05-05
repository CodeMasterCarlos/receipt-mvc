<?php

namespace Codemastercarlos\Receipt\Controller;

use Codemastercarlos\Receipt\Bootstrap\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ErrorController implements RequestHandlerInterface
{
    use View;

    public function __construct(private readonly string $message = "")
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(500, body: $this->render('error', ["message" => $this->message]));
    }
}
