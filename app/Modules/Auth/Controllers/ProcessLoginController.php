<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\ProcessLoginService;
use Illuminate\Http\RedirectResponse;

class ProcessLoginController extends Controller
{
  public function __construct(
    private ProcessLoginService $processLoginService
  ) {}

  public function login(LoginRequest $request): RedirectResponse
  {
    return $this->processLoginService->execute($request->validated());
  }
}
