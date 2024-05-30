<?php

use Codemastercarlos\Receipt\Bootstrap\Route\Route;
use Codemastercarlos\Receipt\Controller\Receipt\ReceiptController;

Route::get('/web', ReceiptController::class, middlewares: ['web']);
