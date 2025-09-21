<?php

namespace App\Modules\Orders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Services\ShowOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetOrderDetailsController extends Controller
{
  public function __construct(
    private ShowOrderService $showOrderService
  ) {}

  public function __invoke(Request $request, $id): JsonResponse
  {
    try {
      $order = $this->showOrderService->execute($id);

      return response()->json([
        'success' => true,
        'order' => [
          'id' => $order->id,
          'total' => $order->total,
          'status' => $order->status,
          'status_label' => $this->getStatusLabel($order->status),
          'created_at' => $order->created_at->format('d/m/Y H:i'),
          'user_name' => $order->user->name,
          'order_items' => $order->orderItems->map(function ($item) {
            return [
              'product_name' => $item->product->name,
              'quantity' => $item->quantity,
              'unit_price' => $item->unit_price,
              'total_price' => $item->unit_price * $item->quantity,
            ];
          }),
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Pedido não encontrado.'
      ], 404);
    }
  }

  private function getStatusLabel(string $status): string
  {
    return match ($status) {
      'pending' => 'Pendente',
      'paid' => 'Pago',
      'cancelled' => 'Cancelado',
      default => ucfirst($status)
    };
  }
}
