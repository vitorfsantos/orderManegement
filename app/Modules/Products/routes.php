<?php

use App\Modules\Products\Controllers\{
  ListProductsController,
  ShowProductController,
  CreateProductController,
  EditProductController,
  DeleteProductController
};
use App\Modules\Products\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public routes - require authentication
Route::middleware(['auth'])->group(function () {
  Route::get('/products', [ListProductsController::class, 'index'])->name('products.index');
  Route::get('/products/{slug}', [ShowProductController::class, 'show'])->name('products.show');
});

// Admin routes - require admin authentication
Route::middleware(['auth', 'admin'])->group(function () {
  Route::get('/admin/products', [ListProductsController::class, 'index'])->name('admin.products.index');

  Route::get('/admin/products/create', [CreateProductController::class, 'create'])->name('admin.products.create');
  Route::post('/admin/products', [CreateProductController::class, 'store'])->name('admin.products.store');

  Route::get('/admin/products/{product}', [EditProductController::class, 'show'])->name('admin.products.show');
  Route::get('/admin/products/{product}/edit', [EditProductController::class, 'edit'])->name('admin.products.edit');
  Route::put('/admin/products/{product}', [EditProductController::class, 'update'])->name('admin.products.update');

  Route::delete('/admin/products/{product}', [DeleteProductController::class, 'destroy'])->name('admin.products.destroy');
});
