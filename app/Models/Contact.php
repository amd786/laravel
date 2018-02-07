<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Permission;
use App\Models\Modules;
use App\Models\Company;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $primaryKey = 'contact_id';

  protected $fillable = ['fk_user_id','first_name','last_name','fk_company_id','phone','email','position','lead_contact','birthday','notes','status','deleted'];

  // relations


  public function getCompanyInDropdown(){
    return Company::where('status','=', 1)->orderBy('company_name')->pluck('company_name', 'company_id');
  }

  public function fullName(){
    if(!empty($this->first_name) && !empty($this->last_name)){
      return $this->first_name.' '.$this->last_name;
    }else{
      return 'No Name';
    }
  }

  public function Company(){
    return $this->hasOne('App\Models\Company','company_id','fk_company_id');
  }
}
