<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\ProductController;

// Redirect root to login
Route::get('/', function() {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');

// Protected pages
Route::get('/homepage', [AuthController::class, 'showHomepage'])->name('homepage');

// Arahkan search ke ProductController@index (bukan ke AuthController)
Route::get('/searchfilter', [ProductController::class, 'index'])->name('searchfilter');

Route::get('/cart', [AuthController::class, 'showCart'])->name('cart');
Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
Route::get('/orders', [AuthController::class, 'showOrders'])->name('orders');
Route::get('/checkout', [AuthController::class, 'showCheckout'])->name('checkout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/alamat', function () { return view('alamat'); });
Route::get('/alamat', [AlamatController::class, 'showForm'])->name('alamat.form');
Route::post('/alamat/update', [AlamatController::class, 'update'])->name('alamat.update');

Route::get('/adminDash', fn() => view('adminDash'));
Route::get('/product', fn() => view('product'));
Route::get('/keranjang', fn() => view('keranjang'));
Route::get('/payment', fn() => view('payment'))->name('payment');