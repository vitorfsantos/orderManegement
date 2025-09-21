<?php

namespace App\Modules\Cart\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RemoveFromCartService
{
  public function removeFromCart(string $produtoId, Request $request = null): RedirectResponse|JsonResponse
  {
    $carrinho = session()->get('carrinho', []);
    unset($carrinho[$produtoId]);
    session()->put('carrinho', $carrinho);

    // If it's an AJAX request, return JSON
    if ($request && $request->ajax()) {
      $totalItems = array_sum(array_column($carrinho, 'quantidade'));
      return response()->json([
        'success' => true,
        'total_items' => $totalItems,
        'message' => 'Produto removido do carrinho!'
      ]);
    }

    return redirect()
      ->route('cart.index')
      ->with('success', 'Produto removido do carrinho!');
  }
}
