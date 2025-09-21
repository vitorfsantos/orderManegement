<?php

namespace App\Modules\Dashboard;

use App\Modules\Dashboard\Services\DashboardService;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->app->singleton(DashboardService::class);
  }

  public function boot(): void
  {
    //
  }
}
