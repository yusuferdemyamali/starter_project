<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Cache optimized API endpoints with eager loading for better performance
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Blog API Routes - Cache enabled
Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/categories', [BlogController::class, 'categories']);
    Route::get('/category/{categoryId}', [BlogController::class, 'byCategory']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});

// Product API Routes - Cache enabled
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/featured', [ProductController::class, 'featured']);
    Route::get('/categories', [ProductController::class, 'categories']);
    Route::get('/categories/popular', [ProductController::class, 'popularCategories']);
    Route::get('/category/{categoryId}', [ProductController::class, 'byCategory']);
    Route::get('/price-range', [ProductController::class, 'byPriceRange']);
    Route::get('/{id}', [ProductController::class, 'show']);
});
