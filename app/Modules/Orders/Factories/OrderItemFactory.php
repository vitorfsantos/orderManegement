<?php

namespace App\Modules\Orders\Factories;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Orders\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
  protected $model = OrderItem::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $quantity = $this->faker->numberBetween(1, 5);
    $price = $this->faker->randomFloat(2, 10, 100);

    return [
      'order_id' => Order::factory(),
      'product_id' => Product::factory(),
      'quantity' => $quantity,
      'unit_price' => $price,
    ];
  }
}
