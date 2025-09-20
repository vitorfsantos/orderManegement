<?php

namespace App\Modules\Products\Seeders;

use App\Modules\Products\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    $products = [
      [
        'name' => 'Laptop Dell Inspiron 15',
        'price' => 1299.99,
        'stock' => 10,
        'active' => true,
      ],
      [
        'name' => 'Smartphone Samsung Galaxy S23',
        'price' => 899.99,
        'stock' => 25,
        'active' => true,
      ],
      [
        'name' => 'Headphones Sony WH-1000XM4',
        'price' => 349.99,
        'stock' => 15,
        'active' => true,
      ],
      [
        'name' => 'Tablet iPad Air',
        'price' => 599.99,
        'stock' => 8,
        'active' => true,
      ],
      [
        'name' => 'Smartwatch Apple Watch Series 8',
        'price' => 399.99,
        'stock' => 12,
        'active' => true,
      ],
      [
        'name' => 'Monitor LG 27" 4K',
        'price' => 299.99,
        'stock' => 20,
        'active' => true,
      ],
      [
        'name' => 'Keyboard Mechanical RGB',
        'price' => 149.99,
        'stock' => 30,
        'active' => true,
      ],
      [
        'name' => 'Mouse Gaming Wireless',
        'price' => 79.99,
        'stock' => 40,
        'active' => true,
      ],
      [
        'name' => 'Webcam HD 1080p',
        'price' => 89.99,
        'stock' => 18,
        'active' => true,
      ],
      [
        'name' => 'External SSD 1TB',
        'price' => 199.99,
        'stock' => 22,
        'active' => true,
      ],
    ];

    foreach ($products as $productData) {
      $productData['slug'] = Str::slug($productData['name']);

      // Garantir slug único
      $originalSlug = $productData['slug'];
      $counter = 1;

      while (Product::where('slug', $productData['slug'])->exists()) {
        $productData['slug'] = $originalSlug . '-' . $counter;
        $counter++;
      }

      Product::create($productData);
    }
  }
}
