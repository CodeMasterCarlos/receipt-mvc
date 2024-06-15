<?php

namespace Codemastercarlos\Receipt\Bootstrap\Provider;

use JetBrains\PhpStorm\NoReturn;

class BackPageExceptionProvider
{
    #[NoReturn]
    public function __construct()
    {
        $pathBack = $_SERVER['HTTP_REFERER'];

        header('Location: ' . $pathBack);
        exit();
    }
}