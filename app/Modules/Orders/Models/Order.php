<?php

namespace App\Modules\Orders\Models;

use App\Models\BaseModel;
use App\Modules\Orders\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends BaseModel
{
  use HasFactory;
  protected $fillable = [
    'code',
    'user_id',
    'total',
    'status'
  ];

  protected $casts = [
    'total' => 'decimal:2',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($order) {
      $order->code = Str::uuid();
    });
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(\App\Models\User::class);
  }

  public function orderItems(): HasMany
  {
    return $this->hasMany(OrderItem::class);
  }

  /**
   * Create a new factory instance for the model.
   */
  protected static function newFactory()
  {
    return OrderFactory::new();
  }
}
