<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\AboutUsController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// About Us Routes
Route::get('/about-us', [AboutUsController::class, 'index'])->name('about.us');
Route::post('/about-us/upload-image', [AboutUsController::class, 'uploadImage'])->name('about.us.upload.image');

// Remove default auth routes since we only want admin auth

// Admin Routes
Route::prefix('admin')->group(function() {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    
    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function() {
        Route::get('/dashboard', function() {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});
