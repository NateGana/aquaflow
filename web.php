<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\SafetyController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TechniqueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', SearchController::class)->name('search');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'form'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/academy', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/academy/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
Route::get('/techniques', [TechniqueController::class, 'index'])->name('techniques.index');
Route::get('/techniques/{technique}', [TechniqueController::class, 'show'])->name('techniques.show');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
Route::get('/water-safety', [SafetyController::class, 'index'])->name('safety.index');
Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/academy/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    Route::delete('/academy/{lesson}/complete', [LessonController::class, 'reopen'])->name('lessons.reopen');

    Route::post('/programs/{program}/enroll', [ProgramController::class, 'enroll'])->name('programs.enroll');
    Route::post('/programs/{program}/exercises/{exercise}/toggle', [ProgramController::class, 'toggle'])->name('programs.toggle');

    Route::resource('tracker', RecordController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['tracker' => 'record'])
        ->names('records');

    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::post('/challenges/{challenge}/join', [ChallengeController::class, 'join'])->name('challenges.join');

    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/{category}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/attempts/{attempt}', [QuizController::class, 'attempt'])->name('quiz.attempt');
    Route::post('/quiz/attempts/{attempt}/answer', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::post('/quiz/attempts/{attempt}/finish', [QuizController::class, 'finish'])->name('quiz.finish');
    Route::get('/quiz/attempts/{attempt}/result', [QuizController::class, 'result'])->name('quiz.result');

    Route::resource('schedule', ScheduleController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::patch('/schedule/{schedule}/complete', [ScheduleController::class, 'complete'])->name('schedule.complete');

    Route::get('/achievements', AchievementController::class)->name('achievements.index');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'password'])->name('settings.password');

    Route::post('/notifications/read', [NotificationController::class, 'readAll'])->name('notifications.read');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'admin'])->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');

    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('lessons', Admin\LessonController::class)->except('show');
    Route::resource('techniques', Admin\TechniqueController::class)->except('show');
    Route::resource('programs', Admin\ProgramController::class)->except('show');
    Route::post('/programs/{program}/exercises', [Admin\ProgramExerciseController::class, 'store'])->name('programs.exercises.store');
    Route::patch('/programs/{program}/exercises/{exercise}', [Admin\ProgramExerciseController::class, 'update'])->name('programs.exercises.update');
    Route::delete('/programs/{program}/exercises/{exercise}', [Admin\ProgramExerciseController::class, 'destroy'])->name('programs.exercises.destroy');
    Route::resource('quiz-questions', Admin\QuizQuestionController::class)->except('show');
    Route::resource('challenges', Admin\ChallengeController::class);
    Route::resource('safety-guides', Admin\SafetyGuideController::class)->except('show');
    Route::resource('equipment', Admin\EquipmentGuideController::class)->except('show');
});
