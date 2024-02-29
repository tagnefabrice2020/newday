<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Option\ReadOptionController;
use App\Http\Controllers\Option\UpdateOptionController;
use App\Http\Controllers\PracticeHistory\PracticeHistoryController;
use App\Http\Controllers\PracticeHistory\UserPracticeHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Topic\DeleteTopicController;
use App\Http\Controllers\Topic\ReadTopicController;
use App\Http\Controllers\Topic\StoreTopicController;
use App\Http\Controllers\Topic\TopicController;
use App\Http\Controllers\Topic\UpdateTopicController;
use App\Http\Controllers\Question\AddQuestionController;
use App\Http\Controllers\Question\DeleteQuestionController;
use App\Http\Controllers\Question\PracticeQuestionController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Question\ReadQuestionController;
use App\Http\Controllers\Question\UpdateQuestionController;
use App\Http\Controllers\Question\UploadBulkQuestionController;
use App\Http\Controllers\Topic\SearchController;
use App\Http\Controllers\Topic\TopicQuestionController;
use App\Http\Controllers\SyncUser\SyncUserController; 

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
Route::patch('/topics/{uuid}', [UpdateTopicController::class, 'update']);
Route::post('/topics', [StoreTopicController::class, 'store']);
Route::delete('/topics', [DeleteTopicController::class, 'destroy']);
Route::get('/topics/{uuid}/', [ReadTopicController::class, 'show']);



Route::get('/my-questions', [QuestionController::class, 'index']);
Route::post('/my-questions', [AddQuestionController::class, 'store']);
Route::delete('/my-questions/{uuid}', [DeleteQuestionController::class, 'destroy']);
Route::get('/my-questions/{id}', [ReadQuestionController::class, 'show']);
Route::put('/my-questions/{id}/update', [UpdateQuestionController::class, 'update']);

Route::middleware('auth:sanctum')->get('/options/{id}', [ReadOptionController::class, 'show']);
Route::middleware('auth:sanctum')->put('/options/{id}/update', [UpdateOptionController::class, 'update']);


Route::get('/topicList', [TopicController::class, 'topicList']);
Route::get('/topics/{uuid}/questions', [TopicQuestionController::class, 'index']);
Route::get('/topics/single/{uuid}/summary',  [ReadTopicController::class, 'showSingleTopicInfo']);
Route::post('/topics/history', [PracticeHistoryController::class, 'store']);
Route::get('/topic/exam/history/{topic_id}', [PracticeHistory::class, 'getHistoryByTopic']);
Route::get('/practiceQuestionsbyTopics/{uuid}', [PracticeHistoryController::class, 'index']);






Route::get('/search', [SearchController::class, 'search']);

Route::post('/syncuser', [SyncUserController::class, 'store']);

// u --- stands for used in front end app

Route::middleware("clerkAuth")->group(function () {
    Route::get('/test', [SearchController::class, 'search']);


    // get practice history stats by topics.
    Route::get('/practiceTestByUserGroupByTopics', [UserPracticeHistoryController::class, 'practiceHistoryByUserGroupByTopics']); // u
    // get users practice history for a particular topic --- returns a list of past practice taken for a particular topic.
    Route::get('/practiceTestByUserAndTopic/{topic}', [UserPracticeHistoryController::class, 'practiceHistoryByUserAndTopic']); // u
    // get a single practice history.
    Route::get('/practiceTest/{uuid}', [PracticeQuestionController::class, 'getSingle']); // u
    // update practice test.
    Route::patch('/practiceTest/{practice_uuid}', [PracticeQuestionController::class, 'updatePracticeHistory']); // u
    // create practice test.
    Route::post('/topics/practice/{topic_uuid}', [PracticeQuestionController::class, 'newPracticeHistoryQuestion']); // u
    // list of user question pool.
    // this contains also the filter with search param if exist.
    Route::get('/user/question-pool', [ReadTopicController::class, "showAllQuestionPoolByAuthUser"]);
    // list of user questions by question pool.
    // this contains also the filter with search param if exist.
    Route::get('/user/question-pool/{uuid}/questions', [ReadQuestionController::class, 'showAllQuestionsByUserAndByQuestionPool']);

    // practice test by user and topic / subject or course.
    // Route::get('/practice_history/list/{practice_uuid}/by/{author_email}', [PracticeQuestionController::class, 'getAllExamPracticesByTopicIdAndOwner']); 

    Route::get('/getPracticeHistoryTopic/{topic}', [UserPracticeHistoryController::class, 'practiceHistoryByTopics']); 


    Route::post('/upload-bulk-question', [UploadBulkQuestionController::class, 'uploadBulkQuestions']);
});