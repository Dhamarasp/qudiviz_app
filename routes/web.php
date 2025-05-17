<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\SubLessonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    // Login
    Route::post('/login', [AuthController::class, 'login'])->name('login.in');

    // Register
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Alias untuk kemudahan akses
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::get('/register', [RegisterController::class, 'index'])->name('register.index')->middleware('guest');

/*
|--------------------------------------------------------------------------
| Profil Pengguna
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => 'auth'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::post('/remove-profile-image', [ProfileController::class, 'removeProfileImage'])->name('remove-profile-image');
    
    // Profile customization
    Route::get('/customize', [ProfileController::class, 'customize'])->name('customize');
    Route::post('/customize', [ProfileController::class, 'updateCustomization'])->name('update-customization');
    Route::post('/remove-cover-image', [ProfileController::class, 'removeCoverImage'])->name('remove-cover-image');
    
    // View other user's profile
    Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Friends System
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'friends', 'as' => 'friends.', 'middleware' => 'auth'], function () {
    Route::get('/', [FriendController::class, 'index'])->name('index');
    Route::get('/search', [FriendController::class, 'search'])->name('search');
    Route::post('/send-request/{user}', [FriendController::class, 'sendRequest'])->name('send-request');
    Route::post('/accept-request/{user}', [FriendController::class, 'acceptRequest'])->name('accept-request');
    Route::post('/reject-request/{user}', [FriendController::class, 'rejectRequest'])->name('reject-request');
    Route::post('/cancel-request/{user}', [FriendController::class, 'cancelRequest'])->name('cancel-request');
    Route::post('/remove-friend/{user}', [FriendController::class, 'removeFriend'])->name('remove-friend');
    Route::post('/block-user/{user}', [FriendController::class, 'blockUser'])->name('block-user');
    Route::post('/unblock-user/{user}', [FriendController::class, 'unblockUser'])->name('unblock-user');
    Route::get('/blocked', [FriendController::class, 'blocked'])->name('blocked');
});

/*
|--------------------------------------------------------------------------
| Pembelajaran
|--------------------------------------------------------------------------
*/
// Pelajaran Utama
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons')->middleware('auth');
Route::post('/lessons/{lesson}/start', [LessonController::class, 'startLesson'])->name('lessons.start')->middleware('auth');

// Pengaturan Bahasa
Route::get('/lessons/switch-language', [LanguageController::class, 'index'])->name('switch-language')->middleware('auth');
Route::post('/lessons/switch-language', [LanguageController::class, 'switchLanguage'])->name('switch-language.update')->middleware('auth');

// Sub Pelajaran dan Studi
Route::group(['prefix' => 'lessons', 'as' => 'lessons.', 'middleware' => 'auth'], function () {
    Route::get('/sub', [SubLessonController::class, 'index'])->name('sub');
    Route::post('/sub/{subLesson}/start', [SubLessonController::class, 'startSubLesson'])->name('sub.start');
    Route::post('/sub/{subLesson}/complete', [SubLessonController::class, 'completeSubLesson'])->name('sub.complete');

    Route::group(['prefix' => 'sub', 'as' => 'sub.'], function () {
        Route::get('/study', [StudyController::class, 'index'])->name('study');
        Route::post('/study/{subLesson}/complete', [StudyController::class, 'complete'])->name('study.complete');
    });
});

/*
|--------------------------------------------------------------------------
| Latihan dan Kuis
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'practice', 'as' => 'practice.', 'middleware' => 'auth'], function () {
    Route::get('/', [PracticeController::class, 'index'])->name('index');
    Route::post('/start', [PracticeController::class, 'startSession'])->name('start');
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
    Route::post('/quiz/submit-answer', [QuizController::class, 'submitAnswer'])->name('submit-answer');
    Route::post('/quiz/skip-question', [QuizController::class, 'skipQuestion'])->name('skip-question');
    
    // Fix: Ensure parameter names are consistent
    Route::get('/complete/{session}', [PracticeController::class, 'completeSession'])->name('complete');
    Route::get('/results/{session}', [PracticeController::class, 'showResults'])->name('results');
});

/*
|--------------------------------------------------------------------------
| Fitur Sosial
|--------------------------------------------------------------------------
*/
// Papan Peringkat
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard')->middleware('auth');



// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Languages Management
    Route::get('/languages', [AdminController::class, 'languages'])->name('admin.languages');
    Route::get('/languages/create', [AdminController::class, 'createLanguage'])->name('admin.languages.create');
    Route::post('/languages', [AdminController::class, 'storeLanguage'])->name('admin.languages.store');
    Route::get('/languages/{id}/edit', [AdminController::class, 'editLanguage'])->name('admin.languages.edit');
    Route::put('/languages/{id}', [AdminController::class, 'updateLanguage'])->name('admin.languages.update');
    Route::delete('/languages/{id}', [AdminController::class, 'deleteLanguage'])->name('admin.languages.delete');
    
    // Units Management
    Route::get('/units', [AdminController::class, 'units'])->name('admin.units');
    Route::get('/units/create', [AdminController::class, 'createUnit'])->name('admin.units.create');
    Route::post('/units', [AdminController::class, 'storeUnit'])->name('admin.units.store');
    Route::get('/units/{id}/edit', [AdminController::class, 'editUnit'])->name('admin.units.edit');
    Route::put('/units/{id}', [AdminController::class, 'updateUnit'])->name('admin.units.update');
    Route::delete('/units/{id}', [AdminController::class, 'deleteUnit'])->name('admin.units.delete');
    
    // Lessons Management
    Route::get('/lessons', [AdminController::class, 'lessons'])->name('admin.lessons');
    Route::get('/lessons/create', [AdminController::class, 'createLesson'])->name('admin.lessons.create');
    Route::post('/lessons', [AdminController::class, 'storeLesson'])->name('admin.lessons.store');
    Route::get('/lessons/{id}/edit', [AdminController::class, 'editLesson'])->name('admin.lessons.edit');
    Route::put('/lessons/{id}', [AdminController::class, 'updateLesson'])->name('admin.lessons.update');
    Route::delete('/lessons/{id}', [AdminController::class, 'deleteLesson'])->name('admin.lessons.delete');
    
    // SubLessons Management
    Route::get('/sub-lessons', [AdminController::class, 'subLessons'])->name('admin.sub-lessons');
    Route::get('/sub-lessons/create', [AdminController::class, 'createSubLesson'])->name('admin.sub-lessons.create');
    Route::post('/sub-lessons', [AdminController::class, 'storeSubLesson'])->name('admin.sub-lessons.store');
    Route::get('/sub-lessons/{id}/edit', [AdminController::class, 'editSubLesson'])->name('admin.sub-lessons.edit');
    Route::put('/sub-lessons/{id}', [AdminController::class, 'updateSubLesson'])->name('admin.sub-lessons.update');
    Route::delete('/sub-lessons/{id}', [AdminController::class, 'deleteSubLesson'])->name('admin.sub-lessons.delete');
    
    // Quiz Questions Management
    Route::get('/quiz-questions', [AdminController::class, 'quizQuestions'])->name('admin.quiz-questions');
    Route::get('/quiz-questions/create', [AdminController::class, 'createQuizQuestion'])->name('admin.quiz-questions.create');
    Route::post('/quiz-questions', [AdminController::class, 'storeQuizQuestion'])->name('admin.quiz-questions.store');
    Route::get('/quiz-questions/{id}/edit', [AdminController::class, 'editQuizQuestion'])->name('admin.quiz-questions.edit');
    Route::put('/quiz-questions/{id}', [AdminController::class, 'updateQuizQuestion'])->name('admin.quiz-questions.update');
    Route::delete('/quiz-questions/{id}', [AdminController::class, 'deleteQuizQuestion'])->name('admin.quiz-questions.delete');
    
    // Practice Categories Management
    Route::get('/practice-categories', [AdminController::class, 'practiceCategories'])->name('admin.practice-categories');
    Route::get('/practice-categories/create', [AdminController::class, 'createPracticeCategory'])->name('admin.practice-categories.create');
    Route::post('/practice-categories', [AdminController::class, 'storePracticeCategory'])->name('admin.practice-categories.store');
    Route::get('/practice-categories/{id}/edit', [AdminController::class, 'editPracticeCategory'])->name('admin.practice-categories.edit');
    Route::put('/practice-categories/{id}', [AdminController::class, 'updatePracticeCategory'])->name('admin.practice-categories.update');
    Route::delete('/practice-categories/{id}', [AdminController::class, 'deletePracticeCategory'])->name('admin.practice-categories.delete');

    // SubLesson Contents Management
    Route::get('/sub-lesson-contents/{subLessonId?}', [AdminController::class, 'subLessonContents'])->name('admin.sub-lesson-contents');
    Route::get('/sub-lesson-contents/{subLessonId?}/create', [AdminController::class, 'createSubLessonContent'])->name('admin.sub-lesson-contents.create');
    Route::post('/sub-lesson-contents', [AdminController::class, 'storeSubLessonContent'])->name('admin.sub-lesson-contents.store');
    Route::get('/sub-lesson-contents/edit/{id}', [AdminController::class, 'editSubLessonContent'])->name('admin.sub-lesson-contents.edit');
    Route::put('/sub-lesson-contents/{id}', [AdminController::class, 'updateSubLessonContent'])->name('admin.sub-lesson-contents.update');
    Route::delete('/sub-lesson-contents/{id}', [AdminController::class, 'deleteSubLessonContent'])->name('admin.sub-lesson-contents.delete');
});