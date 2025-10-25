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

    Route::resource('surahs', SurahController::class);
    Route::resource('verses', VerseController::class);
    Route::resource('wordgroups', WordGroupController::class);

    // Administrator Only
    Route::middleware(['roles:administrator'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::get('/example', function () {
            return view('pages.Template.forms-advanced-form');
        })->name('page.templatepage');; // Template Page
    });

    // Administrator & Operator Only
    Route::middleware(['roles:administrator,operator'])->group(function () {
        Route::get('/grouping', [WordGroupController::class, 'indexByVerse'])->name('wordgroups.indexByVerse');
        Route::post('/wordgroups/save', [WordGroupController::class, 'save'])->name('wordgroups.save');
        Route::post('/wordgroups/merge', [WordGroupController::class, 'merge'])
            ->name('wordgroups.merge');
        Route::post('/wordgroups/split', [WordGroupController::class, 'split'])
            ->name('wordgroups.split');
        Route::post('/wordgroups/complete', [WordGroupController::class, 'completeOrderNumber'])
            ->name('wordgroups.complete');
    });
});
