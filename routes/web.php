<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirect root to login
Route::get('/', function() {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister']);

// Protected routes (require authentication)
Route::middleware('web')->group(function () {
    Route::get('/homepage', [AuthController::class, 'showHomepage'])->name('homepage');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::get('/orders', [AuthController::class, 'showOrders'])->name('orders');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Remove duplicate routes - these are causing conflicts
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::match(['get', 'post'], '/register', function () {
//     return view('register');
// });

// Route::match(['get', 'post'], '/login', function () {
//     return view('login');
// });

// Route::get('/homepage', function () {
//     return view('homepage');
// });

// Route::get('/logout', function () {
//     return view('logout');
// });