<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\FinishOrderService;
use Illuminate\Http\RedirectResponse;

class FinishOrderController extends Controller
{
  public function __construct(
    private FinishOrderService $finishOrderService
  ) {}

  public function __invoke(): RedirectResponse
  {
    return $this->finishOrderService->execute();
  }
}
