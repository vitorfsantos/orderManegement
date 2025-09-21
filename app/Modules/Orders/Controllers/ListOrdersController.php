<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\ListOrdersService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListOrdersController extends Controller
{
  public function __construct(
    private ListOrdersService $listOrdersService
  ) {}

  public function __invoke(Request $request): View
  {
    $filters = $request->only(['user_id', 'status', 'date_from', 'date_to', 'product_name']);
    $orders = $this->listOrdersService->execute($filters);
    $statusOptions = $this->listOrdersService->getStatusOptions();

    return view('Orders.index', compact('orders', 'statusOptions', 'filters'));
  }
}
