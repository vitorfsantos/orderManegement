<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
  use HasUuids, SoftDeletes;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $dates = ['deleted_at'];
}
