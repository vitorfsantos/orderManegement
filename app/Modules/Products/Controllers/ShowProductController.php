<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Models\Product;
use Illuminate\View\View;

class ShowProductController extends Controller
{
  public function show(string $slug): View
  {
    $product = Product::where('slug', $slug)->firstOrFail();

    return view('Products.show', compact('product'));
  }
}
