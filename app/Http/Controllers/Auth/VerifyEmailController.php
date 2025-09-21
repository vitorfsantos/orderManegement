<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
  /**
   * Mark the authenticated user's email address as verified.
   */
  public function __invoke(EmailVerificationRequest $request): RedirectResponse
  {
    $user = Auth::user();
    $redirectRoute = $user->isAdmin() ? 'dashboard' : 'orders.index';

    if ($user->hasVerifiedEmail()) {
      return redirect()->intended(route($redirectRoute, absolute: false) . '?verified=1');
    }

    $request->fulfill();

    return redirect()->intended(route($redirectRoute, absolute: false) . '?verified=1');
  }
}
