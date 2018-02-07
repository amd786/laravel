<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Company;
use App\Models\InvLocations;
use DB;
use Config;
class Datasheet extends Model
{
  use SoftDeletes;
  protected $table = 'datasheet';
  protected $fillable = ['salesperson','date','detail','client','po','status','sequence_no'];
  
  public function getClientName(){
    return $this->hasOne('App\Models\Company','company_id','client');
  }
  public function getSalespersonName(){
    return $this->hasOne('App\Models\User','id','salesperson');
  }
}
?>