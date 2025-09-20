<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Products\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FinishOrderService
{
  public function execute(): RedirectResponse
  {
    $carrinho = Session::get('carrinho', []);

    if (empty($carrinho)) {
      return redirect()->route('cart.index')
        ->with('error', 'Seu carrinho está vazio.');
    }

    try {
      DB::beginTransaction();

      // Verificar estoque antes de criar o pedido
      $this->validateStock($carrinho);

      // Criar o pedido
      $order = $this->createOrder($carrinho);

      // Criar os itens do pedido e subtrair do estoque
      $this->createOrderItemsAndUpdateStock($order, $carrinho);

      // Limpar o carrinho
      Session::forget('carrinho');

      DB::commit();

      return redirect()->route('orders.show', $order->id)
        ->with('success', 'Pedido finalizado com sucesso!');
    } catch (\Exception $e) {
      \Log::error('Error in FinishOrderService: ' . $e->getMessage());
      DB::rollBack();

      return redirect()->route('cart.index')
        ->with('error', 'Erro ao finalizar pedido: ' . $e->getMessage());
    }
  }

  private function validateStock(array $carrinho): void
  {
    foreach ($carrinho as $item) {
      $product = Product::find($item['produto_id']);

      if (!$product) {
        throw new \Exception("Produto '{$item['nome']}' não encontrado.");
      }

      if (!$product->active) {
        throw new \Exception("Produto '{$item['nome']}' não está disponível.");
      }

      if ($product->stock < $item['quantidade']) {
        throw new \Exception(
          "Estoque insuficiente para o produto '{$item['nome']}'. " .
            "Disponível: {$product->stock}, Solicitado: {$item['quantidade']}"
        );
      }
    }
  }

  private function createOrder(array $carrinho): Order
  {
    $total = array_sum(array_map(function ($item) {
      return $item['preco'] * $item['quantidade'];
    }, $carrinho));

    return Order::create([
      'user_id' => Auth::id(),
      'total' => $total,
      'status' => 'pending'
    ]);
  }

  private function createOrderItemsAndUpdateStock(Order $order, array $carrinho): void
  {
    foreach ($carrinho as $item) {
      // Criar item do pedido
      OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $item['produto_id'],
        'quantity' => $item['quantidade'],
        'unit_price' => $item['preco']
      ]);

      // Subtrair do estoque
      $product = Product::find($item['produto_id']);
      $product->decrement('stock', $item['quantidade']);
    }
  }
}
