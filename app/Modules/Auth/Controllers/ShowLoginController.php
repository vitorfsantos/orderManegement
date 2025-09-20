<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Services\ShowLoginService;
use Illuminate\View\View;

class ShowLoginController extends Controller
{
  public function __construct(
    private ShowLoginService $showLoginService
  ) {}

  public function show(): View
  {
    return $this->showLoginService->execute();
  }
}
