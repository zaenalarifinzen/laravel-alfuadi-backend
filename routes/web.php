<?php

use App\Http\Controllers\Admin\AnalysisSettingController;
use App\Http\Controllers\KalimatController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KedudukanController;
use App\Http\Controllers\ExerciseLevelController;
use App\Http\Controllers\NahwuDataController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SurahController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\WordGroupController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\SettingsController;
use App\Models\Surah;
// use App\Models\UserAnswer;
use App\Models\Verse;
use App\Models\Word;
use App\Models\WordGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::resource('surahs', SurahController::class);
Route::resource('verses', VerseController::class);

// Custom API routes
Route::get('/wordgroups/get/{id?}', [WordGroupController::class, 'getWordGroup'])->name('wordgroups.get');
Route::get('/words/get/{id}', [WordController::class, 'getWord'])->name('words.get');

Route::middleware(['auth', 'verified'])->group(function () {

    // Administrator Only
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::middleware(['roles:administrator'])->group(function () {
            Route::resource('users', UserController::class);
            Route::post('users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
            Route::resource('products', ProductController::class);

            Route::prefix('skema-nahwu')->name('skema-nahwu.')->group(function () {
                Route::get('/', [NahwuDataController::class, 'index'])->name('index');
                Route::resource('kalimat', KalimatController::class);
                Route::resource('kategori', KategoriController::class);
                Route::resource('kedudukan', KedudukanController::class);
            });

            Route::name('analysis-settings.')->group(function () {
                Route::get('/analysis-settings', [SettingsController::class, 'index'])->name('index');
                Route::post('/analysis-settings', [SettingsController::class, 'store'])->name('store');
                Route::resource('settings', SettingsController::class);
            });
        });
    });

    // Administrator & Operator Only
    Route::middleware(['roles:administrator,operator'])->group(function () {
        // Dashboard Panel
        Route::get('/dashboard', function () {
            $user = Auth::user();

            $randomVerse = Cache::remember('daily_verse', now()->endOfDay(), function () {
                return Verse::query()
                    ->with('surah')
                    ->inRandomOrder()
                    ->first();
            });

            $latestTask = null;
            $updatedAt = null;
            $latestExercise = null;

            if ($user) {
                if (in_array($user->roles, ['administrator', 'operator'], true)) {
                    $latestProgres = Word::query()
                        ->where('editor', $user->id)
                        ->latest('updated_at')
                        ->first();

                    if ($latestProgres) {
                        $wordgroup = WordGroup::query()
                            ->where('id', $latestProgres->word_group_id)
                            ->latest('updated_at')
                            ->first();

                        if ($wordgroup) {
                            $surah = Surah::query()
                                ->where('id', $wordgroup->surah_id)
                                ->first();

                            if ($surah) {
                                $latestTask = 'Surah ' . $surah->name . ' ayat ' . $wordgroup->verse_number;
                            }

                            $updatedAt = $latestProgres->updated_at;
                        }
                    }
                }
            }

            return view('pages.dashboard.dashboard', [
                'type_menu' => 'dashboard',
                'randomVerse' => $randomVerse,
                'latestTask' => $latestTask,
                'updated_at' => $updatedAt,
                'latestExercise' => $latestExercise,
            ]);
        })->name('dashboard');

        // Custom wordgroup routes
        Route::get('/wordgroups/grouping', [WordGroupController::class, 'grouping'])->name('wordgroups.grouping');
        Route::post('/wordgroups/save', [WordGroupController::class, 'save'])->name('wordgroups.save');
        Route::post('/wordgroups/multiple-update', [WordGroupController::class, 'multipleUpdate'])->name('wordgroups.multiple-update');
        Route::post('/wordgroups/merge', [WordGroupController::class, 'merge'])->name('wordgroups.merge');
        Route::post('/wordgroups/split', [WordGroupController::class, 'split'])->name('wordgroups.split');
        Route::post('/wordgroups/complete', [WordGroupController::class, 'completeOrderNumber'])->name('wordgroups.complete');

        // Dashboard exercise management
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            // Exercise Level
            Route::resource('exercise-levels', ExerciseLevelController::class);
            Route::resource('exercises', ExerciseController::class);

            // Exercise Items
            Route::get('exercises/{id}/grouping', [ExerciseController::class, 'grouping'])->name('exercises.grouping');
            Route::put('exercises/{id}/grouping', [ExerciseController::class, 'updateGrouping'])->name('exercises.grouping.update');
            Route::get('exercises/{id}/irob', [ExerciseController::class, 'irob'])->name('exercises.irob');
            Route::put('exercises/{id}/irob', [ExerciseController::class, 'updateIrob'])->name('exercises.irob.update');
            Route::post('exercises/{id}/activate', [ExerciseController::class, 'activate'])->name('exercises.activate');
            Route::post('exercises/{id}/deactivate', [ExerciseController::class, 'deactivate'])->name('exercises.deactivate');

            // Exercise Levels.
            Route::get('/exercise-level/new', [ExerciseLevelController::class, 'create'])->name('exercise-level.create');
            Route::post('/exercise-level/new', [ExerciseLevelController::class, 'store'])->name('exercise-level.store');
            Route::post('/exercise-level/{id}/activate', [ExerciseLevelController::class, 'activate'])->name('exercise-level.activate');
            Route::post('/exercise-level/{id}/deactivate', [ExerciseLevelController::class, 'deactivate'])->name('exercise-level.deactivate');
            Route::resource('new-exercise', ExerciseController::class);
        });


        // Custom words routes
        Route::post('words/sync', [WordController::class, 'sync'])->name('words.sync');

        // Resource routes
        Route::resource('wordgroups', WordGroupController::class);
        Route::resource('words', WordController::class);
    });

    // Administrator, Operator and User Only
    Route::middleware(['roles:administrator,operator,user'])->group(function () {
        Route::get('quran', [QuranController::class, 'index'])->name('quran.index');
        Route::get('quran/surah', [QuranController::class, 'versesOfSurah'])->name('quran.surah');

        Route::get('/metode-al-fuadi/jilid-1', function () {
            return view('pages.modul.nahwu.jilid-1', ['type_menu' => 'metode-al-fuadi']);
        })->name('metode-al-fuadi.jilid-1');

        Route::get('/exercise', [ExerciseLevelController::class, 'userIndex'])->name('exercise-level.index');
        Route::get('/exercise/get/{level}/{exerciseId?}', [ExerciseController::class, 'getExercise'])
            ->name('exercise.get');
        Route::get('/exercise/{level}/{exerciseId?}', function ($level) {
            return view('pages.exercise.analyze', ['type_menu' => $level]);
        })->name('exercise.analyze');

        // Data Nahwu Resource
        Route::get('/words/data/data-nahwu', [NahwuDataController::class, 'getAll']);
    });

    // User Profile
    Route::get('/profile', function () {
        return view('pages.users.profile', ['type_menu' => 'profile']);
    })->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Resource
    Route::resource('user-answers', UserAnswerController::class);
});
