<?php

use App\Http\Controllers\Api\WordGroupController;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::post('word_groups/merge', [WordGroupController::class, 'merge']);
    Route::apiResource('word_groups', WordGroupController::class);
});