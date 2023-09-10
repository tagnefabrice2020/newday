<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Topic\DeleteTopicController;
use App\Http\Controllers\Topic\ReadTopicController;
use App\Http\Controllers\Topic\StoreTopicController;
use App\Http\Controllers\Topic\TopicController;
use App\Http\Controllers\Topic\UpdateTopicController;
use App\Http\Controllers\Question\AddQuestionController;
use App\Http\Controllers\Question\DeleteQuestionController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Question\ReadQuestionController;
use App\Http\Controllers\Topic\TopicQuestionController;

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

Route::middleware('auth:sanctum')->get('/topics', [TopicController::class, 'index']);
Route::middleware('auth:sanctum')->patch('/topics/{uuid}', [UpdateTopicController::class, 'update']);
Route::middleware('auth:sanctum')->post('/topics', [StoreTopicController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/topics', [DeleteTopicController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/topics/{uuid}/', [ReadTopicController::class, 'show']);
Route::middleware('auth:sanctum')->get('/topics/{uuid}/questions', [TopicQuestionController::class, 'index']);

Route::middleware('auth:sanctum')->get('/questions', [QuestionController::class, 'index']);
Route::middleware('auth:sanctum')->post('/questions', [AddQuestionController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/questions', [DeleteQuestionController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/questions/{id}', [ReadQuestionController::class, 'show']);



// @include('./api/auth.php');
// @include('./api/user.php');
// @include('./api/question.php');
// @include('./api/topic.php');
