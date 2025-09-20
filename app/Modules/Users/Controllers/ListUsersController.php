<?php

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Services\ListUsersService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListUsersController extends Controller
{
  public function __construct(
    private ListUsersService $listUsersService
  ) {}

  public function index(Request $request): View
  {
    return $this->listUsersService->execute($request);
  }
}
