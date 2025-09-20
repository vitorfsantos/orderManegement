<?php

namespace App\Modules\Cart\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Http\JsonResponse;

class AddToCartService
{
  public function addToCart(array $data): JsonResponse
  {
    $produto = Product::findOrFail($data['produto_id']);

    if (!$produto->active || $produto->stock < $data['quantidade']) {
      return response()->json([
        'success' => false,
        'message' => 'Produto indisponível ou estoque insuficiente'
      ], 400);
    }

    $carrinho = session()->get('carrinho', []);
    $produtoId = $data['produto_id'];

    if (isset($carrinho[$produtoId])) {
      $carrinho[$produtoId]['quantidade'] += $data['quantidade'];
    } else {
      $carrinho[$produtoId] = [
        'produto_id' => $produtoId,
        'nome' => $produto->name,
        'preco' => $produto->price,
        'quantidade' => $data['quantidade']
      ];
    }

    session()->put('carrinho', $carrinho);

    return response()->json([
      'success' => true,
      'total_items' => array_sum(array_column($carrinho, 'quantidade')),
      'message' => 'Produto adicionado ao carrinho'
    ]);
  }
}
