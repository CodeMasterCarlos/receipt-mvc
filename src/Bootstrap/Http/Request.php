<?php

namespace Codemastercarlos\Receipt\Bootstrap\Http;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;

class Request
{
    private ServerRequestInterface $request;
    private string $method;
    private string $path;

    public function __construct()
    {
        $this->createRequest();
        $this->setInfoRequest();
    }

    public function request(): ServerRequestInterface
    {
        return $this->request;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    private function createRequest(): void
    {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        $this->request = $creator->fromGlobals();
    }

    private function setInfoRequest(): void
    {
        $this->path = $_SERVER['PATH_INFO'] ?? '/';
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }
}
