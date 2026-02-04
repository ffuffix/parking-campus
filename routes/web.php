<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ParkingSpotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Dashboard routes with authentication logic
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User routes
    Route::middleware(['user'])->group(function () {
        Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user');
        Route::resource('vehicles', VehicleController::class);
        Route::resource('reservations', ReservationController::class);
    });
    
    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::get('/dashboard/admin/parking-spots', [ParkingSpotController::class, 'index'])->name('admin.parking-spots');
        Route::get('/dashboard/admin/reservations', [ReservationController::class, 'adminIndex'])->name('admin.reservations');
    });
});

require __DIR__ . '/auth.php';