<?php

namespace Codemastercarlos\Receipt\Bootstrap;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Http\HttpInterface;
use Psr\Http\Message\ServerRequestInterface;

class Bootstrap
{
    use Logger;

    private readonly HttpInterface $http;

    public function __construct(
        HttpInterface $http
    )
    {
        $this->http = $http;

        $this->createResponse();
    }

    private function createResponse(): void
    {
        $response = $this->http->getMiddlewares()->dispatch($this->http->getRequest(),
            function (ServerRequestInterface $request) {
                return $this->http->createController($request);
            }
        );

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        http_response_code($response->getStatusCode());

        echo $response->getBody();
    }
}
