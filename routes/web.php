<?php

use Codemastercarlos\Receipt\Bootstrap\Route;
use Codemastercarlos\Receipt\Controller\HomeController;
use Codemastercarlos\Receipt\Controller\LoginController;
use Codemastercarlos\Receipt\Controller\NotFoundController;
use Codemastercarlos\Receipt\Controller\RegisterController;
use Codemastercarlos\Receipt\Controller\SearchController;
use Codemastercarlos\Receipt\Controller\UserController;

Route::get('/', HomeController::class, middlewares: ['web']);
Route::get('/receipt/create', HomeController::class, 'create', ['web']);
Route::get('/search', SearchController::class, middlewares: ['web']);
Route::get('/user', UserController::class, middlewares: ['web']);

Route::get('/login', LoginController::class, middlewares: ['guest']);
Route::post('/login', LoginController::class, 'store', ['guest']);
Route::get('/register', RegisterController::class, middlewares: ['guest']);
Route::post('/register', RegisterController::class, 'store', ['guest']);
