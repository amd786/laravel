<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
use Session;
use Validator;
use Input;
use Auth;
use Mail;
use App\Models\User;
use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Product;
use App\Library\Functions;
use App\Models\TempAssignParts;
use App\Models\AttributeValue;
use App\Models\AssignProd;
use App\Models\Attribute;
use App\Models\ProductClass;
use App\Models\Quotes;
use App\Models\QuoteDetails;
use App\Models\Excuse;
use App\Models\ExcuseQuotes;
use App\Models\EngQuoteDetails;
use App\Models\EngQuotes;
use App\Models\InvMoveInventory;
use App\Models\InvLocations;
use App\Models\InvCheckInOut;
use App\Models\InvCheckInOutRawMaterials;
use App\Models\InvRentReturn;
use App\Models\Datasheet;
use App\Models\PrPurchaseOrder;
use App\Models\InvRawMetrialsSuppliers;
use App\Models\PrSupplier;
use Illuminate\Pagination\Paginator;
use Config;

class AjaxController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('check')->except('SupplierInformation','GetDatasheet','GetCheckoutRejectedTable','GetCheckoutApprovedTable','GetOrderFulfillmentTable','GetCheckoutPendingTable','GetRawMaterialTable','GetInventory','GetDeliveredInventory','GetTransitInventory','GetSequenceNoDetails','EngSearchOrder','GetOrderInfo','GetAttributes','SearchOrder','SearchCommission','SearchSalesReport','GetMissedGraph','GetApprovedAttr','SelectContact','SelectClient','ReverseAction','SearchProducts','AttrValUncheck','CheckLeadContact','GetProducts','AttrValCheckbox','SelAllAttrVal','SelNoneAttrVal');
  }

  public function CheckLeadContact(){
    $company_id = Functions::test_input(Input::get('company_id'));
    $contact = Contact::where([['fk_company_id','=',$company_id],['lead_contact','=',1],['status','=',1]])->first();
    $company = Company::findOrFail($company_id);
    if(count($contact)>0){
      $data['result']="<label for='lead_contact'>Lead Contact</label><i class='fa fa-check green'></i> ".ucfirst($company->company_name)." has <b>".ucfirst($contact->fullName())."</b> as lead contact.";
      $data['status']='yes';
    }else{
      $data['status']="no";
    }
    echo json_encode($data);
  }

  public function ChnageSignPla(){

    $cid = Functions::test_input(Input::get('cid'));
    $sign = Functions::test_input(Input::get('sign'));

    $company = Company::where([['company_id','=',$cid],['status','=',1]])->first();
    $company->PLA_sign = $sign;
    $company->PLA_approved_by = Auth::User()->id;
    $company->save();
    $data['class'] = '';
    if($sign == 0)
    $data['class'] = 'red';
    if($sign == 1)
    $data['class'] = 'green';
    $data['name'] = "Approved By ".Auth::User()->fullName();
    echo json_encode($data);
  }

  public function SavePlaValue(){

    $cid = Functions::test_input(Input::get('cid'));
    $value = Functions::test_input(Input::get('value'));
    $company = Company::where([['company_id','=',$cid],['status','=',1]])->first();
    $company->price_list_adjustment = $value;
    $company->PLA_approved_by = Auth::User()->id;
    if($value == 0){
      $company->PLA_sign = null;
      $data['zero'] = 'zero';
    }
    $data['name'] = "Approved By ".Auth::User()->fullName();
    $company->save();
    echo json_encode($data);
  }

  public function GetProducts(){
    $p_class_id = Input::get('p_class_id');
    $save = Input::get('save');
    $product = Product::where('fk_product_class', '=', $p_class_id)->orderBy('product_name', 'asc')->get();
    $attribute_id = Attribute::where([
                                      ['status', 1],
                                      ['attribute_name', 'Sizes MM'],
                                      ['fk_product_class_id',$p_class_id]
                                    ])->pluck('id')->first();
    $sizes = AttributeValue::where([
                          ['status', 1],
                          ['fk_attribute_id',$attribute_id]
                        ])->orderBy('id', 'asc')->get();
    if($save=='false' && $p_class_id == null){
      $product = Product::orderBy('product_name', 'asc')->get();
      $sizes = AttributeValue::orderBy('id', 'asc')->get();
    }
    // also save this value in db.
    if($save == 'true'){
      $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
      if($tmp_assign == null){
        $tmp_assign = new TempAssignParts;
      }
      $tmp_assign->fk_product_class_id = $p_class_id;
      $tmp_assign->fk_user_id = Auth::User()->id;
      $tmp_assign->fk_product_id = null;
      $tmp_assign->attributes = null;
      $tmp_assign->save();
    }
    $reponse[0] = $product;
    $reponse[1] = $sizes;
    return \Response::json($reponse);
  }

  public function SaveProductId(){
    $p_id = Input::get('p_id');
    $class_id = Input::get('class_id');
    $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
    if($tmp_assign == null){
      $tmp_assign = new TempAssignParts;
    }
    $tmp_assign->fk_product_class_id = $class_id;
    $tmp_assign->fk_product_id = $p_id;
    $tmp_assign->attributes = null;
    $tmp_assign->fk_user_id = Auth::User()->id;
    $tmp_assign->save();
  }

  public function AttrValCheckbox(){
    $ids = array();
    $ids = Input::get('ids');
    if(count($ids)>0){
      $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
      if($tmp_assign == null){
        $tmp_assign = new TempAssignParts;
        $id_str = implode(",",$ids);
        $tmp_assign->attributes = $id_str;
        $tmp_assign->fk_user_id = Auth::user()->id;
        $tmp_assign->save();
      }else{
        if(empty($tmp_assign->attributes)){
          $id_str = implode(",",$ids);
          $tmp_assign->attributes = $id_str;
          $tmp_assign->save();
        }else{
          $ids_arrays = explode(",",$tmp_assign->attributes);
          $all_ids=array_merge($ids_arrays,$ids);
          $uni_ids = array_unique($all_ids);
          $uni_ids_str = implode(",",$uni_ids);
          $tmp_assign->attributes = $uni_ids_str;
          $tmp_assign->save();
        }
      }
    }
  }

  public function SelAllAttrVal(){
    $attr_id = Input::get('attr_id');
    $attr_val_ids = AttributeValue::where([['fk_attribute_id',$attr_id],['status',1]])->get();
    $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
    if($tmp_assign == null){
      $tmp_assign = new TempAssignParts;
      $id_array_empty = array();
      foreach($attr_val_ids as $attr_val_id){
        $id_array_empty[] = $attr_val_id->id;
      }
      $id_str = implode(",",$id_array_empty);
      $tmp_assign->attributes = $id_str;
      $tmp_assign->fk_user_id = Auth::user()->id;
      $tmp_assign->save();
    }else{
      if(empty($tmp_assign->attributes)){
        $id_array_empty = array();
        foreach($attr_val_ids as $attr_val_id){
          $id_array_empty[] = $attr_val_id->id;
        }
        $id_str = implode(",",$id_array_empty);
        $tmp_assign->attributes = $id_str;
        $tmp_assign->save();
      }else{
        $id_array = array();
        $db_ids_arrays = explode(",",$tmp_assign->attributes);
        foreach($attr_val_ids as $attr_val_id){
          $id_array[] = $attr_val_id->id;
        }
        $all_ids=array_merge($db_ids_arrays,$id_array);
        $uni_ids = array_unique($all_ids);
        $uni_ids_str = implode(",",$uni_ids);
        $tmp_assign->attributes = $uni_ids_str;
        $tmp_assign->save();
      }
    }
  }

  public function SelNoneAttrVal(){
    $attr_id = Input::get('id');
    $attr_val_ids = AttributeValue::where([['fk_attribute_id',$attr_id],['status',1]])->get();
    if($attr_val_ids !== null){
      $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
      if($tmp_assign != null){
        $ids = $tmp_assign->attributes;
        $ids_arr = explode(",",$ids);
        foreach($attr_val_ids as $id){
          if (($key = array_search($id->id, $ids_arr)) !== false) {
            unset($ids_arr[$key]);
          }
        }
        $ids_str = implode(",",$ids_arr);
        $tmp_assign->attributes = $ids_str;
        $tmp_assign->save();
      }
    }
  }

  public function AttrValUncheck(){
    $id = Input::get('id');

    $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
    if($tmp_assign != null){
      $ids = $tmp_assign->attributes;
      $ids_arr = explode(",",$ids);
      if (($key = array_search($id, $ids_arr)) !== false) {
        unset($ids_arr[$key]);
      }
      $ids_str = implode(",",$ids_arr);
      $tmp_assign->attributes = $ids_str;
      $tmp_assign->save();
    }
  }

  public function GenerateProducts(){
    set_time_limit(0);
    $data = array();
    $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
    // check if already exist
    // if($tmp_assign != null && AssignProd::where([['fk_product_id',$tmp_assign->fk_product_id],['status',1]])->count() > 0){
    //   Session::flash('error','Part number already generated.');
    //   $data['status'] = 'error';
    // }
    if($tmp_assign == null){
      Session::flash('error','Please select all fields.');
      $data['status'] = 'error';
    }else if(empty($tmp_assign->fk_product_class_id) || empty($tmp_assign->fk_product_id) || empty($tmp_assign->attributes)){
      Session::flash('error','Please select all fields.');
      $data['status'] = 'error';
    }else{
      $attr_ary = array();
      // get the attributes which user has selected
      $all_attr_vals = explode(",",$tmp_assign->attributes);
      foreach($all_attr_vals as $all_attr_val){
        $attr_val = AttributeValue::where([['id',$all_attr_val],['status',1]])->first();
        if($attr_val != null){
          $attr_ary[] = $attr_val->fk_attribute_id;
        }
      }
      $uni_attr_ids = array_unique($attr_ary);
      // get the attributes from db in sort order
      $attr_in_sort = Attribute::where([['fk_product_class_id',$tmp_assign->fk_product_class_id],['status',1]])->orderBy('sort_order')->get();
      if($attr_in_sort !== null){
        foreach($attr_in_sort as $attr){
          $all_attr_sort[] = $attr->id;
          $delete_arr[] = $attr->id;
        }
      }
      // end here
      // delete other attributes from array which user has not selected. then we will left with only those values which
      // user has selected in sort order
      foreach($uni_attr_ids  as $uni_attr_id){
        if(($key = array_search($uni_attr_id, $delete_arr)) !== false) {
            unset($delete_arr[$key]);
        }
      }
      foreach($delete_arr  as $del){
        if(($key = array_search($del, $all_attr_sort)) !== false) {
            unset($all_attr_sort[$key]);
        }
      }
      // end here
      $attr_final_arr = array();
      foreach($all_attr_sort as $id){
        $attr_final_arr[$id] = array();
        foreach($all_attr_vals as $all_attr_val){
          $attr_val = AttributeValue::where([['id',$all_attr_val],['status',1]])->first();
          if($attr_val != null && $id == $attr_val->fk_attribute_id){
            array_push($attr_final_arr[$id],$all_attr_val);
          }
        }
      }
       // got all the attribute in array
       $attr_final_arr = array_values($attr_final_arr);
      // save the code in db
      if(count($attr_final_arr) <= 11){
        $this->get_all_products_code($attr_final_arr,$tmp_assign->fk_product_id);
        Session::flash('success','Products genarted successfully.');
      }
      else{
        Session::flash('error','Max 11 attributes can be selected.');
        $data['status'] = 'error';
      }
       // now delete the record from temo table
       $tmp_assign->delete();
    }
    echo json_encode($data);
  }

  public function get_all_products_code($attr_final_arr,$prod_id){
    $count_el = count($attr_final_arr);
    foreach($attr_final_arr[0] as $attr1){
      if($count_el ==1)
      $this->save_data($prod_id,$attr1);
      if($count_el > 1){
        foreach($attr_final_arr[1] as $attr2){
          if($count_el ==2)
          $this->save_data($prod_id,$attr1.','.$attr2);
          if($count_el > 2){
            foreach($attr_final_arr[2] as $attr3){
              if($count_el ==3)
              $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3);
              if($count_el > 3){
                foreach($attr_final_arr[3] as $attr4){
                  if($count_el ==4)
                  $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4);
                  if($count_el > 4){
                    foreach($attr_final_arr[4] as $attr5){
                      if($count_el ==5)
                      $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5);
                      if($count_el > 5){
                        foreach($attr_final_arr[5] as $attr6){
                          if($count_el ==6)
                          $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5.','.$attr6);
                          if($count_el > 6){
                            foreach($attr_final_arr[6] as $attr7){
                              if($count_el ==7)
                              $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5.','.$attr6.','.$attr7);
                              if($count_el > 7){
                                foreach($attr_final_arr[7] as $attr8){
                                  if($count_el ==8)
                                  $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5.','.$attr6.','.$attr7.','.$attr8);
                                  if($count_el > 8){
                                    foreach($attr_final_arr[8] as $attr9){
                                      if($count_el ==9)
                                      $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5.','.$attr6.','.$attr7.','.$attr8.','.$attr9);
                                      if($count_el > 9){
                                        foreach($attr_final_arr[9] as $attr10){
                                          if($count_el ==10)
                                          $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5.','.$attr6.','.$attr7.','.$attr8.','.$attr9.','.$attr10);
                                          if($count_el > 10){
                                            foreach($attr_final_arr[10] as $attr11){
                                              if($count_el ==11)
                                              $this->save_data($prod_id,$attr1.','.$attr2.','.$attr3.','.$attr4.','.$attr5.','.$attr6.','.$attr7.','.$attr8.','.$attr9.','.$attr10.','.$attr11);
                                            } // endforeach $attr_final_arr[10]
                                          } // end if
                                        } // endforeach $attr_final_arr[9]
                                      }
                                    } // endforeach $attr_final_arr[8]
                                  }
                                } // endforeach $attr_final_arr[7]
                              }
                            } // endforeach $attr_final_arr[6]
                          }
                        } // endforeach $attr_final_arr[5]
                      }
                    } // endforeach $attr_final_arr[4]
                  }
                } // endforeach $attr_final_arr[3]
              }
            } // endforeach $attr_final_arr[2]
          }
        } // endforeach $attr_final_arr[1]
      }
    } // endforeach $attr_final_arr[0]
  } // end function

  public function save_data($prod_id,$ids){
    // first check if it already generated then dont generate again
    $count  = 0;
    $assign_prods = AssignProd::where([['fk_product_id',$prod_id],['sel_attr_val_id',$ids],['status',1]])->first();
    if($assign_prods !== null){
      $count++;
    }else{
      $ids_array = explode(",",$ids);
      $p_code = Product::where([['id',$prod_id],['status',1]])->first();
      $attr_code = array($p_code->part_number);
      $assign_prod = new AssignProd;
      $assign_prod->fk_user_id = Auth::User()->id;
      $assign_prod->fk_product_id = $prod_id;
      $assign_prod->fk_product_class_id = $p_code->fk_product_class;
      $assign_prod->sel_attr_val_id = $ids;
      foreach($ids_array as $id){
        $code = AttributeValue::where([['id',$id],['status',1]])->first();
        $attr_code[] = $code->attribute_code;
      }
      $attr_code_str = implode("-",$attr_code);
      $assign_prod->code = $attr_code_str;
      $assign_prod->save();
    }
    if($count ==1){
        Session::flash('warning',"$count product was already generated.");
    }else if($count >1){
      Session::flash('warning',"$count products were already generated.");
    }
  }

  public static function SaveNotesAssign(){
    $id = Functions::test_input(Input::get('id'));
    $notes = Functions::test_input(Input::get('notes'));
    $model = AssignProd::where([['id',$id],['status',1]])->first();
    $model->notes = $notes;
    $model->save();
  }

  public function DeleteAssignVal(){
    $data = array();
    $id = Functions::test_input(Input::get('id'));
    $assign_prod = AssignProd::where([['id',$id],['status',1]])->first();
    $assign_prod->approved_by =null;
    $assign_prod->approved_status = null;
    $assign_prod->approved_date = null;
    $assign_prod->save();
    Session::flash('success','Part number deleted Successfully.');
    $data['status'] = 'ok';
    $data['url'] = Input::get('url');
    echo json_encode($data);
  }

  public function DeleteAssignValViewAll(){
    $data = array();
    $id = Functions::test_input(Input::get('id'));
    $assign_prod = AssignProd::where([['id',$id],['status',1]])->first();
    $assign_prod->delete();
    Session::flash('success','Part number deleted Successfully.');
    $data['status'] = 'ok';
    $data['url'] = Input::get('url');
    echo json_encode($data);
  }

  public function ChangeTolerance(){
    $id = Functions::test_input(Input::get('id'));
    $value = Functions::test_input(Input::get('value'));
    $assign_prod = AssignProd::where([['id',$id],['status',1]])->first();
    $assign_prod->tolerance_sign = $value;
    $assign_prod->save();
    if($assign_prod->price !=null && $assign_prod->tolerance !=null && $assign_prod->tolerance_sign !== null){
      $price = Functions::cal_retail_price($assign_prod->id);
    }else{
      $price ='';
    }
    echo $price;
  }

  public function ApprovePrice(){
    $id = Input::get('id');
    $value = Functions::test_input(Input::get('value'));
    if(is_array($id)){
      foreach($id as $id_val){
        $assign_prod = AssignProd::where([['id',$id_val],['status',1]])->first();
        $assign_prod->approved_by = Auth::User()->id;
        $assign_prod->approved_date = date('Y-m-d H:i:s');
        if($value==0){ // disapprove product
          $assign_prod->approved_status = $value;
          $assign_prod->save();
          Session::flash('success','Product is Successfully disapproved.');
        }
        elseif($value==1){ // approve product
          if($assign_prod->price != null){
            $assign_prod->approved_status = $value;
            $assign_prod->save();
            Session::flash('success','Product is Successfully approved.');
          }else{
            Session::flash('error','Please fill all required values.');
          }
        }
      }
    }else{
      $assign_prod = AssignProd::where([['id',$id],['status',1]])->first();
      $assign_prod->approved_by = Auth::User()->id;
      $assign_prod->approved_date = date('Y-m-d H:i:s');
      if($value==0){ // disapprove product
        $assign_prod->approved_status = $value;
        $assign_prod->save();
        Session::flash('success','Product is Successfully disapproved.');
      }
      elseif($value==1){ // approve product
        if($assign_prod->price != null){
          $assign_prod->approved_status = $value;
          $assign_prod->save();
          Session::flash('success','Product is Successfully approved.');
        }else{
          Session::flash('error','Please fill all required values.');
        }
      }
    }
  }

  public function ApproveClassTole(){
    $id = Functions::test_input(Input::get('id'));
    $value = Functions::test_input(Input::get('value'));
    $prod_class = ProductClass::where([['id',$id],['status',1]])->first();
    if($prod_class->tolerance !== null && $prod_class->tolerance_sign !==null){
      $prod_class->approved_by = Auth::User()->id;
      $prod_class->approved_status = $value;
      $prod_class->approved_date = date('Y-m-d H:i:s');
      $prod_class->save();
      if($value ==1){
        Session::flash('success','Tolerance is Successfully approved.');
      }else{
        Session::flash('success','Tolerance is Successfully disapproved.');
      }
    }else{
      Session::flash('error','Please fill all required values.');
    }
  }

  public function SearchProducts(AssignProd $prods){
    $attributes = array();
    $products = array();
    $class_id = Functions::test_input(Input::get('class_id'));
    $p_id = Functions::test_input(Input::get('p_id'));
    $show_order = Functions::test_input(Input::get('show_order'));
    $has_bom = Functions::test_input(Input::get('has_bom'));
    $search = Functions::test_input(Input::get('search'));
    $prods = $prods->newQuery();
    if(!empty($search)){
      $attributes  = AttributeValue::where([['status','=',Config::get('constants.success')],['attribute_value','like','%'.$search.'%']])->orderBy('id')->pluck('id');
      $products = Product::where([['status','=',Config::get('constants.success')],['product_name','like','%'.$search.'%']])->orderBy('id')->pluck('id');
    }
    $prods->where(function($q) use ($class_id,$p_id,$show_order,$has_bom,$prods) {
      if(!empty($class_id)){
        $q->where('fk_product_class_id',$class_id);
      }
      if(!empty($p_id)){
        $q->where('fk_product_id',$p_id);
      }
    });
    if($has_bom>0){
      if($has_bom==1){
        $prods->join('bom_import_main','bom_import_main.fk_sku', '=', 'assign_products.code');
      }elseif($has_bom==2){
        $prods->leftjoin('bom_import_main','bom_import_main.fk_sku', '=', 'assign_products.code');
        $prods->whereNotIn('assign_products.code', function($query) {
             $query->select('bom_import_main.fk_sku')
                   ->from('bom_import_main');
         });
       }
    }
    if(count($attributes)>0){
      $prods->where(function($query) use ($attributes){
        foreach($attributes as $attribute) {
          $query->orWhere(DB::RAW("FIND_IN_SET(".$attribute.",sel_attr_val_id)"),'>',0);
        }
      });
    }
    if(count($products)>0){
      $prods->where(function($query) use ($products){
        foreach($products as $product) {
          $query->orWhere('fk_product_id',$product);
        }
      });
    }
    if(!empty($search)){
      $prods->where(function($query) use ($search){
        $query->orWhere('code','like','%'.$search.'%');
      });
    }
    $assign_prods = $prods->orderBy('assign_products.id',$show_order)->Paginate(10);
    $product = new Product();
    return view('product.search_products',['assign_prods'=>$assign_prods,'product'=>$product])->render();
  }

  public function ReverseAction(){
    $p_id = Functions::test_input(Input::get('pid'));
    $assign_prods = AssignProd::where([['fk_product_id',$p_id],['status',1]])->get();
    foreach($assign_prods as $assign_prod){
      $assign_prod->forceDelete();
    }
  }

  public function SelectClient(){
    $data = array();
    $client_id = Functions::test_input(Input::get('id'));
    $quote_id = Functions::test_input(Input::get('quote_id'));
    $query = Company::where('company_id',$client_id)->first();
    // address
    $street = (isset($query->street_name)?$query->street_name.", ":'');
    $city = (isset($query->city)?$query->city.", ":'');
    $country = (isset($query->country)?$query->country:'');
    $data['address'] = $street.$city.$country;
    // telephone
    $data['tel'] = $query->phone;
    $data['contacts'] = $query->Contacts()->get();

    // save data
    if($quote_id == null){
      $quote_modal = new Quotes;
    }else{
      $quote_modal = Quotes::where('id',$quote_id)->first();
    }
    $quote_modal->client_address = $data['address'];
    $quote_modal->client_tel = $data['tel'];
    $quote_modal->fk_company_id = $client_id;
    $quote_modal->fk_user_id = Auth::User()->id;
    $quote_modal->prepared_for = null;
    $quote_modal->contact_telephone = null;
    $quote_modal->save();

    $data['url'] = action('SaleController@CreateQuote',['id'=>$quote_modal->id]);

    echo json_encode($data);
  }

  public function SelectContact(){
    $data = array();
    $contact_id = Functions::test_input(Input::get('id'));
    $quote_id = Functions::test_input(Input::get('quote_id'));
    $query = Contact::where('contact_id',$contact_id)->first();
    $data['contacts'] = $query;
    // save data in db
    $quote = Quotes::where('id',$quote_id)->first();
    if($quote != null && $query != null){
      $quote->prepared_for = $query->contact_id;
      $quote->contact_telephone = $query->phone;
      $quote->save();
    }
    if(empty($contact_id) && !empty($quote_id)){
      $quote->prepared_for = null;
      $quote->contact_telephone = null;
      $quote->save();
    }
    echo json_encode($data);
  }

  public function GetApprovedAttr(){
    ini_set('max_execution_time', 0);
    $attr_ids = array();
    $html = "";
    $pid = Functions::test_input(Input::get('id'));
    $assign_prods =  AssignProd::where([['fk_product_id',$pid],['status',1],['approved_status',1]])->get();

    if(count($assign_prods) > 0 ){
      foreach($assign_prods as $assign_prod){
        $all_attr_vals = explode(',',$assign_prod->sel_attr_val_id);
        foreach($all_attr_vals as $all_attr_val){
          $query = AttributeValue::where([['id',$all_attr_val],['status',1]])->first();
          $attr_ids[$query->fk_attribute_id][] = $query->id;
        }
      }
    }

    if(count($attr_ids)>0){
      $loop_count = 1;
      foreach($attr_ids as $key=>$value){
        $temp = null;
        $attr_model = Attribute::where('id',$key)->first();
        $html .="<div class='col-sm-6 bold' style='padding-bottom:15px'>";
          $html .= "<label>$attr_model->attribute_name</label>";
          $html .="<select name='attr[$loop_count]' class='form-control attr_select_dd'>";
            foreach($value as $val){
              if($temp != $val){
                $attr_val = AttributeValue::where('id',$val)->first();
                $html .= "<option value='$attr_val->id'>$attr_val->attribute_value</option>";
                $temp = $val;
              }
            }
          $html .= "</select>";
        $html .="</div>";
        $loop_count++;
      }
    }else{
      $html.= "Approved product not found. Please approve it in price management section.";
    }
    echo $html;
  }

  public function CheckProductExist(){
    $data = array();
    $data['match'] = 'no';
    $data['status'] = '';
    $pid = Functions::test_input(Input::get('product_id'));
    $quoteid = Functions::test_input(Input::get('quoteid'));
    $qty = Functions::test_input(Input::get('qty'));
    $attrs_array = Input::get('attr');
    $sizes_t = Input::get('size_t');
    if($sizes_t == 'inches'){
      unset($attrs_array[1]);
    }else if($sizes_t == 'mm'){
      unset($attrs_array[0]);
    }
    $assign_prods =  AssignProd::where([['fk_product_id',$pid],['status',1],['approved_status',1]])->get();
    if(count($assign_prods) > 0 ){
      foreach($assign_prods as $assign_prod){
        $db_attr_array = explode(',',$assign_prod->sel_attr_val_id);
        $result=array_diff($attrs_array,$db_attr_array);
        if(count($result) == 0){
          $assign_prod_id = $assign_prod->id;
          $data['status'] = 'ok';
          $data['match'] = 'yes';
        }
      }
    }

    // save product
    if($data['status'] == 'ok' && $data['match'] == 'yes'){

      // check if this product is already added to quote details
      $quote_details_modal = QuoteDetails::where([['fk_quote_id',$quoteid],['fk_assign_product_id',$assign_prod_id],['status',1]])->first();
      if($quote_details_modal !== null){
        $quote_details_modal->qty += $qty;
        $quote_details_modal->save();
        Session::flash('success','Product is updated successfully.');
      }else{
        $quote_details = new QuoteDetails;
        $quote_details->fk_quote_id = $quoteid;
        $quote_details->fk_assign_product_id = $assign_prod_id;
        $quote_details->qty = $qty;
        $quote_details->status = 0;
        $quote_details->save();
        Session::flash('success','Product is added successfully.');
      }
      // Now add to enginnering quote details table
        for($i = 0; $i < $qty; $i++){
          $new_eng_quote_details = new EngQuoteDetails;
          $new_eng_quote_details->fk_quote_id = $quoteid;
          $new_eng_quote_details->fk_assign_product_id = $assign_prod_id;
          $new_eng_quote_details->status = 0;
          $new_eng_quote_details->save();
        }
    }
    echo json_encode($data);
  }

  public function SaveAjaxQuote(){
    $data = array();
    $value = Functions::test_input(Input::get('value'));
    $quote_id = Functions::test_input(Input::get('quote_id'));
    $data_model = Functions::test_input(Input::get('data_model'));
    if($quote_id != null){
      $quote = Quotes::where('id',$quote_id)->first();
      $quote->$data_model = $value;
      $quote->save();
      if($data_model == 'shipping_cost'){
        $data['final_price'] = Functions::get_total_price($quote_id);
      }
    }
    echo json_encode($data);
  }

  public function GetMissedGraph(){

    $duration = Functions::test_input(Input::get('duration'));

    $lastWeekStartTime = strtotime("last sunday",strtotime("-1 week"));
    $lastWeekEndTime = strtotime("this sunday",strtotime("-1 week"));
    $lastWeekStart = date("Y-m-d",$lastWeekStartTime);
    $lastWeekEnd = date("Y-m-d",$lastWeekEndTime);

    $first_day_month =  date("Y-m-d", strtotime("first day of previous month"));
    $last_day_month =  date("Y-m-d", strtotime("last day of previous month"));

    $excuses = ExcuseQuotes::where('status',1)
    ->select('fk_excuse_id', DB::raw('count(fk_excuse_id) as count'))
    ->groupBy('fk_excuse_id')
    ->get();
    // echo "<pre>";
    // print_r($excuses);
    if($duration == 'last_week'){
      $excuses = ExcuseQuotes::where('status',1)
      ->select('fk_excuse_id', DB::raw('count(fk_excuse_id) as count'))
      ->whereBetween( DB::raw('date(created_at)'), [$lastWeekStart, $lastWeekEnd] )
      ->groupBy('fk_excuse_id')
      ->get();
    }
    if($duration == 'last_month'){
      $excuses = ExcuseQuotes::where('status',1)
      ->select('fk_excuse_id', DB::raw('count(fk_excuse_id) as count'))
      ->whereBetween( DB::raw('date(created_at)'), [$first_day_month, $last_day_month] )
      ->groupBy('fk_excuse_id')
      ->get();
    }

    $data = array(
      array('Excuse','Count', array('role'=>'style') )
    );
    if(count($excuses)>0){
      foreach($excuses as $excuse){
        array_push($data,array($excuse->excuse->excuse,$excuse->count,'#D8D8D8'));
      }
    }else{
      array_push($data,array('No Data Found',0,'#D8D8D8'));
    }
    echo $data = json_encode($data);
  }

  public function SearchSalesReport(Quotes $q){
    $sales_person = Functions::test_input(Input::get('sales_person'));
    $client_dd = Functions::test_input(Input::get('company_id'));
    $salesreport_status = Functions::test_input(Input::get('status'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));

    $quote = $q->newQuery();
    $quote->where('order_status','<>' ,null);
    $quote->where('status',1);
    $quote->where('order_reviewed','yes');

    if(!empty($start_date) && !empty($end_date)){
      $quote->whereBetween( DB::raw('date(created_at)'), [$start_date, $end_date] );
    }
    if(!empty($sales_person)){
      $quote->where('fk_user_id',$sales_person);
    }
    if(!empty($client_dd)){
      $quote->where('fk_company_id',$client_dd);
    }
    if(!empty($salesreport_status)){
      $quote->where('order_status',$salesreport_status);
    }
    $quotes = $quote->Paginate(10);

    $new_quotes = new Quotes;
    return view('sale.search_salesreport',['quotes'=>$quotes,'new_quotes'=>$new_quotes])->render();
  }

  public function SearchCommission(Quotes $q){
    $sales_person = Functions::test_input(Input::get('sales_person'));
    $client_dd = Functions::test_input(Input::get('company_id'));
    $salesreport_status = Functions::test_input(Input::get('status'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));

    $quote = $q->newQuery();
    $quote->where('order_status','<>' ,null);

    if(!empty($start_date) && !empty($end_date)){
      $quote->whereBetween( DB::raw('date(created_at)'), [$start_date, $end_date] );
    }
    if(!empty($sales_person)){
      $quote->where('fk_user_id',$sales_person);
    }
    if(!empty($client_dd)){
      $quote->where('fk_company_id',$client_dd);
    }
    if(!empty($salesreport_status)){
      $quote->where('order_status',$salesreport_status);
    }
    $quotes = $quote->Paginate(10);

    $new_quotes = new Quotes;
    return view('sale.search_commission',['quotes'=>$quotes,'new_quotes'=>$new_quotes])->render();
  }

  public function SaveFlagStatus(){
    $quote_id = Functions::test_input(Input::get('quote_id'));
    $flag_status = Functions::test_input(Input::get('flag_status'));

    $quotes = Quotes::where('id',$quote_id)->first();
    $quotes->order_status = $flag_status;
    $quotes->order_postpone = null;
    $quotes->save();
    $excuse_quote = ExcuseQuotes::where('fk_quote_id',$quotes->id)->first();
    if($excuse_quote != null){
      $excuse_quote->delete();
    }
  }

  public function SaveConfirmStatus(){
    $quote_id = Functions::test_input(Input::get('quote_id'));

    $quotes = Quotes::where('id',$quote_id)->first();
    $quotes->order_status = 'won';
    $quotes->order_postpone = null;
    $quotes->save();
    $excuse_quote = ExcuseQuotes::where('fk_quote_id',$quotes->id)->first();
    if($excuse_quote != null){
      $excuse_quote->delete();
    }
  }

  public function SavePostponeStatus(){
    $quote_id = Functions::test_input(Input::get('quote_id'));
    $time = Functions::test_input(Input::get('time'));

    $quotes = Quotes::where('id',$quote_id)->first();
    $quotes->order_status = 'delay';
    $quotes->order_postpone = date("Y-m-d H:i:s",strtotime($time));
    $quotes->save();
    $excuse_quote = ExcuseQuotes::where('fk_quote_id',$quotes->id)->first();
    if($excuse_quote != null){
      $excuse_quote->delete();
    }
  }

  public function SearchOrder(Quotes $q){
    $show_delay = 'no';
    $sales_person = Functions::test_input(Input::get('sales_person'));
    $client_dd = Functions::test_input(Input::get('company_id'));
    $salesreport_status = Functions::test_input(Input::get('status'));

    $quote = $q->newQuery();
    $quote->where('order_status','<>' ,null);
    $quote->orderBy('id', 'desc');

    if(!empty($sales_person)){
      $quote->where('fk_user_id',$sales_person);
    }
    if(!empty($client_dd)){
      $quote->where('fk_company_id',$client_dd);
    }
    if(!empty($salesreport_status)){
      $quote->where('order_status',$salesreport_status);
      $show_delay = 'yes';
    }
    $quotes = $quote->Paginate(10);

    $new_quotes = new Quotes;
    return view('sale.search_order',['quotes'=>$quotes,'new_quotes'=>$new_quotes,'show_delay'=>$show_delay])->render();
  }

  public function SaveSortOrder(){
    $value = Functions::test_input(Input::get('value'));
    $attr_id = Functions::test_input(Input::get('attr_id'));
    $attribute = Attribute::where([['id',$attr_id],['status',1]])->first();
    $order_exists = Attribute::where([['id',$attr_id],['sort_order',$value],['status',1]])->first();
    if($value == ""){
      $value = null;
    }
    if(!empty($order_exists)){
      $order_exists->sort_order = $attribute->sort_order;
      if($order_exists->save()){
        $attribute->sort_order = $value;
        $attribute->save();
      }
    }
    else{
      $attribute->sort_order = $value;
      $attribute->save();
    }
  }

  public function SelectProductClass(){
    $data = array();
    $p_class_id = Input::get('p_class_id');
    // also save this value in db.
    $tmp_assign = TempAssignParts::where([['fk_user_id',Auth::User()->id],['status',1]])->first();
    if($tmp_assign == null){
      $tmp_assign = new TempAssignParts;
    }
    $tmp_assign->fk_product_class_id = $p_class_id;
    $tmp_assign->fk_user_id = Auth::User()->id;
    $tmp_assign->fk_product_id = null;
    $tmp_assign->attributes = null;
    $tmp_assign->save();

    $data['url'] = action('ProductController@AssignPart',['id'=>$p_class_id]);
    echo json_encode($data);
  }

  public function EditPrice(){
    $column_name = Input::get('name');
    $assign_prod = AssignProd::where('id',Input::get('pk'))->first();
    $assign_prod->$column_name = Input::get('value');
    $assign_prod->save();
  }

  public function GetAttributes(){
    $p_class_id = Input::get('p_class_id');
    $html = '';
    $attributes = Attribute::where([['status',1],['fk_product_class_id',$p_class_id]])->orderBy('sort_order')->get();
    return view('sale.attributes',['attributes'=>$attributes]);
      /*$html .= "<input type='hidden' class='sizes_t' name='size_t' value='inches'>";
      $html .=  "<div class='row'>";
        $html .=  "<div class='col-sm-12' style='padding:15px 0 25px 0;'>";
            if(count($attributes) > 0){
              //echo "<pre>"; print_r($attributes); die;
              foreach($attributes as $attribute){
                  $text_con = '';
                  $extra_class = '';
                  if($attribute->attribute_name == 'Sizes Inches'){
                      $text_con = "<a href='javascript:;' data-id='inches' class='change_attr_size'>Change to MM</a>";
                  }
                  if($attribute->attribute_name == 'Sizes MM'){
                      $text_con = "<a href='javascript:;' data-id='mm' class='change_attr_size'>Change to Inches</a>";
                      $extra_class = 'display:none;';
                  }
          $html .=      "<div class='col-md-4 attr_list' style='padding-bottom:15px;$extra_class'>";
            $html .=      "<div class='bold' style='margin-bottom:5px'>$attribute->attribute_name $text_con</div>";
            $html .=      "<select class='form-control attr_dd' name='attr[]' style='width:100%'>";
                      $attr_values = AttributeValue::where([['fk_attribute_id',$attribute->id],['status',1]])->get();
                    if(count($attr_values) > 0){
            $html .=          "<option value=''>Please select</option>";
                      foreach($attr_values as $attr_value){
              $html .=          "<option value='$attr_value->id'>$attr_value->attribute_value</option>";
                      }
                    }
            $html .=      "</select>";
            $html .=    "</div>";
              }
            }else{
              $html .= "<div class='text-center'>There are no attributes added to this class.";
            }
        $html .=  "</div>";
        $html .= "</div>";
        $html .= "";

      echo $html;*/
  }

  public function GetOrderInfo(){
    $data = array();
    $quote_id = Input::get('quote_id');
    $quotes = Quotes::where('id',$quote_id)->first();
    if($quotes != null){
      if(!empty($quotes->order_status) &&  $quotes->order_status == 'won'){
        $data['status_msg'] = 'Order was Approved.';
        $data['order_msg'] = ($quotes->ExcuseQuotes != null && $quotes->ExcuseQuotes->Excuse != null)?$quotes->ExcuseQuotes->Excuse->excuse:'';
        $data['notes'] = ($quotes->ExcuseQuotes != null && $quotes->ExcuseQuotes->notes != null)?$quotes->ExcuseQuotes->notes:'';
      }else if (!empty($quotes->order_status) && $quotes->order_status == 'lost'){
        $data['status_msg'] = 'Order was Cancelled.';
        $data['order_msg'] = ($quotes->ExcuseQuotes != null && $quotes->ExcuseQuotes->Excuse != null)?$quotes->ExcuseQuotes->Excuse->excuse:'';
        $data['notes'] = ($quotes->ExcuseQuotes != null && $quotes->ExcuseQuotes->notes != null)?$quotes->ExcuseQuotes->notes:'';
      }

      $data['order_status'] = $quotes->order_status;
    }

    echo json_encode($data);
  }

  public function SendNotify(){
    $quote_id = Input::get('quote_id');
    $quotes = Quotes::where('id',$quote_id)->first();
    if($quotes != null){
      Mail::send('email.send_notify', ['quotes'=>$quotes], function ($m) use ($quotes) {
          $m->from($quotes->OrderReviewedBy->email, 'Powerseal ERP');

          //$m->to($quotes->OrderStatusCHangedBy->email, null)->subject('Order Notification');
          $m->to('alskdj@yopmail.com', null)->subject('Order Notification');
      });

      $quotes->order_status = 'review';
      $quotes->save();
    }
  }

  public function EngSearchOrder(EngQuotes $q){
    $salesreport_status = Functions::test_input(Input::get('status'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));

    $quote = $q->newQuery();
    //$quote->where('eng_status','<>' ,null);
    $quote->where('status',1);

    if(!empty($salesreport_status) && $salesreport_status != 'undefined'){
      $quote->where('eng_status',$salesreport_status);
    }
    if(!empty($start_date) && !empty($end_date)){
      $quote->whereBetween( DB::raw('date(created_at)'), [$start_date, $end_date] );
    }
    $eng_quotes = $quote->Paginate(10);
    return view('engineering.eng_search_order',['eng_quotes'=>$eng_quotes,'new_quotes'=>new Quotes])->render();
  }

  public function GetSequenceNoDetails(){
    $assign_prods = array();
    $quote_modal = array();
    $quote_details_modal  = array();
    $work_order = Functions::test_input(Input::get('work_order'));
    $quote_detail = Quotes::where([['work_order_no',$work_order],['status',1]])->first();
    if($quote_detail !== null){
      $quote_id  = $quote_detail->id;
      $quote_details_modal = QuoteDetails::where([['fk_quote_id',$quote_id],['status',1]])->first();
      if($quote_details_modal !== null){
        $assign_prods = AssignProd::where([['id',$quote_details_modal->fk_assign_product_id],['status',1]])->get();
        $quote_modal = Quotes::where([['id',$quote_details_modal->fk_quote_id],['status',1]])->first();
        return view('inventory.sequence_no_details',['assign_prods'=>$assign_prods,'quote_modal'=>$quote_modal,'quote_details_modal'=>$quote_details_modal])->render();
      }else{
        return 0;
      }
    }else{
      return 0;
    }
  }

  public function GetTransitInventory(InvMoveInventory $inventory){
    $search = "";
    $location_ids = array();
    $search = Functions::test_input(Input::get('search'));
    $inventory = $inventory->newQuery();
    if(!empty($search)){
      $location_ids  = InvLocations::where([['status','=',1],['location_name','like','%'.$search.'%']])->orderBy('id')->pluck('id');
    }
    $inventory->where(function ($query) {
        $query->where('status',1)
        ->where('is_delivered',0);
      })->where(function($query) use ($location_ids,$search){
            $query->orWhere('send_date', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('mi_sequence_no', 'like', '%'.strtoupper($search).'%')
            ->orWhere('tracking_no', 'like', '%'.$search.'%');
            foreach($location_ids as $location_id) {
              $query->orWhere('from_location', '=', $location_id);
              $query->orWhere('to_location', '=', $location_id);
            }
          });

    $transit_inventory_data = $inventory->Paginate(10);
    return view('inventory.transit_inventory_table',['transit_inventory_data'=>$transit_inventory_data])->render();
  }

  public function GetDeliveredInventory(InvMoveInventory $inventory){
    $search = "";
    $location_ids = array();
    $search = Functions::test_input(Input::get('search'));
    $inventory = $inventory->newQuery();
    if(!empty($search)){
      $location_ids  = InvLocations::where([['status','=',1],['location_name','like','%'.$search.'%']])->orderBy('id')->pluck('id');
    }
    $inventory->where(function ($query) {
        $query->where('status',1)
        ->where('is_delivered',1);
      })->where(function($query) use ($location_ids,$search){
            $query->orWhere('send_date', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('mi_sequence_no', 'like', '%'.strtoupper($search).'%')
            ->orWhere('tracking_no', 'like', '%'.$search.'%');
            foreach($location_ids as $location_id) {
              $query->orWhere('from_location', '=', $location_id);
              $query->orWhere('to_location', '=', $location_id);
            }
          });

    $delivered_inventory_data = $inventory->Paginate(10);
    return view('inventory.delivered_inventory_table',['delivered_inventory_data'=>$delivered_inventory_data])->render();
  }

  public function GetInventory(InvCheckInOut $inventory) {
    $search = "";
    $location_ids = array();
    $search = Functions::test_input(Input::get('search'));
    $inventory = $inventory->newQuery();

    if (!empty($search)) {
        $location_ids = InvLocations::where([['status', '=', Config::get('constants.success')], ['location_name', 'like', '%' . $search . '%']])->orderBy('id')->pluck('id');
    }
    $inventory->where(function ($query) {
        $query->where('status', 1);
    })->where(function($query) use ($location_ids, $search) {
        $query->orWhere('sku', 'like', '%'.$search . '%')
                ->orWhere('quantity', 'like', '%' . strtoupper($search) . '%');
        foreach ($location_ids as $location_id) {
            $query->orWhere('location', '=', $location_id);
        }
    });
    $view_inventory_data = $inventory->Paginate(10);
    return view('inventory.view_inventory_table', ['view_inventory_data' => $view_inventory_data])->render();
  }
  public function GetRawMaterialTable(InvCheckInOutRawMaterials $check_in_out_raw_materials){
    $search = Functions::test_input(Input::get('search'));
    $check_in_out_raw_materials = $check_in_out_raw_materials->newQuery();
    $location_id  = 0;
    if(!empty($search)){
      $location_id  = InvLocations::where([['status','=',Config::get('constants.success')],['location_name','like','%'.$search.'%']])->orderBy('id')->pluck('id');
    }
    if(count($location_id)==0){
      $location_id  = 0;
    }
    $check_in_out_raw_materials->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('in_out_status',Config::get('constants.check_in'));
      })->where(function($query) use ($location_id,$search){
            $query->orWhere('sku', 'like', '%'.$search.'%');
            $query->orWhere('location', '=', $location_id);
          });
    $check_in_out_raw_materials = $check_in_out_raw_materials->groupBy('sku')->Paginate(10);
    $inv_raw_metrials_suppliers = new InvRawMetrialsSuppliers;
    return view('inventory.raw_materials_check_in_out_table', ['check_in_out_raw_materials' => $check_in_out_raw_materials,'inv_raw_metrials_suppliers'=>$inv_raw_metrials_suppliers])->render();
  }
  public function GetCheckoutPendingTable(Request $request){
    $page = Input::get('page', 1);
    $paginate = 10;
    $search = "";
    $search = Functions::test_input(Input::get('search'));
    $pending_checkout_data = new InvCheckInOut;
    $pending_raw_materials = new InvCheckInOutRawMaterials;
    $pending_rent_return = new InvRentReturn;
    $pending_checkout_data = $pending_checkout_data->newQuery();
    $pending_checkout_data->where(function ($query) {
      $query->where('status',Config::get('constants.success'))
      ->where('in_out',Config::get('constants.check_out'))
      ->where('approval_status',Config::get('constants.checkout_approval_pending'));
    })->where(function($query) use ($search){
          $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
          ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
          ->orWhere('request_number', 'like', '%'.$search.'%');
        });
    $pending_checkout_data = $pending_checkout_data->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_check_in_out' as table_name"));

    $pending_raw_materials = $pending_raw_materials->newQuery();
    $pending_raw_materials->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('in_out_status',Config::get('constants.check_out'))
        ->where('approval_status',Config::get('constants.checkout_approval_pending'));
      })->where(function($query) use ($search){
            $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
            ->orWhere('request_number', 'like', '%'.$search.'%');
          });
    $pending_raw_materials = $pending_raw_materials->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_check_in_out_raw_materials' as table_name"));

    $pending_rent_return = $pending_rent_return->newQuery();
    $pending_rent_return->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('approval_status',Config::get('constants.checkout_approval_pending'));
      })->where(function($query) use ($search){
            $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
            ->orWhere('request_number', 'like', '%'.$search.'%');
          });
    $pending_rent_return = $pending_rent_return->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_rent_return' as table_name"))->union($pending_checkout_data)->union($pending_raw_materials)->get()->toArray();
    $offSet = ($page * $paginate) - $paginate;
    $itemsForCurrentPage = array_slice($pending_rent_return, $offSet, $paginate, true);
    $pending_requests = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($pending_rent_return), $paginate, $page, ['path' => $request->url(), 'query' => $request->query()]);
    $inv_rent_return  = new InvRentReturn;
    return view('inventory.checkout_approval_pending_table', ['pending_requests' => $pending_requests,'inv_rent_return' => $inv_rent_return])->render();
  }
  public function GetOrderFulfillmentTable(Quotes $quote){
    $search = Functions::test_input(Input::get('search'));
    $quote = $quote->newQuery();
    $quote->where(function ($query) {
        $query->where('status',Config::get('constants.success'));
      })->where(function($query) use ($search){
            $query->orWhere('id', 'like', '%'.$search.'%');
            $query->orWhere('created_at', 'like', '%'.Functions::dbDateFormat($search).'%');
          });
    $quotes = $quote->Paginate(10);
    $inv_check_in_out = new InvCheckInOut;
    return view('inventory.order_fulfillment_table',['quotes'=>$quotes,'inv_check_in_out'=>$inv_check_in_out]);
  }
  public function GetCheckoutApprovedTable(Request $request){
    $page = Input::get('page', 1);
    $paginate = 10;
    $search = "";
    $search = Functions::test_input(Input::get('search'));
    $approved_checkout_data = new InvCheckInOut;
    $approved_raw_materials = new InvCheckInOutRawMaterials;
    $approved_rent_return = new InvRentReturn;
    $approved_checkout_data = $approved_checkout_data->newQuery();
    $approved_checkout_data->where(function ($query) {
      $query->where('status',Config::get('constants.success'))
      ->where('in_out',Config::get('constants.check_out'))
      ->where('approval_status',Config::get('constants.checkout_approval_approved'));
    })->where(function($query) use ($search){
          $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
          ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
          ->orWhere('request_number', 'like', '%'.$search.'%');
        });
    $approved_checkout_data = $approved_checkout_data->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_check_in_out' as table_name"));

    $approved_raw_materials = $approved_raw_materials->newQuery();
    $approved_raw_materials->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('in_out_status',Config::get('constants.check_out'))
        ->where('approval_status',Config::get('constants.checkout_approval_approved'));
      })->where(function($query) use ($search){
            $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
            ->orWhere('request_number', 'like', '%'.$search.'%');
          });
    $approved_raw_materials = $approved_raw_materials->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_check_in_out_raw_materials' as table_name"));

    $approved_rent_return = $approved_rent_return->newQuery();
    $approved_rent_return->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('approval_status',Config::get('constants.checkout_approval_approved'));
      })->where(function($query) use ($search){
            $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
            ->orWhere('request_number', 'like', '%'.$search.'%');
          });
    $approved_rent_return = $approved_rent_return->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_rent_return' as table_name"))->union($approved_checkout_data)->union($approved_raw_materials)->get()->toArray();
    $offSet = ($page * $paginate) - $paginate;
    $itemsForCurrentPage = array_slice($approved_rent_return, $offSet, $paginate, true);
    $approved_requests = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($approved_rent_return), $paginate, $page, ['path' => $request->url(), 'query' => $request->query()]);
    $inv_rent_return  = new InvRentReturn;
    return view('inventory.checkout_approval_approved_table', ['approved_requests' => $approved_requests,'inv_rent_return'=>$inv_rent_return])->render();
  }
  public function GetCheckoutRejectedTable(Request $request){
    $page = Input::get('page', 1);
    $paginate = 10;
    $search = "";
    $search = Functions::test_input(Input::get('search'));
    $rejected_checkout_data = new InvCheckInOut;
    $rejected_raw_materials = new InvCheckInOutRawMaterials;
    $rejected_rent_return = new InvRentReturn;
    $rejected_checkout_data = $rejected_checkout_data->newQuery();
    $rejected_checkout_data->where(function ($query) {
      $query->where('status',Config::get('constants.success'))
      ->where('in_out',Config::get('constants.check_out'))
      ->where('approval_status',Config::get('constants.checkout_approval_rejected'));
    })->where(function($query) use ($search){
          $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
          ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
          ->orWhere('request_number', 'like', '%'.$search.'%');
        });
    $rejected_checkout_data = $rejected_checkout_data->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_check_in_out' as table_name"));

    $rejected_raw_materials = $rejected_raw_materials->newQuery();
    $rejected_raw_materials->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('in_out_status',Config::get('constants.check_out'))
        ->where('approval_status',Config::get('constants.checkout_approval_rejected'));
      })->where(function($query) use ($search){
            $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
            ->orWhere('request_number', 'like', '%'.$search.'%');
          });
    $rejected_raw_materials = $rejected_raw_materials->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_check_in_out_raw_materials' as table_name"));

    $rejected_rent_return = $rejected_rent_return->newQuery();
    $rejected_rent_return->where(function ($query) {
        $query->where('status',Config::get('constants.success'))
        ->where('approval_status',Config::get('constants.checkout_approval_rejected'));
      })->where(function($query) use ($search){
            $query->orWhere('received_on', 'like', '%'.Functions::dbDateFormat($search).'%')
            ->orWhere('sku', 'like', '%'.strtoupper($search).'%')
            ->orWhere('request_number', 'like', '%'.$search.'%');
          });
    $rejected_rent_return = $rejected_rent_return->select('id','request_number','sku','quantity','requested_by','received_on','created_at',DB::raw("'inv_rent_return' as table_name"))->union($rejected_checkout_data)->union($rejected_raw_materials)->get()->toArray();
    $offSet = ($page * $paginate) - $paginate;
    $itemsForCurrentPage = array_slice($rejected_rent_return, $offSet, $paginate, true);
    $rejected_requests = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($rejected_rent_return), $paginate, $page, ['path' => $request->url(), 'query' => $request->query()]);
    $inv_rent_return  = new InvRentReturn;
    return view('inventory.checkout_approval_rejected_table', ['rejected_requests' => $rejected_requests,'inv_rent_return'=>$inv_rent_return])->render();
  }
  public function GetDatasheet(Datasheet $data){
    $sales_person = Functions::test_input(Input::get('sales_person'));
    $client = Functions::test_input(Input::get('client'));
    $po_status = Functions::test_input(Input::get('status'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));

    $datasheet = $data->newQuery();
    $datasheet->where('status',Config::get('constants.success'));

    if(!empty($start_date) && !empty($end_date)){
      $datasheet->whereBetween( DB::raw('date(date)'), [$start_date, $end_date] );
    }
    if(!empty($po_status)){
      if($po_status==1){
        $datasheet->where('po','<>','');
      }else{
        $datasheet->where('po','=','');
      }
    }
    if(!empty($sales_person)){
      $datasheet->where('salesperson',$sales_person);
    }
    if(!empty($client)){
      $datasheet->where('client',$client);
    }
    $datasheets = $datasheet->orderBy('id','desc')->Paginate(10);
    return view('sale.datasheet_table', ['datasheets' => $datasheets])->render();
  }

  public function SupplierInformation(PrSupplier $supplier){
    $id  = Functions::test_input(Input::get('id'));
    $module  = Functions::test_input(Input::get('module'));
    if($id>0){
      $supplier = PrSupplier::where([
                                      ['status',Config::get('constants.success')],
                                      ['id',$id]
                                    ])->first();
      return view('purchase.supplier_information',['supplier'=>$supplier,'module'=>$module])->render();
    }
  }

}
