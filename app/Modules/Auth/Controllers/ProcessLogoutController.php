<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\ProcessLogoutService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProcessLogoutController extends Controller
{
  public function __construct(
    private ProcessLogoutService $processLogoutService
  ) {}

  public function logout(Request $request): RedirectResponse
  {
    return $this->processLogoutService->execute($request);
  }
}
