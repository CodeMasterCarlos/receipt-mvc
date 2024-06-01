<?php

namespace Codemastercarlos\Receipt\Interfaces\Bootstrap\Http;

use Equip\Dispatch\MiddlewareCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HttpInterface
{
    public function getMiddlewares(): MiddlewareCollection;

    public function getRequest(): ServerRequestInterface;

    public function createController(ServerRequestInterface $request): ResponseInterface;
}
