<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Models\Product;
use App\Modules\Products\Services\DeleteProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DeleteProductController extends Controller
{
  public function __construct(
    private DeleteProductService $deleteProductService
  ) {}

  public function destroy(Product $product): RedirectResponse|JsonResponse
  {
    if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
      try {
        $this->deleteProductService->deleteProduct($product);
        return response()->json([
          'success' => true,
          'message' => 'Produto removido com sucesso!'
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => $e->getMessage()
        ], 422);
      }
    }

    return $this->deleteProductService->execute($product);
  }
}
