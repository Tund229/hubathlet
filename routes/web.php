<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ClubSettingsController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\PlayerController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Routes de réinitialisation de mot de passe
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('guest');

// Routes protégées (authentifié)
Route::middleware(['auth', 'role.redirect'])->group(function () {
    // Dashboard Admin (Owner/Admin/Moderator)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Espace Joueur
    Route::prefix('player')->name('player.')->group(function () {
        Route::get('/', [PlayerController::class, 'dashboard'])->name('dashboard');
        Route::get('/schedule', [PlayerController::class, 'schedule'])->name('schedule');
        Route::get('/stats', [PlayerController::class, 'stats'])->name('stats');
        Route::get('/profile', [PlayerController::class, 'profile'])->name('profile');
        Route::get('/settings', [PlayerController::class, 'settings'])->name('settings');
    });
    
    // Dashboard Parent (placeholder)
    Route::prefix('parent')->name('parent.')->group(function () {
        Route::get('/', function () {
            return view('parent.dashboard');
        })->name('dashboard');
        Route::get('/children', function () {
            return view('parent.children');
        })->name('children');
        Route::get('/schedule', function () {
            return view('parent.schedule');
        })->name('schedule');
        Route::get('/profile', function () {
            return view('parent.profile');
        })->name('profile');
    });
    
    // Membres
    Route::resource('members', MemberController::class);
    Route::patch('/members/{member}/status', [MemberController::class, 'updateStatus'])->name('members.status');
    
    // Planning
    Route::resource('planning', PlanningController::class);
    Route::post('/planning/{planning}/duplicate', [PlanningController::class, 'duplicate'])->name('planning.duplicate');
    Route::post('/planning/{planning}/participants', [PlanningController::class, 'addParticipant'])->name('planning.add-participant');
    Route::delete('/planning/{planning}/participants/{user}', [PlanningController::class, 'removeParticipant'])->name('planning.remove-participant');
    Route::patch('/planning/{planning}/participants/{user}/attendance', [PlanningController::class, 'updateAttendance'])->name('planning.update-attendance');
    
    // Statistiques
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/members', [StatisticsController::class, 'members'])->name('statistics.members');
    Route::get('/statistics/members/{member}', [StatisticsController::class, 'member'])->name('statistics.member');
    Route::get('/statistics/trainings', [StatisticsController::class, 'trainings'])->name('statistics.trainings');
    
    // Club Settings
    Route::prefix('club')->name('club.')->group(function () {
        Route::get('/', [ClubSettingsController::class, 'index'])->name('index');
        Route::get('/edit', [ClubSettingsController::class, 'edit'])->name('edit');
        Route::put('/update', [ClubSettingsController::class, 'update'])->name('update');
        Route::delete('/logo', [ClubSettingsController::class, 'deleteLogo'])->name('delete-logo');
        Route::get('/customization', [ClubSettingsController::class, 'customization'])->name('customization');
        Route::put('/customization', [ClubSettingsController::class, 'updateCustomization'])->name('customization.update');
        Route::get('/settings', [ClubSettingsController::class, 'settings'])->name('settings');
        Route::put('/settings', [ClubSettingsController::class, 'updateSettings'])->name('settings.update');
    });
    
    // Espace Coach
    Route::prefix('coach')->name('coach.')->group(function () {
        Route::get('/', [CoachController::class, 'dashboard'])->name('dashboard');
        Route::get('/trainings', [CoachController::class, 'trainings'])->name('trainings');
        Route::get('/trainings/{training}/attendance', [CoachController::class, 'attendance'])->name('attendance');
        Route::patch('/trainings/{training}/participants/{participant}/attendance', [CoachController::class, 'updateAttendance'])->name('update-attendance');
        Route::post('/trainings/{training}/participants', [CoachController::class, 'addParticipant'])->name('add-participant');
        Route::delete('/trainings/{training}/participants/{participant}', [CoachController::class, 'removeParticipant'])->name('remove-participant');
        Route::post('/trainings/{training}/mark-all-present', [CoachController::class, 'markAllPresent'])->name('mark-all-present');
        Route::post('/trainings/{training}/complete', [CoachController::class, 'completeTraining'])->name('complete-training');
        Route::get('/player-stats', [CoachController::class, 'playerStats'])->name('player-stats');
    });
});
