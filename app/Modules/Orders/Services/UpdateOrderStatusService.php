<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatusService
{
  public function execute($orderId, string $status): RedirectResponse
  {
    $user = Auth::user();

    // Verificar se o usuário é admin
    if (!$user->isAdmin()) {
      return redirect()->back()
        ->with('error', 'Você não tem permissão para alterar o status do pedido.');
    }

    // Buscar o pedido com seus itens
    $order = Order::with('orderItems.product')->find($orderId);

    if (!$order) {
      throw new ModelNotFoundException('Pedido não encontrado.');
    }

    // Validar status
    $validStatuses = ['pending', 'paid', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
      return redirect()->back()
        ->with('error', 'Status inválido.');
    }

    // Verificar se o pedido já está cancelado
    if ($order->status === 'cancelled' && $status === 'cancelled') {
      return redirect()->back()
        ->with('error', 'Este pedido já está cancelado.');
    }

    try {
      DB::beginTransaction();

      // Se o pedido está sendo cancelado e não estava cancelado antes
      if ($status === 'cancelled' && $order->status !== 'cancelled') {
        $this->returnStockToProducts($order);
      }

      // Atualizar status
      $order->update(['status' => $status]);

      DB::commit();

      $statusLabels = [
        'pending' => 'Pendente',
        'paid' => 'Pago',
        'cancelled' => 'Cancelado'
      ];

      $message = "Status do pedido #{$order->id} alterado para '{$statusLabels[$status]}' com sucesso!";

      if ($status === 'cancelled') {
        $message .= " As quantidades dos produtos foram devolvidas ao estoque.";
      }

      return redirect()->back()->with('success', $message);
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Error updating order status: ' . $e->getMessage());

      return redirect()->back()
        ->with('error', 'Erro ao alterar status do pedido: ' . $e->getMessage());
    }
  }

  /**
   * Retorna as quantidades dos produtos para o estoque
   */
  private function returnStockToProducts(Order $order): void
  {
    foreach ($order->orderItems as $orderItem) {
      $product = $orderItem->product;

      if ($product) {
        // Incrementar o estoque com a quantidade que foi subtraída
        $product->increment('stock', $orderItem->quantity);

        Log::info("Stock returned for product {$product->name}: {$orderItem->quantity} units");
      }
    }
  }
}
