<?php

use App\Modules\Users\Controllers\{
  ListUsersController,
  CreateUserController,
  EditUserController,
  DeleteUserController
};
use App\Modules\Users\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Users routes - apenas para administradores
Route::middleware(['auth', 'admin'])->group(function () {
  Route::get('/users', [ListUsersController::class, 'index'])->name('users.index');

  Route::get('/users/create', [CreateUserController::class, 'create'])->name('users.create');
  Route::post('/users', [CreateUserController::class, 'store'])->name('users.store');

  Route::get('/users/{user}', [EditUserController::class, 'show'])->name('users.show');
  Route::get('/users/{user}/edit', [EditUserController::class, 'edit'])->name('users.edit');
  Route::put('/users/{user}', [EditUserController::class, 'update'])->name('users.update');

  Route::delete('/users/{user}', [DeleteUserController::class, 'destroy'])->name('users.destroy');
});
