<?php

namespace Codemastercarlos\Receipt\Views;

class Utils
{
    public function checkRoute(string $nameRoute): bool
    {
        $path = $_SERVER['PATH_INFO'] ?? "/";
        return $path === $nameRoute;
    }
}