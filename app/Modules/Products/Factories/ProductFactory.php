<?php

namespace App\Modules\Products\Factories;

use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Products\Models\Product>
 */
class ProductFactory extends Factory
{
  protected $model = Product::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => $this->faker->words(3, true),
      'slug' => $this->faker->slug(),
      'price' => $this->faker->randomFloat(2, 10, 500),
      'stock' => $this->faker->numberBetween(0, 100),
      'active' => $this->faker->boolean(80), // 80% chance of being active
    ];
  }

  /**
   * Indicate that the product is active.
   */
  public function active(): static
  {
    return $this->state(fn(array $attributes) => [
      'active' => true,
    ]);
  }

  /**
   * Indicate that the product is inactive.
   */
  public function inactive(): static
  {
    return $this->state(fn(array $attributes) => [
      'active' => false,
    ]);
  }

  /**
   * Indicate that the product is out of stock.
   */
  public function outOfStock(): static
  {
    return $this->state(fn(array $attributes) => [
      'stock' => 0,
    ]);
  }
}
