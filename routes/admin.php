<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (Authentication Required)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.alt');
    
    // Product Management
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'adminIndex'])->name('index');
        Route::get('/{order}', [OrderController::class, 'adminShow'])->name('show');
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    });
    
    // Customer Management
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [AdminController::class, 'customers'])->name('index');
        Route::get('/details', [AdminController::class, 'customerDetails'])->name('details');
        Route::get('/analytics', [AdminController::class, 'customerAnalytics'])->name('analytics');
        Route::get('/export', [AdminController::class, 'exportCustomers'])->name('export');
    });
    
    // Product Logs & Audit Trail
    Route::prefix('product-logs')->name('product-logs.')->group(function () {
        Route::get('/', [AdminController::class, 'productLogs'])->name('index');
    });
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::put('/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('toggle-status');
    });
    
    // Analytics & Reports
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AdminController::class, 'analytics'])->name('index');
        Route::get('/export-report', [AdminController::class, 'exportReport'])->name('export-report');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('index');
        Route::put('/', [AdminController::class, 'updateSettings'])->name('update');
    });
});
