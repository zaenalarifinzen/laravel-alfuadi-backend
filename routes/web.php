<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SurahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\WordGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth'])->group(function () {

    // Administrator Only
    Route::middleware(['roles:administrator'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::get('/example', function () {
            return view('pages.Template.forms-advanced-form', ['type_menu' => '']);
        })->name('page.templatepage');; // Template Page
    });

    // Administrator & Operator Only
    Route::middleware(['roles:administrator,operator'])->group(function () {
        Route::get('/wordgroups/grouping', [WordGroupController::class, 'grouping'])->name('wordgroups.grouping');
        Route::post('/wordgroups/save', [WordGroupController::class, 'save'])->name('wordgroups.save');
        Route::post('/wordgroups/multiple-update', [WordGroupController::class, 'multipleUpdate'])->name('wordgroups.multiple-update');
        Route::post('/wordgroups/merge', [WordGroupController::class, 'merge'])->name('wordgroups.merge');
        Route::post('/wordgroups/split', [WordGroupController::class, 'split'])->name('wordgroups.split');
        Route::post('/wordgroups/complete', [WordGroupController::class, 'completeOrderNumber'])->name('wordgroups.complete');

        Route::get('/words/get/{id}', [WordController::class, 'getWord'])->name('words.get');
        Route::post('words/sync', [WordController::class, 'sync'])->name('words.sync');

        Route::get('/wordgroups/get/{id?}', [WordGroupController::class, 'getWordGroup'])->name('wordgroups.get');
    });

    // All User
    Route::get('/home', function () {
        return view('pages.dashboard', ['type_menu' => 'dashboard']);
    })->name('home');

    Route::resource('surahs', SurahController::class);
    Route::resource('verses', VerseController::class);
    Route::resource('wordgroups', WordGroupController::class);
    Route::resource('words', WordController::class);
});
