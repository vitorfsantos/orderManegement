<?php

namespace App\Modules\Orders\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends BaseModel
{
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
}
