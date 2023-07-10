<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Topic\DeleteTopicController;
use App\Http\Controllers\Topic\ReadTopicController;
use App\Http\Controllers\Topic\StoreTopicController;
use App\Http\Controllers\Topic\TopicController;
use App\Http\Controllers\Topic\UpdateTopicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/topics', [TopicController::class, 'index']);
Route::patch('/topics/{id}', [UpdateTopicController::class, 'update']);
Route::post('/topics', [StoreTopicController::class, 'store']);
Route::delete('/topics', [DeleteTopicController::class, 'destroy']);
Route::get('/topics/{id}/', [ReadTopicController::class, 'show']);



// @include('./api/auth.php');
// @include('./api/user.php');
// @include('./api/question.php');
// @include('./api/topic.php');
