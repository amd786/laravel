<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeRate extends Model
{
  use SoftDeletes;
  protected $table = 'exchange_rates';
  protected $dates = ['deleted_at'];

  protected $fillable = ['currency','rate','calculated_at'];

  // relations


}
