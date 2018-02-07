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

class Excuse extends Model
{
  use SoftDeletes;
  protected $table = 'excuses';
  protected $dates = ['deleted_at'];
  
  protected $fillable = ['excuse','category','status'];
  
  // relations
  
  
  // custom function 
  public function getCategoryInDropdown(){
    return $data = array('Order Canceled'=>'Order Canceled','Order Approved'=>'Order Approved');
  }
  
}
