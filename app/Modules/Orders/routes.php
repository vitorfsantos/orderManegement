<?php

use App\Modules\Orders\Controllers\FinishOrderController;
use App\Modules\Orders\Controllers\GetOrderDetailsController;
use App\Modules\Orders\Controllers\ListOrdersController;
use App\Modules\Orders\Controllers\ShowOrderController;
use App\Modules\Orders\Controllers\UpdateOrderStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  Route::post('/orders/finish', FinishOrderController::class)->name('orders.finish');
  Route::get('/orders', ListOrdersController::class)->name('orders.index');
  Route::get('/orders/{id}', ShowOrderController::class)->name('orders.show');
  Route::get('/orders/{id}/details', GetOrderDetailsController::class)->name('orders.details');

  // Rota para admin alterar status do pedido
  Route::middleware(['admin'])->group(function () {
    Route::patch('/orders/{id}/status', UpdateOrderStatusController::class)->name('orders.update-status');
  });
});
