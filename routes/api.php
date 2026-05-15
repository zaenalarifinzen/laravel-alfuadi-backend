<?php

use App\Http\Controllers\Api\WordGroupController;
use App\Http\Controllers\Api\SurahController;
use App\Http\Controllers\Api\UserAnswerController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuestionLevelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PUBLIC
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// api surah
Route::apiResource('surahs', SurahController::class)->only(['index']);

// PROTECTED
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Product
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);

    // Wordgroup
    Route::post('wordgroups/merge', [WordGroupController::class, 'merge']);
    Route::apiResource('wordgroups', WordGroupController::class);

    // Question Level
    Route::get('question-levels/number/{levelNumber}', [QuestionLevelController::class, 'getByNumber']);
    Route::get('question-levels/{id}', [QuestionLevelController::class, 'show']);

    // Question 
    Route::get('questions', [QuestionController::class, 'index']);
    Route::get('questions/level/{level}', [QuestionController::class, 'getByLevel']);
    Route::get('questions/{id}', [QuestionController::class, 'show']);
    Route::post('questions', [QuestionController::class, 'store']);
    Route::put('questions/{id}', [QuestionController::class, 'update']);
    Route::delete('questions/{id}', [QuestionController::class, 'destroy']);

    // User Answers
    Route::post('user-answers', [UserAnswerController::class, 'store']); // Simpan jawaban
    Route::get('user-answers', [UserAnswerController::class, 'index']); // List jawaban user dengan filter
    Route::get('user-answers/{wordId}', [UserAnswerController::class, 'show']); // Detail jawaban untuk soal tertentu
    Route::get('user-answers/stats', [UserAnswerController::class, 'stats']); // Statistik jawaban user
});
