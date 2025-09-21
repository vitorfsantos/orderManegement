<?php

namespace App\Modules\Products\Models;

use App\Models\BaseModel;
use App\Modules\Products\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
  use HasFactory;
  protected $fillable = [
    'name',
    'slug',
    'price',
    'stock',
    'active'
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'active' => 'boolean',
  ];

  public function orderItems(): HasMany
  {
    return $this->hasMany(\App\Modules\Orders\Models\OrderItem::class);
  }

  public function scopeActive($query)
  {
    return $query->where('active', true);
  }

  public function scopeInStock($query)
  {
    return $query->where('stock', '>', 0);
  }

  /**
   * Create a new factory instance for the model.
   */
  protected static function newFactory()
  {
    return ProductFactory::new();
  }
}
