<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ListOrdersService
{
  public function execute(array $filters = []): LengthAwarePaginator
  {
    $user = Auth::user();
    $query = Order::with(['orderItems.product', 'user']);

    // Se não for admin, filtrar apenas pedidos do usuário
    if (!$user->isAdmin()) {
      $query->where('user_id', $user->id);
    }

    // Aplicar filtros (apenas para admin)
    if ($user->isAdmin()) {
      // Filtro por usuário
      if (!empty($filters['user_id'])) {
        $query->where('user_id', $filters['user_id']);
      }

      // Filtro por status
      if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
      }

      // Filtro por data (desde)
      if (!empty($filters['date_from'])) {
        $query->whereDate('created_at', '>=', $filters['date_from']);
      }

      // Filtro por data (até)
      if (!empty($filters['date_to'])) {
        $query->whereDate('created_at', '<=', $filters['date_to']);
      }

      // Filtro por produto (busca nos itens do pedido)
      if (!empty($filters['product_name'])) {
        $query->whereHas('orderItems.product', function ($q) use ($filters) {
          $q->where('name', 'like', '%' . $filters['product_name'] . '%');
        });
      }
    }

    return $query->orderBy('created_at', 'desc')->paginate(10);
  }

  /**
   * Get available status options
   */
  public function getStatusOptions(): array
  {
    return [
      'pending' => 'Pendente',
      'paid' => 'Pago',
      'cancelled' => 'Cancelado'
    ];
  }
}
