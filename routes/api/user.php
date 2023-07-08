<?php

use App\Http\Controllers\User\ReadUserController;
use App\Http\Controllers\User\StoreUserController;
use App\Http\Controllers\User\UpdateUserController;
use App\Http\Controllers\User\UserController;
use Illuminate\Routing\Route;

Route::get('/user', [UserController::class, 'index']);
Route::post('/users', [StoreUserController::class, 'store']);
Route::get('/users/{id}', [ReadUserController::class, 'show']);
Route::patch('/users/{id}', [UpdateUserController::class, 'update']);