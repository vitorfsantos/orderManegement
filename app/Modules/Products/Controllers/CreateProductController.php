<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Requests\StoreProductRequest;
use App\Modules\Products\Services\CreateProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreateProductController extends Controller
{
  public function __construct(
    private CreateProductService $createProductService
  ) {}

  public function create(): View
  {
    return $this->createProductService->showForm();
  }

  public function store(StoreProductRequest $request): RedirectResponse|JsonResponse
  {
    if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
      try {
        $product = $this->createProductService->createProduct($request->validated());
        return response()->json([
          'success' => true,
          'message' => 'Produto criado com sucesso!',
          'product' => $product
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => 'Erro ao criar produto: ' . $e->getMessage()
        ], 422);
      }
    }

    return $this->createProductService->execute($request->validated());
  }
}
