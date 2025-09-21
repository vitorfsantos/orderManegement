<?php

namespace App\Modules\Auth\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ProcessLoginService
{
  public function execute(array $credentials): RedirectResponse
  {
    $email = $credentials['email'];
    $password = $credentials['password'];
    $remember = $credentials['remember'] ?? false;

    if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
      request()->session()->regenerate();

      // Redirect based on user role
      $redirectRoute = Auth::user()->isAdmin() ? 'dashboard' : 'orders.index';
      return redirect()->intended(route($redirectRoute));
    }

    return back()->withErrors([
      'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
    ])->onlyInput('email');
  }
}
