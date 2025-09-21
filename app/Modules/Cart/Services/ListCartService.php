<?php

namespace App\Modules\Cart\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ListCartService
{
  public function execute(): View
  {
    $carrinho = session()->get('carrinho', []);
    $total = 0;

    foreach ($carrinho as $item) {
      $total += $item['preco'] * $item['quantidade'];
    }

    return view('Cart.index', compact('carrinho', 'total'));
  }

  public function getCartPreview(): JsonResponse
  {
    $carrinho = session()->get('carrinho', []);
    $total = 0;
    $totalItems = 0;

    foreach ($carrinho as $item) {
      $total += $item['preco'] * $item['quantidade'];
      $totalItems += $item['quantidade'];
    }

    return response()->json([
      'items' => $carrinho,
      'total' => $total,
      'total_items' => $totalItems,
      'formatted_total' => 'R$ ' . number_format($total, 2, ',', '.')
    ]);
  }
}
