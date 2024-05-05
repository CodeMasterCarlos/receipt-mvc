<?php

namespace Codemastercarlos\Receipt\Controller;

use Codemastercarlos\Receipt\bootstrap\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RegisterController implements RequestHandlerInterface
{
    use View;

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, body: $this->render('register'));
    }

}
