<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Option\ReadOptionController;
use App\Http\Controllers\Option\UpdateOptionController;
use App\Http\Controllers\PracticeHistory\PracticeHistoryController;
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
use App\Http\Controllers\Question\UpdateQuestionController;
use App\Http\Controllers\Topic\TopicQuestionController;
use App\Models\PracticeHistory;

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

Route::get('/topics/{uuid}/questions', [TopicQuestionController::class, 'index']);

Route::post('/topics/history', [PracticeHistoryController::class, 'store']);
Route::get('/topic/exam/history/{topic_id}', [PracticeHistory::class, 'getHistoryByTopic']);

Route::middleware('auth:sanctum')->get('/my-questions', [QuestionController::class, 'index']);
Route::middleware('auth:sanctum')->post('/my-questions', [AddQuestionController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/my-questions/{uuid}', [DeleteQuestionController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/my-questions/{id}', [ReadQuestionController::class, 'show']);
Route::middleware('auth:sanctum')->put('/my-questions/{id}/update', [UpdateQuestionController::class, 'update']);

Route::middleware('auth:sanctum')->get('/options/{id}', [ReadOptionController::class, 'show']);
Route::middleware('auth:sanctum')->put('/options/{id}/update', [UpdateOptionController::class, 'update']);

Route::get('/topicList', [TopicController::class, 'topicList']);
