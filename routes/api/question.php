<?php

use App\Http\Controllers\Question\AddQuestionController;
use App\Http\Controllers\Question\DeleteQuestionController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Question\ReadQuestionController;
use Illuminate\Routing\Route;

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/questions', [AddQuestionController::class, 'store']);
Route::delete('/questions', [DeleteQuestionController::class, 'destroy']);
Route::get('/questions/{id}', [ReadQuestionController::class, 'show']);
