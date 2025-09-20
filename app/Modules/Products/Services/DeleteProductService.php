<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Http\RedirectResponse;

class DeleteProductService
{
  public function deleteProduct(Product $product): bool
  {
    // Verificar se o produto tem pedidos associados
    if ($product->orderItems()->exists()) {
      throw new \Exception('Não é possível deletar um produto que possui pedidos associados.');
    }

    return $product->delete();
  }

  public function execute(Product $product): RedirectResponse
  {
    try {
      $this->deleteProduct($product);

      return redirect()
        ->route('products.index')
        ->with('success', 'Produto removido com sucesso!');
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Erro ao remover produto: ' . $e->getMessage());
    }
  }
}
