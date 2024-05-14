<?php

use Codemastercarlos\Receipt\Bootstrap\Route;
use Codemastercarlos\Receipt\Controller\HomeController;
use Codemastercarlos\Receipt\Controller\LoginController;
use Codemastercarlos\Receipt\Controller\NotFoundController;
use Codemastercarlos\Receipt\Controller\ReceiptController;
use Codemastercarlos\Receipt\Controller\RegisterController;
use Codemastercarlos\Receipt\Controller\SearchController;
use Codemastercarlos\Receipt\Controller\UserController;

Route::get('/', HomeController::class, middlewares: ['web']);

Route::get('/receipt/create', ReceiptController::class, middlewares: ['web']);
Route::post('/receipt/create', ReceiptController::class, 'store', ['web']);
Route::post('/receipt/remove', ReceiptController::class, 'destroy', ['web']);
Route::get('/receipt/edit', ReceiptController::class, 'edit', ['web']);
Route::post('/receipt/edit', ReceiptController::class, 'update', ['web']);

Route::get('/search', SearchController::class, middlewares: ['web']);

Route::get('/user', UserController::class, middlewares: ['web']);
Route::post('/user', UserController::class, 'store', ['web']);
Route::post('/user/logout', UserController::class, 'destroy', ['web']);

Route::get('/login', LoginController::class, middlewares: ['guest']);
Route::post('/login', LoginController::class, 'store', ['guest']);
Route::get('/register', RegisterController::class, middlewares: ['guest']);
Route::post('/register', RegisterController::class, 'store', ['guest']);
