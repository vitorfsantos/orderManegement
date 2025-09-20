<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Services\ListProductsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListProductsController extends Controller
{
  public function __construct(
    private ListProductsService $listProductsService
  ) {}

  public function index(Request $request): View
  {
    return $this->listProductsService->execute($request);
  }
}
