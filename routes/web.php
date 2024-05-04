<?php

use Codemastercarlos\Receipt\bootstrap\Route;
use Codemastercarlos\Receipt\Controller\HomeController;

Route::get('/', HomeController::class);
Route::post('/', HomeController::class, 'store', ['web']);
