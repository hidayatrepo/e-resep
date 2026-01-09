<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckAuthenticated;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login routes (public)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes (protected dengan middleware)
Route::middleware([CheckAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Patients routes
    Route::get('/patients', function () {
        return view('patients.index');
    })->name('patients.index');
    
    // Payments routes
    Route::get('/payments', function () {
        return view('payments.index');
    })->name('payments.index');
    
    // Reports routes
    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');
    
    // Settings routes
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
    
    // Prescriptions routes
    Route::get('/prescriptions', function () {
        return view('prescriptions.index');
    })->name('prescriptions.index');
    
    // Medications routes
    Route::get('/medications', function () {
        return view('medications.index');
    })->name('medications.index');
});