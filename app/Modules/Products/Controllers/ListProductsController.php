<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Models\Product;
use Illuminate\View\View;

class ListProductsController extends Controller
{
  public function index(): View
  {
    $products = Product::active()
      ->inStock()
      ->paginate(12);

    return view('Products.index', compact('products'));
  }
}
