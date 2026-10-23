<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\KalimatController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KedudukanController;
use App\Http\Controllers\ExerciseLevelController;
use App\Http\Controllers\NahwuDataController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SurahController;
use App\Http\Controllers\ExerciseSubmissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\WordGroupController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\User\LearningController;
use App\Http\Controllers\CourseController as UserCourseController;
use App\Http\Controllers\User\QuizController as UserQuizController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('homepage');
})->name('home');
Route::get('/courses', [UserCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [UserCourseController::class, 'show'])->name('courses.show');
Route::get('/enrollments', function () {
    return view('pages.course.enrollment', ['type_menu' => 'enrollments']);
})->name('enrollments');
Route::get('quran', [QuranController::class, 'index'])->name('quran.index');
Route::get('quran/surah', [QuranController::class, 'versesOfSurah'])->name('quran.surah');
Route::get('metode-alfuadi', function () {
    return view('pages.metode-alfuadi.introducing');
})->name('metode-alfuadi');

Route::resource('surahs', SurahController::class);
Route::resource('verses', VerseController::class);


// Custom API routes
Route::get('/wordgroups/get/{id?}', [WordGroupController::class, 'getWordGroup'])->name('wordgroups.get');
Route::get('/words/get/{id}', [WordController::class, 'getWord'])->name('words.get');

// Auth Middleware
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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Custom wordgroup routes
        Route::get('/wordgroups/grouping', [WordGroupController::class, 'grouping'])->name('wordgroups.grouping');
        Route::post('/wordgroups/save', [WordGroupController::class, 'save'])->name('wordgroups.save');
        Route::post('/wordgroups/multiple-update', [WordGroupController::class, 'multipleUpdate'])->name('wordgroups.multiple-update');
        Route::post('/wordgroups/merge', [WordGroupController::class, 'merge'])->name('wordgroups.merge');
        Route::post('/wordgroups/split', [WordGroupController::class, 'split'])->name('wordgroups.split');
        Route::post('/wordgroups/complete', [WordGroupController::class, 'completeOrderNumber'])->name('wordgroups.complete');

        // Dashboard
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            // Categories
            Route::resource('/categories', CategoryController::class);

            // Course
            Route::resource('/courses', CourseController::class)->except('show');
            Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
            Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
            Route::get('/courses/{course}/student', [CourseController::class, 'students'])->name('courses.students');
            Route::get('/courses/{course}/review', [CourseController::class, 'reviews'])->name('courses.reviews');

            // Transactions (Pesanan Kursus)
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
            Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
            Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
            Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');


            // Lessons
            Route::get('/courses/{course}/lessons', [LessonController::class, 'index'])->name('lessons.index');
            Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
            Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
            Route::get('/courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
            Route::put('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
            Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

            // Quizzes
            Route::get('/courses/{course}/lessons/{lesson}/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
            Route::get('/courses/{course}/lessons/{lesson}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
            Route::post('/courses/{course}/lessons/{lesson}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
            Route::get('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
            Route::put('/courses/{course}/lessons/{lesson}/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
            Route::delete('/courses/{course}/lessons/{lesson}/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

            // Questions
            // Route::get('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('questions.index');
            // Route::get('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
            // Route::post('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
            // Route::get('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
            // Route::put('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
            // Route::delete('/courses/{course}/lessons/{lesson}/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

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

        Route::get('/exercise', [ExerciseLevelController::class, 'userIndex'])->name('exercise-level.index');
        Route::get('/exercise/list/{level}', [ExerciseController::class, 'getExerciseList'])->name('exercise.list');
        Route::get('/exercise/get/{level}/{exerciseId?}', [ExerciseController::class, 'getExercise'])
            ->name('exercise.get');
        Route::get('/exercise/{level}/{exerciseId?}', function ($level) {
            return view('pages.exercise.analyze', ['type_menu' => $level]);
        })->name('exercise.analyze');

        // Data Nahwu Resource
        Route::get('/words/data/data-nahwu', [NahwuDataController::class, 'getAll']);

        // Course
        Route::get('/courses/{course:slug}/checkout', [UserCourseController::class, 'checkout'])->name('courses.checkout');
        Route::post('/courses/{course:slug}/checkout', [UserCourseController::class, 'processCheckout'])->name('courses.checkout.process');
    });

    // User
    Route::get('/profile', function () {
        return view('pages.users.profile', ['type_menu' => 'profile']);
    })->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Learning
    Route::get('/courses/{course:slug}/{lesson?}', [LearningController::class, 'show'])->name('learn');

    // Quiz
    Route::get('/courses/{course:slug}/lessons/{lesson}/quizzes/{quiz?}', [UserQuizController::class, 'show'])->name('quiz.show');
    Route::post('/courses/{course:slug}/lessons/{lesson}/quizzes/{quiz?}', [UserQuizController::class, 'submit'])->name('quiz.submit');

    // Review
    Route::post('/courses/{course}/review', [UserReviewController::class, 'store'])->name('review.store');

    // Resource
    Route::resource('user-answers', ExerciseSubmissionController::class);
});
