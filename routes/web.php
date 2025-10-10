<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SurahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\WordGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('pages.dashboard');
    })->name('home');

    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('surahs', SurahController::class);
    Route::resource('verses', VerseController::class);
    Route::resource('wordgroups', WordGroupController::class);
    Route::get('/grouping', [WordGroupController::class, 'indexByVerse'])->name('wordgroups.indexByVerse');
    Route::post('/word_groups/merge', [WordGroupController::class, 'merge'])
        ->name('word_groups.merge');
});
