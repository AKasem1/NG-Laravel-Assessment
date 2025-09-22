<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Customer\CustomerAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Home & Product Browsing
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');
Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');
Route::get('/filter-products', [HomeController::class, 'filter'])->name('products.filter');
Route::get('/categories', [HomeController::class, 'categories'])->name('categories.api');

// Cart Management (No Authentication Required)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::put('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::post('/quick-add/{product}', [HomeController::class, 'quickAddToCart'])->name('quick-add');
});

// Checkout Redirect (Triggers Authentication)
Route::get('/proceed-to-checkout', [CartController::class, 'proceedToCheckout'])->name('cart.checkout');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->name('customer.')->group(function () {
    // Guest routes
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login']);
        Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register']);
    });

    // Authenticated customer routes
    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [CustomerAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerAuthController::class, 'updateProfile'])->name('profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| Customer Order Routes (Authentication Required)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    // Checkout Process
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order-success/{order}', [OrderController::class, 'success'])->name('order.success');
    
    // Order Tracking
    Route::get('/track-order', [OrderController::class, 'track'])->name('order.track');
    Route::post('/track-order', [OrderController::class, 'track']);
});

/*
|--------------------------------------------------------------------------
| Laravel Breeze Admin Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
