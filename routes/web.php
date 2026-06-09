<?php

use App\Http\Controllers\QuestionLevelController;
use App\Http\Controllers\NahwuDataController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SurahController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\WordGroupController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

// Public routes
Route::get('/home', function () {
    return view('pages.dashboard', ['type_menu' => 'dashboard']);
})->middleware(['auth'])->name('home');
Route::resource('surahs', SurahController::class);
Route::resource('verses', VerseController::class);

// Custom API routes
Route::get('/wordgroups/get/{id?}', [WordGroupController::class, 'getWordGroup'])->name('wordgroups.get');
Route::get('/words/get/{id}', [WordController::class, 'getWord'])->name('words.get');

Route::middleware(['auth', 'verified'])->group(function () {

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

        // Exercise
        Route::get('/exercise/new', [QuestionLevelController::class, 'create'])->name('exercise-level.create');
        Route::post('/exercise/new', [QuestionLevelController::class, 'store'])->name('exercise-level.store');

        // Custom words routes
        Route::post('words/sync', [WordController::class, 'sync'])->name('words.sync');

        // Resource routes
        Route::resource('wordgroups', WordGroupController::class);
        Route::resource('words', WordController::class);
    });

    // Administrator, Operator and User Only
    Route::middleware(['roles:administrator,operator,user'])->group(function () {
        Route::get('/metode-al-fuadi/jilid-1', function () {
            return view('pages.modul.nahwu.jilid-1', ['type_menu' => 'metode-al-fuadi']);
        })->name('metode-al-fuadi.jilid-1');
        Route::get('/exercise', [QuestionLevelController::class, 'index'])->name('exercise-level.index');
        Route::get('/exercise/alquran', function () {
            return view('pages.exercise.question.analyze', ['type_menu' => '']);
        })->name('exercise.alquran');
        Route::get('/exercise/analysis/{verseId?}', [QuestionController::class, 'getAnalysisQuestion'])
            ->name('exercise.analysis');

        // Data Nahwu Resource
        Route::get('/words/data/data-nahwu', [NahwuDataController::class, 'index']);
    });

    // User Profile
    Route::get('/profile', function () {
        return view('pages.users.profile', ['type_menu' => 'profile']);
    })->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Resource
    Route::resource('user-answers', UserAnswerController::class);
});
