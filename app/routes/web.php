<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\SupervisedUserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public event routes
Route::get('/e/{slug}', [PublicEventController::class, 'show'])->name('events.public.show');
Route::post('/e/{slug}/register', [PublicEventController::class, 'register'])->name('events.public.register');

// Protected routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/personal-data', [ProfileController::class, 'updatePersonalData'])->name('profile.update-personal-data');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User preferences route
    Route::post('/user-preferences', [UserPreferenceController::class, 'save'])->name('user-preferences.save');
    
    // Events routes
    Route::resource('events', EventController::class);
    
    // Participants routes
    Route::get('/events/{event}/participants', [ParticipantController::class, 'index'])->name('events.participants.index');
    Route::get('/events/{event}/participants/create', [ParticipantController::class, 'create'])->name('events.participants.create');
    Route::post('/events/{event}/participants', [ParticipantController::class, 'store'])->name('events.participants.store');
    
    // Export participants list
    Route::get('/events/{event}/participants/export', [ParticipantController::class, 'export'])->name('events.participants.export');
    
    Route::get('/events/{event}/participants/{participant}', [ParticipantController::class, 'showFromEvent'])->name('events.participants.show');
    Route::delete('/events/{event}/participants/{participant}', [ParticipantController::class, 'destroy'])->name('events.participants.destroy');
    
    // Record participant attendance
    Route::post('/events/{event}/participants/{participant}/attendance', [ParticipantController::class, 'recordAttendance'])->name('events.participants.attendance');

    // Global Participants routes (listado general de participantes)
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{participant}', [ParticipantController::class, 'show'])->name('participants.show');

    // Supervised Users routes
    Route::resource('supervised-users', SupervisedUserController::class);
    Route::patch('/supervised-users/{supervisedUser}/password', [SupervisedUserController::class, 'updatePassword'])->name('supervised-users.update-password');
    Route::get('/supervised-users/{supervisedUser}/events', [SupervisedUserController::class, 'events'])->name('supervised-users.events');
    Route::get('/supervised-users/{supervisedUser}/get-password', [SupervisedUserController::class, 'getPassword'])->name('supervised-users.get-password');

    // Statistics routes
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::post('/statistics/download-excel', [StatisticsController::class, 'downloadExcel'])->name('statistics.download-excel');
    Route::post('/statistics/download-pdf', [StatisticsController::class, 'downloadPdf'])->name('statistics.download-pdf');
    Route::get('/statistics/clear-cache', [StatisticsController::class, 'clearCache'])->name('statistics.clear-cache');

    // API para filtros dinámicos
    Route::get('/api/events-by-category/{category?}', [StatisticsController::class, 'getEventsByApi']);
    Route::get('/api/events-by-organization/{organization?}', [StatisticsController::class, 'getEventsByOrganizationApi']);
});

require __DIR__.'/auth.php';
