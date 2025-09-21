<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Models\Product;
use App\Modules\Products\Requests\UpdateProductRequest;
use App\Modules\Products\Services\EditProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EditProductController extends Controller
{
  public function __construct(
    private EditProductService $editProductService
  ) {}

  public function show(Product $product): View|JsonResponse
  {
    if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
      $html = view('Products.partials.product-details', compact('product'))->render();
      return response()->json([
        'success' => true,
        'html' => $html
      ]);
    }

    return $this->editProductService->show($product);
  }

  public function edit(Product $product): View|JsonResponse
  {
    if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
      return response()->json([
        'success' => true,
        'product' => [
          'id' => $product->id,
          'name' => $product->name,
          'slug' => $product->slug,
          'price' => $product->price,
          'stock' => $product->stock,
          'active' => $product->active,
          'created_at' => $product->created_at->format('d/m/Y H:i'),
          'updated_at' => $product->updated_at->format('d/m/Y H:i'),
          'orders_count' => $product->orderItems()->count(),
        ]
      ]);
    }

    return $this->editProductService->showForm($product);
  }

  public function update(UpdateProductRequest $request, Product $product): RedirectResponse|JsonResponse
  {
    if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
      try {
        $this->editProductService->updateProduct($product, $request->validated());
        return response()->json([
          'success' => true,
          'message' => 'Produto atualizado com sucesso!'
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => 'Erro ao atualizar produto: ' . $e->getMessage()
        ], 422);
      }
    }

    return $this->editProductService->execute($product, $request->validated());
  }
}
