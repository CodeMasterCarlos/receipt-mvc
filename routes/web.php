<?php

use Codemastercarlos\Receipt\Bootstrap\Route\Route;
use Codemastercarlos\Receipt\Controller\Authenticate\Login\LoginController;
use Codemastercarlos\Receipt\Controller\Authenticate\Login\StoreLoginController;
use Codemastercarlos\Receipt\Controller\Authenticate\Register\RegisterController;
use Codemastercarlos\Receipt\Controller\Authenticate\Register\StoreRegisterController;
use Codemastercarlos\Receipt\Controller\Receipt\CreateReceiptController;
use Codemastercarlos\Receipt\Controller\Receipt\DeleteReceiptController;
use Codemastercarlos\Receipt\Controller\Receipt\EditReceiptController;
use Codemastercarlos\Receipt\Controller\Receipt\ReceiptController;
use Codemastercarlos\Receipt\Controller\Receipt\StoreReceiptController;
use Codemastercarlos\Receipt\Controller\Receipt\UpdateReceiptController;
use Codemastercarlos\Receipt\Controller\SearchController;
use Codemastercarlos\Receipt\Controller\User\LogoutUserController;
use Codemastercarlos\Receipt\Controller\User\StoreUserController;
use Codemastercarlos\Receipt\Controller\User\UserController;

Route::get('/', ReceiptController::class, middlewares: ['web']);

Route::get('/receipt/create', CreateReceiptController::class, middlewares: ['web']);
Route::post('/receipt/create', StoreReceiptController::class,middlewares: ['web']);
Route::post('/receipt/remove', DeleteReceiptController::class, middlewares: ['web']);
Route::get('/receipt/edit', EditReceiptController::class, middlewares: ['web']);
Route::post('/receipt/edit', UpdateReceiptController::class, middlewares: ['web']);

Route::get('/search', SearchController::class, middlewares: ['web']);

Route::get('/user', UserController::class, middlewares: ['web']);
Route::post('/user', StoreUserController::class, middlewares: ['web']);
Route::post('/user/logout', LogoutUserController::class, middlewares: ['web']);

Route::get('/login', LoginController::class, middlewares: ['guest']);
Route::post('/login', StoreLoginController::class, middlewares: ['guest']);
Route::get('/register', RegisterController::class, middlewares: ['guest']);
Route::post('/register', StoreRegisterController::class, middlewares: ['guest']);
