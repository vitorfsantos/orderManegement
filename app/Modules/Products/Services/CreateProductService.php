<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreateProductService
{
  public function showForm(): View
  {
    return view('Products.create');
  }

  public function createProduct(array $data): Product
  {
    return Product::create([
      'name' => $data['name'],
      'slug' => \Illuminate\Support\Str::slug($data['name']),
      'price' => $data['price'],
      'stock' => $data['stock'],
      'active' => $data['active'] ?? true,
    ]);
  }

  public function execute(array $data): RedirectResponse
  {
    try {
      $product = $this->createProduct($data);

      return redirect()
        ->route('products.show', $product)
        ->with('success', 'Produto criado com sucesso!');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->with('error', 'Erro ao criar produto: ' . $e->getMessage());
    }
  }
}
