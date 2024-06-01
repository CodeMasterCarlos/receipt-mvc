<?php

namespace Codemastercarlos\Receipt\Controller;

use Codemastercarlos\Receipt\Bootstrap\View;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ErrorController implements RequestHandlerInterface
{
    use View;

    private readonly Throwable $e;

    public function __construct(Throwable $e)
    {
        $this->e = $e;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(500, body: $this->render('error', $this->paramError()));
    }

    private function paramError(): array
    {
        $params = [
            "message" => $this->e->getMessage(),
            "code" => $this->e->getCode(),
            "file" => $this->e->getFile(),
            "line" => $this->e->getLine(),
            "trace" => $this->e->getTrace(),
        ];

        $params['status'] = $_ENV['APP'] === 'local';

        return $params;
    }
}
