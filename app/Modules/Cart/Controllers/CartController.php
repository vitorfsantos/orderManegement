<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Requests\AddToCartRequest;
use App\Modules\Cart\Requests\RemoveFromCartRequest;
use App\Modules\Cart\Services\AddToCartService;
use App\Modules\Cart\Services\GetCartCountService;
use App\Modules\Cart\Services\ListCartService;
use App\Modules\Cart\Services\RemoveFromCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
  public function __construct(
    private AddToCartService $addToCartService,
    private ListCartService $listCartService,
    private RemoveFromCartService $removeFromCartService,
    private GetCartCountService $getCartCountService
  ) {}

  public function add(AddToCartRequest $request): JsonResponse
  {
    return $this->addToCartService->addToCart($request->validated());
  }

  public function index(): View
  {
    return $this->listCartService->execute();
  }

  public function remove(RemoveFromCartRequest $request): RedirectResponse
  {
    return $this->removeFromCartService->removeFromCart($request->validated()['produto_id']);
  }

  public function getCartCount(): JsonResponse
  {
    return $this->getCartCountService->execute();
  }
}
