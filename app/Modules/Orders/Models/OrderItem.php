<?php

namespace App\Modules\Orders\Models;

use App\Models\BaseModel;
use App\Modules\Orders\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends BaseModel
{
  use HasFactory;
  protected $fillable = [
    'order_id',
    'product_id',
    'quantity',
    'unit_price'
  ];

  protected $casts = [
    'unit_price' => 'decimal:2',
  ];

  public function order(): BelongsTo
  {
    return $this->belongsTo(Order::class);
  }

  public function product(): BelongsTo
  {
    return $this->belongsTo(\App\Modules\Products\Models\Product::class);
  }

  /**
   * Create a new factory instance for the model.
   */
  protected static function newFactory()
  {
    return OrderItemFactory::new();
  }
}
