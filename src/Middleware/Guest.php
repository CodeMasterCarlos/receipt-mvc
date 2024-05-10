<?php

namespace Codemastercarlos\Receipt\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Guest implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (isset($_SESSION['receipt']['user']['authorization']) && $_SESSION['receipt']['user']['value']) {
            return new Response(302, ["Location" => "/"]);
        }

        return $handler->handle($request);
    }
}