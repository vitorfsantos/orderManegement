<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Mail\OrderConfirmationMail;
use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

      // Enviar email de confirmação quando o pedido for confirmado (paid)
      if ($status === 'paid') {
        try {
          Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
          Log::info("Order confirmation email sent for order #{$order->id} to {$order->user->email}");
        } catch (\Exception $e) {
          Log::error("Failed to send order confirmation email for order #{$order->id}: " . $e->getMessage());
          // Não falha o processo se o email não for enviado
        }
      }

      $statusLabels = [
        'pending' => 'Pendente',
        'paid' => 'Pago',
        'cancelled' => 'Cancelado'
      ];

      $message = "Status do pedido #{$order->id} alterado para '{$statusLabels[$status]}' com sucesso!";

      if ($status === 'cancelled') {
        $message .= " As quantidades dos produtos foram devolvidas ao estoque.";
      } elseif ($status === 'paid') {
        $message .= " Email de confirmação foi enviado para o cliente.";
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
