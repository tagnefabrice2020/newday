<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Routing\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'login']);