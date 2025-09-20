<?php

namespace App\Modules\Products\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
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
}
