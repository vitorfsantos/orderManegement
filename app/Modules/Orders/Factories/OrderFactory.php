<?php

namespace App\Modules\Orders\Factories;

use App\Models\User;
use App\Modules\Orders\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Orders\Models\Order>
 */
class OrderFactory extends Factory
{
  protected $model = Order::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'total' => $this->faker->randomFloat(2, 10, 1000),
      'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
    ];
  }

  /**
   * Indicate that the order is pending.
   */
  public function pending(): static
  {
    return $this->state(fn(array $attributes) => [
      'status' => 'pending',
    ]);
  }

  /**
   * Indicate that the order is paid.
   */
  public function paid(): static
  {
    return $this->state(fn(array $attributes) => [
      'status' => 'paid',
    ]);
  }

  /**
   * Indicate that the order is cancelled.
   */
  public function cancelled(): static
  {
    return $this->state(fn(array $attributes) => [
      'status' => 'cancelled',
    ]);
  }
}
