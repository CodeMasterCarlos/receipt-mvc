<?php

namespace Codemastercarlos\Receipt\Bootstrap\Provider;

class FinishRequestProvider
{
    public function __construct()
    {
        unset($_SESSION['validate']['flash']);
    }
}