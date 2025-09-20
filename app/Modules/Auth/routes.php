<?php

use App\Modules\Auth\Controllers\{
  ShowLoginController,
  ProcessLoginController,
  ProcessLogoutController
};
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [ShowLoginController::class, 'show'])->name('login');
Route::post('/login', [ProcessLoginController::class, 'login']);
Route::post('/logout', [ProcessLogoutController::class, 'logout'])->name('logout');
