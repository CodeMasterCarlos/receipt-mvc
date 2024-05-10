<?php

namespace Codemastercarlos\Receipt\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Web implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $publicKey = file_get_contents($_ENV['JWT_KEY_PUBLIC']);

            $jwt = $_SESSION['receipt']['user']['authorization'] ?? "";

            $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
            $_SESSION['receipt']['user']['value'] = [
                "id" => $decoded->id,
                "name" => $decoded->name,
                "email" => $decoded->email,
            ];
        } catch (\Exception) {
            return new Response(302, ["Location" => "/login"]);
        }

        return $handler->handle($request);
    }
}