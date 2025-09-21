<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\UpdateOrderStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateOrderStatusController extends Controller
{
  public function __construct(
    private UpdateOrderStatusService $updateOrderStatusService
  ) {}

  public function __invoke(Request $request, $id): RedirectResponse
  {
    $request->validate([
      'status' => 'required|in:pending,paid,cancelled'
    ]);

    return $this->updateOrderStatusService->execute($id, $request->status);
  }
}
