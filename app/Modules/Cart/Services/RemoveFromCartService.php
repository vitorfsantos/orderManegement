<?php

namespace App\Modules\Cart\Services;

use Illuminate\Http\RedirectResponse;

class RemoveFromCartService
{
  public function removeFromCart(string $produtoId): RedirectResponse
  {
    $carrinho = session()->get('carrinho', []);
    unset($carrinho[$produtoId]);
    session()->put('carrinho', $carrinho);

    return redirect()
      ->route('cart.index')
      ->with('success', 'Produto removido do carrinho!');
  }
}
