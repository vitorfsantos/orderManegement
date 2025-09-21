<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  if (Auth::check()) {
    // Redirect based on user role
    $redirectRoute = Auth::user()->isAdmin() ? 'dashboard' : 'orders.index';
    return redirect()->route($redirectRoute);
  }
  return redirect()->route('login');
})->name('home');

// Dashboard route is now handled by the Dashboard module

Route::middleware(['auth'])->group(function () {
  Route::redirect('settings', 'settings/profile');

  Route::get('settings/profile', Profile::class)->name('settings.profile');
  Route::get('settings/password', Password::class)->name('settings.password');
  Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
