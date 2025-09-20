<?php

use App\Modules\Orders\Controllers\FinishOrderController;
use App\Modules\Orders\Controllers\ListOrdersController;
use App\Modules\Orders\Controllers\ShowOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  Route::post('/orders/finish', FinishOrderController::class)->name('orders.finish');
  Route::get('/orders', ListOrdersController::class)->name('orders.index');
  Route::get('/orders/{id}', ShowOrderController::class)->name('orders.show');
});
