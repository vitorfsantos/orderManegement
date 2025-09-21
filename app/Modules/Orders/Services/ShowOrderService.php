<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ShowOrderService
{
  public function execute($id): Order
  {
    $order = Order::with(['orderItems.product', 'user'])
      ->where('user_id', Auth::id())
      ->find($id);

    if (!$order) {
      throw new ModelNotFoundException('Pedido não encontrado.');
    }

    return $order;
  }
}
