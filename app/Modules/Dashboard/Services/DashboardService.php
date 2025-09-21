<?php

namespace App\Modules\Dashboard\Services;

use App\Models\User;
use App\Modules\Orders\Models\Order;
use App\Modules\Products\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
  public function getDashboardData(): array
  {
    return [
      'stats' => $this->getStats(),
      'recent_orders' => $this->getRecentOrders(),
      'monthly_revenue' => $this->getMonthlyRevenue(),
      'top_products' => $this->getTopProducts(),
    ];
  }

  private function getStats(): array
  {
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();
    $thisMonth = Carbon::now()->startOfMonth();
    $lastMonth = Carbon::now()->subMonth()->startOfMonth();

    // Total de produtos
    $totalProducts = Product::count();
    $productsThisMonth = Product::where('created_at', '>=', $thisMonth)->count();
    $productsLastMonth = Product::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
    $productsGrowth = $productsLastMonth > 0 ?
      round((($productsThisMonth - $productsLastMonth) / $productsLastMonth) * 100, 1) : 0;

    // Pedidos hoje
    $ordersToday = Order::whereDate('created_at', $today)->count();
    $ordersYesterday = Order::whereDate('created_at', $yesterday)->count();
    $ordersGrowth = $ordersYesterday > 0 ?
      round((($ordersToday - $ordersYesterday) / $ordersYesterday) * 100, 1) : 0;

    // Receita hoje
    $revenueToday = Order::whereDate('created_at', $today)->sum('total');
    $revenueThisMonth = Order::where('created_at', '>=', $thisMonth)->sum('total');
    $revenueLastMonth = Order::whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total');
    $revenueGrowth = $revenueLastMonth > 0 ?
      round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1) : 0;

    // Usuários ativos (total de usuários cadastrados)
    $activeUsers = User::count();
    $usersThisMonth = User::where('created_at', '>=', $thisMonth)->count();
    $usersLastMonth = User::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
    $usersGrowth = $usersLastMonth > 0 ?
      round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1) : 0;

    return [
      'total_products' => [
        'value' => $totalProducts,
        'growth' => $productsGrowth,
        'growth_type' => $productsGrowth >= 0 ? 'positive' : 'negative'
      ],
      'orders_today' => [
        'value' => $ordersToday,
        'growth' => $ordersGrowth,
        'growth_type' => $ordersGrowth >= 0 ? 'positive' : 'negative'
      ],
      'revenue_today' => [
        'value' => $revenueToday,
        'growth' => $revenueGrowth,
        'growth_type' => $revenueGrowth >= 0 ? 'positive' : 'negative'
      ],
      'active_users' => [
        'value' => $activeUsers,
        'growth' => $usersGrowth,
        'growth_type' => $usersGrowth >= 0 ? 'positive' : 'negative'
      ]
    ];
  }

  private function getRecentOrders(): array
  {
    return Order::with(['user', 'orderItems.product'])
      ->latest()
      ->limit(5)
      ->get()
      ->map(function ($order) {
        return [
          'id' => $order->id,
          'code' => $order->code,
          'customer_name' => $order->user->name,
          'total' => $order->total,
          'status' => $order->status,
          'created_at' => $order->created_at,
          'status_color' => $this->getStatusColor($order->status),
          'status_icon' => $this->getStatusIcon($order->status)
        ];
      })
      ->toArray();
  }

  private function getMonthlyRevenue(): array
  {
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
      $date = Carbon::now()->subMonths($i);
      $revenue = Order::whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->sum('total');

      $months[] = [
        'month' => $date->format('M'),
        'revenue' => (float) $revenue
      ];
    }

    return $months;
  }

  private function getTopProducts(): array
  {
    return Product::withCount('orderItems')
      ->orderBy('order_items_count', 'desc')
      ->limit(5)
      ->get()
      ->map(function ($product) {
        return [
          'id' => $product->id,
          'name' => $product->name,
          'orders_count' => $product->order_items_count,
          'stock' => $product->stock,
          'price' => $product->price
        ];
      })
      ->toArray();
  }

  private function getStatusColor(string $status): string
  {
    return match ($status) {
      'pending' => 'yellow',
      'paid' => 'green',
      'cancelled' => 'red',
      default => 'gray'
    };
  }

  private function getStatusIcon(string $status): string
  {
    return match ($status) {
      'pending' => 'fas fa-clock',
      'paid' => 'fas fa-check',
      'cancelled' => 'fas fa-times',
      default => 'fas fa-question'
    };
  }
}
