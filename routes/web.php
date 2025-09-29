<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// User routes
Route::get('/homepage', [ProductController::class, 'index'])->name('homepage');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

Route::get('/cart', [CartController::class, 'showCart'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{itemId}', [CartController::class, 'updateQty'])->name('cart.update');
Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/checkout', function () { return view('checkout'); })->name('checkout');  // Asumsi view untuk pilih payment
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

Route::get('/payment/qris/{orderId}', [PaymentController::class, 'showQris'])->name('payment.qris');

Route::get('/orders', [AuthController::class, 'showProfile'])->name('orders');  // Lihat di profile

Route::get('/alamat', [AlamatController::class, 'showForm'])->name('alamat.form');
Route::post('/alamat/update', [AlamatController::class, 'update'])->name('alamat.update');

Route::get('/searchfilter', [ProductController::class, 'searchfilter'])->name('searchfilter');

// Admin routes (semua dihandle dari dashboard)
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
Route::put('/admin/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

Route::post('/admin/categories/store', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
Route::put('/admin/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
Route::post('/admin/users/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');

Route::put('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
Route::get('/admin/orders/{id}', [AdminController::class, 'showOrder'])->name('admin.orders.show');