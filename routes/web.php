<?php

use App\Http\Controllers\NahwuDataController;
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

// Public routes
Route::get('/home', function () {
    return view('pages.dashboard', ['type_menu' => 'dashboard']);
})->name('home');
// Custom API routes
Route::get('/wordgroups/get/{id?}', [WordGroupController::class, 'getWordGroup'])->name('wordgroups.get');
Route::get('/words/get/{id}', [WordController::class, 'getWord'])->name('words.get');

Route::middleware(['auth'])->group(function () {

    // Administrator Only
    Route::middleware(['roles:administrator'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class);
        Route::get('/skema-nahwu', function () {
            return view('pages.Template.develop.skema-nahwu', ['type_menu' => '']);
        })->name('page.skema-nahwu'); // Template Page
        Route::get('/example', function () {
            return view('pages.users.profile', ['type_menu' => '']);
        })->name('page.templatepage'); // Template Page
    });

    // Administrator & Operator Only
    Route::middleware(['roles:administrator,operator'])->group(function () {
        // Custom wordgroup routes
        Route::get('/wordgroups/grouping', [WordGroupController::class, 'grouping'])->name('wordgroups.grouping');
        Route::post('/wordgroups/save', [WordGroupController::class, 'save'])->name('wordgroups.save');
        Route::post('/wordgroups/multiple-update', [WordGroupController::class, 'multipleUpdate'])->name('wordgroups.multiple-update');
        Route::post('/wordgroups/merge', [WordGroupController::class, 'merge'])->name('wordgroups.merge');
        Route::post('/wordgroups/split', [WordGroupController::class, 'split'])->name('wordgroups.split');
        Route::post('/wordgroups/complete', [WordGroupController::class, 'completeOrderNumber'])->name('wordgroups.complete');

        // Custom words routes
        Route::post('words/sync', [WordController::class, 'sync'])->name('words.sync');
        Route::get('/words/data/data-nahwu', [NahwuDataController::class, 'index']);

        // Resource routes
        Route::resource('wordgroups', WordGroupController::class);
        Route::resource('words', WordController::class);
    });

    // Administrator, Operator and User Only
    Route::middleware(['roles:administrator,operator,user'])->group(function () {
        Route::get('/metode-al-fuadi/jilid-1', function () {
            return view('pages.modul.nahwu.jilid-1', ['type_menu' => 'metode-al-fuadi']);
        })->name('metode-al-fuadi.jilid-1');
        Route::get('/metode-al-fuadi/exercise', function () {
            return view('pages.modul.exercise.index', ['type_menu' => '']);
        })->name('metode-al-fuadi.exercise');
        Route::get('/metode-al-fuadi/exercise/quran', function () {
            return view('pages.modul.exercise.exercise', ['type_menu' => '']);
        })->name('metode-al-fuadi.exercise.quran');
    });

    // Resource
    Route::resource('surahs', SurahController::class);
    Route::resource('verses', VerseController::class);
});
