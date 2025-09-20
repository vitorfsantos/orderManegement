<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShowOrderController extends Controller
{
  public function __invoke(Request $request, $id): View
  {
    $order = Order::with(['orderItems.product', 'user'])
      ->where('user_id', Auth::id())
      ->findOrFail($id);

    return view('Orders.show', compact('order'));
  }
}
