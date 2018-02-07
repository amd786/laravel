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

class EngQuoteDetails extends Model
{
  use SoftDeletes;
  protected $table = 'eng_quotes_details';
  protected $dates = ['deleted_at'];
  
  protected $fillable = [];
  
  // relations
  public function AssignProduct(){
    return $this->hasOne('App\Models\AssignProd', 'id', 'fk_assign_product_id');
  }
  
  // custom function 
  public function getHoldReasonInDropdown(){
    return EngHoldDesc::where('status', 1)->orderBy('id')->pluck('description', 'id');
  }
  
}
