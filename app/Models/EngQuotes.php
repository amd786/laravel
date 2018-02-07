<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Permission;
use App\Models\Modules;
use App\Models\Company;
use App\Models\Contact;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class EngQuotes extends Model
{
  use SoftDeletes;
  protected $table = 'eng_quotes';
  protected $dates = ['deleted_at'];
  
  protected $fillable = [];
  
  // relations
  
  
  // custom function 
  public function Quotes(){
    return $this->hasOne('App\Models\Quotes','id','fk_quote_id');
  } 
  
}
