<?php

use App\Http\Controllers\Api\WordGroupController;
use App\Http\Controllers\Api\SurahController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('wordgroups/merge', [WordGroupController::class, 'merge']);
    Route::apiResource('wordgroups', WordGroupController::class);
});
