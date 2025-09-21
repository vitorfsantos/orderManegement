<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
  protected $modules = [
    'Auth',
    'Users',
    'Products',
    'Orders',
    'Cart',
    'Dashboard'
  ];

  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    foreach ($this->modules as $module) {
      $this->loadModuleRoutes($module);
      $this->loadModuleViews($module);
    }
  }

  protected function loadModuleRoutes($module)
  {
    $routesPath = app_path("Modules/{$module}/routes.php");
    if (file_exists($routesPath)) {
      // Only load web routes for now
      Route::middleware('web')
        ->group($routesPath);
    }
  }

  protected function loadModuleViews($module)
  {
    $viewsPath = app_path("Modules/{$module}/Views");
    if (is_dir($viewsPath)) {
      $this->loadViewsFrom($viewsPath, strtolower($module));
    }
  }
}
