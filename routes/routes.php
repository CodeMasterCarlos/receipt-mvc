<?php

use Codemastercarlos\Receipt\Bootstrap\Route\Route;
use Codemastercarlos\Receipt\Bootstrap\Route\RouteFile;

Route::requiredFileRoutes(
    new RouteFile('web.php'),
);

return Route::allRoutes();
