<?php

namespace App\Modules\Orders\Seeders;

use App\Models\User;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Products\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
  public function run(): void
  {
    // Limpar dados existentes
    DB::table('order_items')->delete();
    DB::table('orders')->delete();

    $users = User::all();
    $products = Product::active()->get();

    if ($users->isEmpty() || $products->isEmpty()) {
      $this->command->warn('Não há usuários ou produtos cadastrados. Execute os seeders de Users e Products primeiro.');
      return;
    }

    $statuses = ['pending', 'paid', 'cancelled'];
    $statusWeights = [
      'pending' => 20,
      'paid' => 70,
      'cancelled' => 10
    ];

    // Criar pedidos distribuídos nos últimos 6 meses
    $totalOrders = 150; // Total de pedidos a criar
    $startDate = Carbon::now()->subMonths(6);
    $endDate = Carbon::now();

    $this->command->info("Criando {$totalOrders} pedidos distribuídos entre {$startDate->format('d/m/Y')} e {$endDate->format('d/m/Y')}...");

    for ($i = 0; $i < $totalOrders; $i++) {
      // Data aleatória nos últimos 6 meses
      $randomDate = $this->getRandomDateBetween($startDate, $endDate);

      // Usuário aleatório
      $user = $users->random();

      // Status baseado nos pesos
      $status = $this->getWeightedRandomStatus($statusWeights);

      // Criar pedido
      $order = Order::create([
        'user_id' => $user->id,
        'total' => 0, // Será calculado após criar os itens
        'status' => $status,
        'created_at' => $randomDate,
        'updated_at' => $randomDate,
      ]);

      // Criar itens do pedido (1 a 5 produtos por pedido)
      $itemsCount = rand(1, 5);
      $totalOrder = 0;

      for ($j = 0; $j < $itemsCount; $j++) {
        $product = $products->random();
        $quantity = rand(1, 3);
        $unitPrice = $product->price;
        $itemTotal = $quantity * $unitPrice;

        OrderItem::create([
          'order_id' => $order->id,
          'product_id' => $product->id,
          'quantity' => $quantity,
          'unit_price' => $unitPrice,
          'created_at' => $randomDate,
          'updated_at' => $randomDate,
        ]);

        $totalOrder += $itemTotal;
      }

      // Atualizar total do pedido
      $order->update(['total' => $totalOrder]);

      // Progresso
      if (($i + 1) % 25 === 0) {
        $this->command->info("Criados " . ($i + 1) . " pedidos...");
      }
    }

    $this->command->info("✅ {$totalOrders} pedidos criados com sucesso!");
    $this->command->info("📊 Estatísticas:");

    // Mostrar estatísticas
    $this->showOrderStatistics();
  }

  private function getRandomDateBetween(Carbon $start, Carbon $end): Carbon
  {
    $startTimestamp = $start->timestamp;
    $endTimestamp = $end->timestamp;
    $randomTimestamp = rand($startTimestamp, $endTimestamp);

    return Carbon::createFromTimestamp($randomTimestamp);
  }

  private function getWeightedRandomStatus(array $weights): string
  {
    $totalWeight = array_sum($weights);
    $random = rand(1, $totalWeight);

    $currentWeight = 0;
    foreach ($weights as $status => $weight) {
      $currentWeight += $weight;
      if ($random <= $currentWeight) {
        return $status;
      }
    }

    return 'pending'; // fallback
  }

  private function showOrderStatistics(): void
  {
    $stats = [
      'Total de pedidos' => Order::count(),
      'Pedidos pendentes' => Order::where('status', 'pending')->count(),
      'Pedidos pagos' => Order::where('status', 'paid')->count(),
      'Pedidos cancelados' => Order::where('status', 'cancelled')->count(),
      'Receita total' => 'R$ ' . number_format(Order::sum('total'), 2, ',', '.'),
      'Ticket médio' => 'R$ ' . number_format(Order::avg('total'), 2, ',', '.'),
    ];

    foreach ($stats as $label => $value) {
      $this->command->line("  {$label}: {$value}");
    }

    // Estatísticas por mês
    $this->command->info("\n📅 Pedidos por mês (últimos 6 meses):");
    for ($i = 5; $i >= 0; $i--) {
      $month = Carbon::now()->subMonths($i);
      $count = Order::whereYear('created_at', $month->year)
        ->whereMonth('created_at', $month->month)
        ->count();
      $revenue = Order::whereYear('created_at', $month->year)
        ->whereMonth('created_at', $month->month)
        ->sum('total');

      $this->command->line("  {$month->format('M/Y')}: {$count} pedidos - R$ " . number_format($revenue, 2, ',', '.'));
    }
  }
}
