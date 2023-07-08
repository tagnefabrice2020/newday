<?php

use App\Http\Controllers\Topic\ReadTopicController;
use App\Http\Controllers\Topic\StoreTopicController;
use App\Http\Controllers\Topic\TopicController;
use Illuminate\Routing\Route;

Route::get('/topics', [TopicController::class, 'index']);
Route::post('/topics', [StoreTopicController::class, 'store']);
Route::delete('/topics', [DeleteTopicController::class, 'destroy']);
Route::get('/topics/{id}', [ReadTopicController::class, 'show']);
