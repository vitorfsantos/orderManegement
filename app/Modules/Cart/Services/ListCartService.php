<?php

namespace App\Modules\Cart\Services;

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
}
