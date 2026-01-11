<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PaymentController;
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


    // API Routes - 4 ROUTE SAJA
    Route::middleware([CheckAuthenticated::class])->prefix('api')->group(function () {
        // 1. GET - Ambil data (semua atau 1)
        Route::post('/prescriptions/get', [PrescriptionController::class, 'get']);
        
        // 2. ADD - Tambah baru
        Route::post('/prescriptions/add', [PrescriptionController::class, 'add']);
        
        // 3. UPDATE - Update
        Route::post('/prescriptions/update', [PrescriptionController::class, 'update']);
        
        // 4. DELETE - Hapus
        Route::post('/prescriptions/delete', [PrescriptionController::class, 'delete']);
    });

        
    // API Routes for payments
    Route::middleware([CheckAuthenticated::class])->prefix('api')->group(function () {
        // GET - Ambil data pembayaran
        Route::post('/payments/get', [PaymentController::class, 'get']);
        
        // UPDATE - Proses pembayaran
        Route::post('/payments/update', [PaymentController::class, 'update']);
        
        // CANCEL - Batalkan pembayaran
        Route::post('/payments/cancel', [PaymentController::class, 'cancel']);
        
        // STATISTICS - Statistik pembayaran
        Route::get('/payments/statistics', [PaymentController::class, 'statistics']);
        
        // INVOICE - Cetak invoice PDF
        Route::get('/payments/invoice/{id}/pdf', [PaymentController::class, 'generateInvoice'])->name('payments.invoice');
    });    

});