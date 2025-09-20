<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EditProductService
{
  public function show(Product $product): View
  {
    return view('Products.show', compact('product'));
  }

  public function showForm(Product $product): View
  {
    return view('Products.edit', compact('product'));
  }

  public function updateProduct(Product $product, array $data): Product
  {
    $product->update([
      'name' => $data['name'],
      'slug' => \Illuminate\Support\Str::slug($data['name']),
      'price' => $data['price'],
      'stock' => $data['stock'],
      'active' => $data['active'] ?? true,
    ]);

    return $product->fresh();
  }

  public function execute(Product $product, array $data): RedirectResponse
  {
    try {
      $this->updateProduct($product, $data);

      return redirect()
        ->route('products.show', $product)
        ->with('success', 'Produto atualizado com sucesso!');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
    }
  }
}
