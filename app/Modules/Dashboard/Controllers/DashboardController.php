<?php

namespace App\Modules\Dashboard\Controllers;

use App\Modules\Dashboard\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController
{
  public function __construct(
    private DashboardService $dashboardService
  ) {}

  public function index()
  {
    $data = $this->dashboardService->getDashboardData();

    return view('Dashboard.index', compact('data'));
  }
}
