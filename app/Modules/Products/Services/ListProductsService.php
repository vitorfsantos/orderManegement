<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProductsService
{
  public function execute(Request $request): View
  {
    $search = $request->get('search');
    $active = $request->get('active');
    $inStock = $request->get('in_stock');

    $query = Product::query();

    if ($search) {
      $query->where('name', 'like', "%{$search}%");
    }

    if ($active !== null) {
      $query->where('active', $active);
    }

    if ($inStock) {
      $query->where('stock', '>', 0);
    }

    $products = $query->orderBy('name')->paginate(15);

    // Determinar qual view usar baseado na rota
    $view = request()->is('admin/products*') ? 'Products.admin-index' : 'Products.index';

    return view($view, compact('products', 'search', 'active', 'inStock'));
  }
}
