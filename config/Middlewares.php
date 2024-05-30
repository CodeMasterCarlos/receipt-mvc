<?php

use Codemastercarlos\Receipt\Middleware\Guest;
use Codemastercarlos\Receipt\Middleware\Web;

return [
    'web' => Web::class,
    'guest' => Guest::class,
];
