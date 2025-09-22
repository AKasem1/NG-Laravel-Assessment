<?php

use App\Http\Controllers\{
    HomeController,
    CartController,
    OrderController,
    AdminController,
    ProductController,
    CategoryController,
    ProfileController
};
use App\Http\Controllers\Auth\UnifiedAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Product Routes
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/search', [HomeController::class, 'search'])->name('products.search');
Route::get('/products/category/{category}', [HomeController::class, 'category'])->name('products.category');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

// API Routes for AJAX
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
| Unified Authentication Routes (Handles Both Customers & Admins)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Main login/register routes (for customers)
    Route::get('/login', [UnifiedAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UnifiedAuthController::class, 'login'])->name('unified.login');
    Route::get('/register', [UnifiedAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UnifiedAuthController::class, 'register'])->name('unified.register');
    
    // Admin registration (restricted access)
    Route::get('/admin/register', [UnifiedAuthController::class, 'showAdminRegister'])->name('admin.register');
    Route::post('/admin/register', [UnifiedAuthController::class, 'createAdmin'])->name('admin.create');
});

// Unified logout (works for both customers and admins)
Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Order Routes (Customer Authentication Required)
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
    
    // Customer Profile
    Route::get('/customer/profile', [UnifiedAuthController::class, 'customerProfile'])->name('customer.profile');
    Route::put('/customer/profile', [UnifiedAuthController::class, 'updateCustomerProfile'])->name('customer.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Admin Authentication Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product Management
    Route::resource('products', ProductController::class);
    Route::put('/products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.status');
    Route::get('/products/{product}/history', [ProductController::class, 'history'])->name('products.history');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    Route::get('/categories/{category}/products', [CategoryController::class, 'products'])->name('categories.products');
    
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // Customer Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminController::class, 'showCustomer'])->name('customers.show');
    
    // Analytics & Reports
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics.index');
    Route::get('/analytics/sales', [AdminController::class, 'salesAnalytics'])->name('analytics.sales');
    Route::get('/analytics/products', [AdminController::class, 'productAnalytics'])->name('analytics.products');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Admin Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Password Reset Routes (For Both Customers & Admins)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [UnifiedAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [UnifiedAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [UnifiedAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [UnifiedAuthController::class, 'resetPassword'])->name('password.store');
});
