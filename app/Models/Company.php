<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Permission;
use App\Models\Modules;
use App\Models\Contact;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{

  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $primaryKey = 'company_id';

  protected $fillable = ['fk_user_id','company_name','street_name','city','country','phone','notes','postal_code','billing_street_name','billing_city','billing_country','billing_postal_code','notes','status','deleted'];

  // relations
  public function Contacts(){
    return $this->hasMany('App\Models\Contact','fk_company_id','company_id');
  }
  public function ApprovedUser(){
    $data = $this->hasOne('App\Models\User','id','PLA_approved_by')->where('deleted_at',NULL);
    if($data != null)
      return $data;
  }

  public function LeadContact(){
    $model = Contact::where([['lead_contact','=',1],['fk_company_id','=',$this->company_id],['status','=',1]])->first();
    return $model;
  }


}
