<?php

use Codemastercarlos\Receipt\Bootstrap\Route;
use Codemastercarlos\Receipt\Controller\HomeController;
use Codemastercarlos\Receipt\Controller\LoginController;
use Codemastercarlos\Receipt\Controller\NotFoundController;
use Codemastercarlos\Receipt\Controller\RegisterController;
use Codemastercarlos\Receipt\Controller\SearchController;
use Codemastercarlos\Receipt\Controller\UserController;

Route::get('/', HomeController::class, middlewares: ['web']);
Route::get('/receipt/create', HomeController::class, 'create');
Route::get('/search', SearchController::class);
Route::get('/user', UserController::class);
Route::get('/login', LoginController::class);
Route::get('/register', RegisterController::class);
