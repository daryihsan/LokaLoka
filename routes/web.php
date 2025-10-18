<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController; // Perubahan: Import CheckoutController
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AvatarController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// =======================================================
// USER/CUSTOMER ROUTES (Cek Session di Controller)
// =======================================================

// HOMEPAGE & PRODUK
Route::get('/homepage', [ProductController::class, 'index'])->name('homepage');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/searchfilter', [ProductController::class, 'searchfilter'])->name('searchfilter');

// PROFIL & PESANAN
Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
// AVATAR MANAGEMENT (Menggunakan Session)
Route::post('/profile/avatar/update', [AvatarController::class, 'update'])->name('profile.avatar.update');

// KERANJANG (CART)
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route baru untuk update kuantitas item keranjang (dipanggil via JS)
Route::patch('/api/cart/item/{id}', [CartController::class, 'updateCartItem'])->name('cart.item.update');
Route::delete('/api/cart/item/{id}', [CartController::class, 'removeFromCart'])->name('cart.item.remove');
Route::get('/api/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');


// CHECKOUT & PEMBAYARAN
Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process'); // Perubahan: Mengarah ke CheckoutController
Route::get('/alamat', [AlamatController::class, 'showForm'])->name('alamat.form');
Route::post('/alamat/update', [AlamatController::class, 'update'])->name('alamat.update');
Route::post('/alamat/delete', [AlamatController::class, 'delete'])->name('alamat.delete');

// Payment routes
Route::get('/payment/qris/{orderId}', [PaymentController::class, 'showQris'])->name('payment.qris');
Route::get('/payment/other/{orderId}', [PaymentController::class, 'showOtherPayment'])->name('payment.other'); // Route untuk transfer/COD


// =======================================================
// HALAMAN INFORMASI: FAQ, Kebijakan Privasi, Syarat & Ketentuan, About Us
// =======================================================
Route::view('/faq', 'etc.faq')->name('faq');
Route::view('/kebijakan-privasi', 'etc.policy&privacy')->name('privacy');
Route::view('/syarat-ketentuan', 'etc.terms&conditions')->name('terms');
Route::view('/about', 'etc.about')->name('about');

// =======================================================
// ADMIN ROUTES (Tidak ada perubahan di sini kecuali ada yang spesifik)
// =======================================================

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // PRODUCTS
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/products/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    
    // CATEGORIES
    Route::post('/categories/store', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}/update', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    
    // USERS (Approval & Edit)
    Route::put('/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
    
    // ORDERS
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
});