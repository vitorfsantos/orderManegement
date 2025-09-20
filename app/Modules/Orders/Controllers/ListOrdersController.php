<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ListOrdersController extends Controller
{
  public function __invoke(): View
  {
    $orders = Order::with(['orderItems.product', 'user'])
      ->where('user_id', Auth::id())
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('Orders.index', compact('orders'));
  }
}
