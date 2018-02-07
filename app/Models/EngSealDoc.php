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

class EngSealDoc extends Model
{
  use SoftDeletes;
  protected $table = 'eng_seal_doc';
  protected $dates = ['deleted_at'];
  
  protected $fillable = ['fk_product_class_id','fk_product_id','inst_drawing','fabri_drawing','inst_drawing_date','fabri_drawing_date','inst_drawing_by','fabri_drawing_by'];
  
  // relations
  
  
  // custom function 
  
  
}
