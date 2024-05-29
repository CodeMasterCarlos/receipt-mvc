<?php

namespace Codemastercarlos\Receipt\Controller\User;

use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserController implements RequestHandlerInterface
{
    use View;

    public function __construct(private readonly UserRepository $repository)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userData = $_SESSION['receipt']['user']['value'];

        return new Response(200, body: $this->render('user', [
            "name" => $userData['name'],
            "email" => $userData['email'],
        ]));
    }
}
