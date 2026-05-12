<?php

use App\Http\Controllers\Api\WordGroupController;
use App\Http\Controllers\Api\SurahController;
use App\Http\Controllers\Api\UserAnswerController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuestionLevelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// post login
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');

// api resource product
Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class)->middleware('auth:sanctum');

// api surah
Route::apiResource('surahs', SurahController::class)->only(['index']);

// Public endpoints untuk questions - bisa diakses tanpa auth untuk view soal
Route::get('questions', [QuestionController::class, 'index']);
Route::get('questions/{id}', [QuestionController::class, 'show']);
Route::get('questions/level/{level}', [QuestionController::class, 'getByLevel']);
// Question management endpoints - hanya untuk admin/pembuat soal
Route::post('questions', [QuestionController::class, 'store']);
Route::put('questions/{id}', [QuestionController::class, 'update']);
Route::delete('questions/{id}', [QuestionController::class, 'destroy']);

// Question Level management endpoints
Route::apiResource('question-levels', QuestionLevelController::class);

// User Answers endpoints
Route::post('user-answers', [UserAnswerController::class, 'store']); // Simpan jawaban
Route::get('user-answers', [UserAnswerController::class, 'index']); // List jawaban user dengan filter
Route::get('question-levels/{id}', [QuestionLevelController::class, 'show']);
Route::get('question-levels/number/{levelNumber}', [QuestionLevelController::class, 'getByNumber']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('wordgroups/merge', [WordGroupController::class, 'merge']);
    Route::apiResource('wordgroups', WordGroupController::class);

    // User Answers endpoints
    Route::post('user-answers', [UserAnswerController::class, 'store']); // Simpan jawaban
    Route::get('user-answers', [UserAnswerController::class, 'index']); // List jawaban user dengan filter
    Route::get('user-answers/{wordId}', [UserAnswerController::class, 'show']); // Detail jawaban untuk soal tertentu
    Route::get('user-answers/stats', [UserAnswerController::class, 'stats']); // Statistik jawaban user
});
