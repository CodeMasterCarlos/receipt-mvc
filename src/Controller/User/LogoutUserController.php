<?php

namespace Codemastercarlos\Receipt\Controller\User;

use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutUserController implements RequestHandlerInterface
{
    use View;

    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        unset($_SESSION['receipt']['user']);
        return new Response(302, ['Location' => '/login']);
    }
}
