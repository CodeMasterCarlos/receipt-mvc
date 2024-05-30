<?php

namespace Codemastercarlos\Receipt\Bootstrap\Route;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Route\RouteFileInterface;
use InvalidArgumentException;

class RouteFile implements RouteFileInterface
{
    private string $routeDirectory = __DIR__ . "/../../../routes/";

    private string $pathFile;

    public function __construct(
        string $fileRoute,
        bool $absolutePath = false
    )
    {
        $this->pathFile = $absolutePath ? $fileRoute : $this->routeDirectory . $fileRoute;

        if (!file_exists($this->pathFile)) {
            throw new InvalidArgumentException("Arquivo de rota $this->pathFile não foi encontrado.");
        }
    }

    public function requiredRoute(): void
    {
        require $this->pathFile;
    }
}
