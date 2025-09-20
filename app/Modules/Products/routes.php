<?php

use App\Modules\Products\Controllers\{
  ListProductsController,
  ShowProductController
};
use Illuminate\Support\Facades\Route;

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
  Route::get('/products', [ListProductsController::class, 'index'])->name('products.index');
  Route::get('/products/{slug}', [ShowProductController::class, 'show'])->name('products.show');
});
