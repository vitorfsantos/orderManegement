<?php

namespace App\Modules\Cart\Services;

use Illuminate\Http\JsonResponse;

class GetCartCountService
{
  public function execute(): JsonResponse
  {
    $carrinho = session()->get('carrinho', []);
    $totalItems = array_sum(array_column($carrinho, 'quantidade'));

    return response()->json([
      'total_items' => $totalItems
    ]);
  }
}
