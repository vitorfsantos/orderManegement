<?php

use App\Modules\Cart\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Cart routes - require authentication
Route::middleware(['auth'])->group(function () {
  Route::post('/carrinho/add', [CartController::class, 'add'])->name('cart.add');
  Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
  Route::post('/carrinho/remove', [CartController::class, 'remove'])->name('cart.remove');
  Route::get('/carrinho/count', [CartController::class, 'getCartCount'])->name('cart.count');
  Route::get('/carrinho/preview', [CartController::class, 'getCartPreview'])->name('cart.preview');
});
