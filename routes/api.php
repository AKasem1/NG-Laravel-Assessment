<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes (No middleware needed)
Route::prefix('v1')->group(function () {
    
    // Product Search & Filtering
    Route::get('/products/search', [HomeController::class, 'searchSuggestions']);
    Route::get('/products/filter', [HomeController::class, 'filter']);
    Route::get('/categories', [HomeController::class, 'categories']);
    
    // Cart API
    Route::prefix('cart')->group(function () {
        Route::get('/count', [CartController::class, 'count']);
        Route::post('/add/{product}', [CartController::class, 'add']);
        Route::put('/update/{product}', [CartController::class, 'update']);
        Route::delete('/remove/{product}', [CartController::class, 'remove']);
    });
});
