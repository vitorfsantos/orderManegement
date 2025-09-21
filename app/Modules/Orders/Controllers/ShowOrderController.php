<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\ShowOrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowOrderController extends Controller
{
  public function __construct(
    private ShowOrderService $showOrderService
  ) {}

  public function __invoke(Request $request, $id): View
  {
    $order = $this->showOrderService->execute($id);

    return view('Orders.show', compact('order'));
  }
}
