<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HallController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Public Facing Routes ---
Route::get('/', [StudentRegistrationController::class, 'index'])->name('welcome');
Route::post('/check-in', [StudentRegistrationController::class, 'register'])->name('check-in');

// --- Authentication Routes (Login, Logout, etc.) ---
Auth::routes(['register' => false]); // Disable registration route

// --- Authenticated Dashboard Routes ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/import', [DashboardController::class, 'import'])->name('dashboard.import')->middleware('can:manage-dashboard');
    Route::post('/dashboard/student/{student}', [DashboardController::class, 'updateStudent'])->name('student.update')->middleware('can:manage-dashboard');
    Route::post('/dashboard/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('can:manage-dashboard');
    Route::post('/dashboard/drop-students', [DashboardController::class, 'dropStudents'])->name('dashboard.drop')->middleware('can:manage-dashboard');
    
    // Hall Management Routes
    Route::post('/halls', [HallController::class, 'store'])->name('halls.store')->middleware('can:manage-dashboard');
    Route::post('/halls/{hall}', [HallController::class, 'update'])->name('halls.update')->middleware('can:manage-dashboard');
    Route::post('/halls/{hall}/delete', [HallController::class, 'destroy'])->name('halls.destroy')->middleware('can:manage-dashboard');

    // Prizes Route
    Route::get('/prizes', [DashboardController::class, 'prizes'])->name('prizes');
    Route::post('/prizes/draw', [DashboardController::class, 'draw'])->name('prizes.draw');
});

