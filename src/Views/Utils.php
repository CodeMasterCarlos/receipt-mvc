<?php

namespace Codemastercarlos\Receipt\Views;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;

class Utils
{
    use FlasherMessage;

    public function checkRoute(string $nameRoute): bool
    {
        $path = $_SERVER['PATH_INFO'] ?? "/";
        return $path === $nameRoute;
    }

    public function isThereMessage(): bool
    {
        return isset($_SESSION['receipt']['message']);
    }

    public function getMessage(): array
    {
        return $this->getFlasherMessage();
    }

    public function valueParams(): array
    {
        $values = $_SESSION['receipt']['validation']['params'] ?? [];

        unset($_SESSION['receipt']['validation']['params']);
        return $values;
    }
}