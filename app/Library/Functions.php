<?php
namespace App\Library;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Role;
use App\Models\Module;
use App\Models\Contact;
use App\Models\Company;
use App\Models\Permission;
use Auth;
use App\Models\AttributeValue;
use App\Models\AssignProd;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\Quotes;
use App\Models\QuoteDetails;
use App\Models\PrSupplier;
use App\Models\PrSupplierContact;
use App\Models\PrSupplierRating;
use App\Models\PrPurchaseOrder;
use Config;
use Session;
use Validator;
use Input;
use DB;
use Illuminate\Http\Request;
use App\Models\ClientSettings;

class Functions{

  public static function date_format($date){
    return date('d-M-Y', strtotime($date));
  }

  public static function date_format2($date){
    return date('d/m/Y', strtotime($date));
  }

  public static function check($module_id,$permission_value=null){
    $pm = Permission::where([['fk_role_id','=',Auth::User()->fk_role_id],['fk_module_id','=',$module_id]])->first();
    if(count($pm)>0){
      if($pm->permission == 2){
        return true;
      }else if($pm->permission == 1){
        if($permission_value == 'R'){
          return true;
        }
      }
    }else{
      return false;
    }
  }

  public static function get_checked($sub_module_id,$role_id,$value){
    $permission = Permission::where([['fk_role_id','=',$role_id],['fk_module_id','=',$sub_module_id],['permission','=',$value]])->first();
    if($permission != null){
      echo "checked";
    }
  }

  public static function set_active($path, $active = 'active open') {
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
  }

  public static function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public static function check_lead_contact($company_id,$contact_id=null){
    $contacts = Contact::where([['fk_company_id','=',$company_id],['lead_contact','=',1],['status','=',1]])->first();
    if($contacts != null && $contacts->contact_id != $contact_id){
      return false;
    }else{
      return true;
    }
  }

  public static function previous_url($curr_controller,$curr_action){
    switch($curr_controller){
      case 'UserController' : {
        switch ($curr_action) {
            case 'EditUser':
              return action('UserController@AllUser');
              break;
            case 'EditRole':
              return action('UserController@roles');
              break;

            default:
              return action('UserController@index');
              break;
        }
      } // case ends here
      case 'CrmController' : {
        switch ($curr_action) {
            case 'ViewAddressbook':
              return action('CrmController@ShowAllAddress');
              break;
            case 'ViewContact':
              return action('CrmController@ShowAllAddress');
              break;

            default:
              return action('CrmController@index');
              break;
        }
      } // case ends here
      case 'ProductController' : {
        switch ($curr_action) {
          case 'Attributes':
            return action('ProductController@index');
            break;
          case 'EditAttribute':
            return action('ProductController@Attributes');
            break;
          case 'AssignPart':
            return action('ProductController@AssignPartNo');
            break;
          case 'ViewAllDetails':
            return action('ProductController@ViewAll');
            break;
          case 'SetProductPrice':
            return action('ProductController@PriceManagement');
            break;
          case 'ProductsWithoutApproval':
            return action('ProductController@SetProductPrice');
            break;
          case 'ProductsWithApproval':
            return action('ProductController@SetProductPrice');
            break;
            case 'PriceViewAll':
            return action('ProductController@SetProductPrice');
            break;
          case 'ProductClassTolerance':
            return action('ProductController@PriceManagement');
            break;
          case 'ProductViewAll':
            return action('ProductController@PriceManagement');
            break;
          default:
            return action('ProductController@index');
            break;
        }
      } // case ends here
      case 'SaleController' : {
        switch ($curr_action) {
            case 'PaymentTerms':
              return action('SaleController@SalesSetting');
              break;
            case 'Taxes':
              return action('SaleController@SalesSetting');
              break;

            case 'Excuses':
              return action('SaleController@SalesSetting');
              break;

            case 'CreateDatasheet':
              return action('SaleController@Datasheet');
              break;

            case 'ViewDatasheet':
              return action('SaleController@Datasheet');
              break;

            default:
              return action('SaleController@index');
              break;
        }
      } // case ends here
      case 'EngineeringController' : {
        switch ($curr_action) {
            case 'OnHoldDesc':
              return action('EngineeringController@OnHold');
              break;

            default:
              return action('EngineeringController@index');
              break;
        }
      } // case ends here
      case 'InventoryController' : {
        switch ($curr_action) {
          case 'SaveMoveInventory':
            return action('InventoryController@MoveInventory');
            break;
          case 'TrackInventoryDetail':
            return action('InventoryController@TrackInventory');
            break;
          case 'UnitManagement':
            return action('InventoryController@RawMaterials');
            break;
          case 'ViewRawMaterialsInventory':
            return action('InventoryController@RawMaterials');
            break;
          case 'AddRawMaterial':
            return action('InventoryController@RawMaterials');
            break;
          case 'CheckInOutRawMaterials':
            return action('InventoryController@RawMaterials');
            break;
          case 'OrderDetails':
            return action('InventoryController@OrderFulfillment');
            break;
          case 'RawMaterial':
            return action('InventoryController@ViewRawMaterialsInventory');
            break;
          default:
            return action('InventoryController@index');
            break;
        }
      } // case ends here
      case 'PurchaseController' : {
        switch ($curr_action) {
          case 'AddSupplierContact':
            return action('PurchaseController@SupplierAddressBook');
            break;
          case 'AddSupplier':
            return action('PurchaseController@SupplierAddressBook');
            break;
          case 'ChooseSupplier':
            return action('PurchaseController@CreatePurchaseOrder');
            break;
          case 'ConfirmOrder':
            return action('PurchaseController@CreatePurchaseOrder');
            break;
          case 'SupplierProfile':
            return action('PurchaseController@SupplierAddressBook');
            break;
          default:
            return action('PurchaseController@index');
            break;
        }
      } // case ends here
      case 'AccountingController' : {
        switch ($curr_action) {
          case 'PurchaseOrderDetails':
            return action('AccountingController@PurchaseOrders');
            break;
          case 'AddSupplierContact':
            return action('AccountingController@Accounts');
            break;
          case 'AddSupplier':
            return action('AccountingController@Accounts');
            break;
          case 'SupplierProfile':
            return action('AccountingController@Accounts');
            break;
          case 'CreateInvoice':
            return action('AccountingController@Invoices');
            break;
          case 'AccountsPayable':
            return action('AccountingController@Transactions');
            break;
          case 'AccountsReceivable':
            return action('AccountingController@Transactions');
            break;
          default:
            return action('AccountingController@index');
            break;
        }
      } // case ends here
      case 'ClientController' : {
        switch ($curr_action) {
          case 'EditUser':
            return action('ClientController@Users');
            break;
          case 'AddUser':
            return action('ClientController@Users');
            break;
          case 'NewOrder':
            return action('ClientController@Orders');
            break;
          case 'OrderHistoryDetail':
            return action('ClientController@OrderHistory');
            break;
          case 'OrderProgressDetail':
            return action('ClientController@OrderProgress');
            break;
          case 'OrderHistory':
            return action('ClientController@Orders');
            break;
          case 'ConfirmOrder':
            return action('ClientController@NewOrder');
            break;
          case 'PlaceOrder':
            return action('ClientController@NewOrder');
            break;
          case 'EditDetails':
            $id = request()->segment(3);
            return action('ClientController@EditUser',['id'=>$id]);
            break;
          default:
            return action('ClientController@home');
            break;
        }
      } // case ends here
      case 'OrdersController' : {
        switch ($curr_action) {
          default:
            return action('OrdersController@index');
            break;
        }
      } // case ends here
    }
  }

  public static function get_prod_attr_disp($ids){
    if(!empty($ids)){
      $ids = explode(',',$ids);
      foreach($ids as $id){
        $attr_val = AttributeValue::where([['id',$id],['status',1]])->first();
        echo "<th>";
        if(count($attr_val)>0){
          echo isset($attr_val->attribute) ? $attr_val->attribute->attribute_name : '';
        }
        echo "</th>";
      }
    }
  }

  public static function get_prod_attr_val_disp($ids){
    if(!empty($ids)){
      $ids = explode(',',$ids);
      foreach($ids as $id){
        $attr_val = AttributeValue::where([['id',$id],['status',1]])->first();
        echo "<td>";
        if(count($attr_val)>0){
          echo isset($attr_val->attribute_value) ? $attr_val->attribute_value : '';
        }
        echo "</td>";
      }
    }
  }

  public static function cal_retail_price($id){
    if(empty($id)){
      return '';
    }
    $data = array();
    $assign_prod = AssignProd::where([['id',$id],['status',1]])->first();
    $prod_class = ProductClass::where([['id',$assign_prod->fk_product_class_id],['status',1]])->first();
    if($assign_prod->price !==null && $assign_prod->tolerance !=null && $assign_prod->tolerance_sign !== null){
      $value = $assign_prod->price*$assign_prod->tolerance/100;
      if($assign_prod->tolerance_sign === 1)
        $data['retail_price'] = $assign_prod->price + $value;
      else
        $data['retail_price'] = $assign_prod->price - $value;
    }else if($prod_class->tolerance != null && $prod_class->tolerance_sign !== null){
      $value = $assign_prod->price*$prod_class->tolerance/100;
      if($prod_class->tolerance_sign === 1)
        $data['retail_price'] = $assign_prod->price + $value;
      else
        $data['retail_price'] = $assign_prod->price - $value;
    }else if($assign_prod->price !==null){
      $data['retail_price'] = $assign_prod->price;
    }else{
      $data['retail_price'] = '';
    }
    //echo $data['retail_price'];
    return (!empty($data['retail_price']))?"$".ceil($data['retail_price']):'';
  }

  public static function get_variants($id){
    $val = array();
    $data_str = null;
    $assign_prod = AssignProd::where([['id',$id],['status',1]])->first();
    if($assign_prod !== null){
      $ids_arr = explode(',',$assign_prod->sel_attr_val_id);
      foreach($ids_arr as $ids){
        $attr_val = AttributeValue::where([['id',$ids],['status',1]])->first();
        $val[]=isset($attr_val->attribute_value)?$attr_val->attribute_value:'';
      }
      $data_str = implode(" / ",$val);
    }
    return utf8_encode($data_str);
  }

  public static function product_affected($id){
    if(empty($id)){
      return '';
    }
    $p_name = array();
    $products = Product::where([['fk_product_class',$id],['status',1]])->get();
    foreach($products as $product){
      $p_name[] = $product->product_name;
    }
    $p_name_str = implode(", ",$p_name);
    if(strlen($p_name_str)>120){
      $p_name_f = substr($p_name_str,0,120)." ...";
    }else{
      $p_name_f = $p_name_str;
    }
    echo (empty($p_name_f)?'N/A':$p_name_f);
  }

  public static function get_tax($quote_id){
    $p_total = null;
    $tax = null;
    if($quote_id != null){
      $quotes = Quotes::where('id',$quote_id)->first();
      $quote_details = $quotes->QuoteDetails()->get();
      if(count($quote_details)>0){
        foreach($quote_details as $quote_detail){
          $p_total += $quote_detail->qty*$quote_detail->AssignProd->price;
        }
        $tax_percent = ($quotes->Tax != null)?$quotes->Tax->tax:null;
        $tax = ($tax_percent != null)?($p_total * $tax_percent)/100:'';
      }
    }
    return ($tax != null)?round($tax,2):null;
  }

  public static function get_subtotal($quote_id){
    $p_total = null;
    if($quote_id != null){
      $quotes = Quotes::where('id',$quote_id)->first();
      $quote_details = $quotes->QuoteDetails()->get();
      if(count($quote_details)>0){
        foreach($quote_details as $quote_detail){
          $p_total += $quote_detail->qty*$quote_detail->AssignProd->price;
        }
      }
    }
    return ($p_total != null)?round($p_total,2):null;
  }

  public static function get_pla($quote_id){
    $data = array();
    $data['key'] = 'Discount';
    $data['value'] = '-';
    $data['pla_sign'] = null;
    $data['pla'] = null;

    $quotes = Quotes::where('id',$quote_id)->first();
    $pla_sign = $quotes->Company->PLA_sign;

    if($pla_sign !== null){
      if($pla_sign === 0){
        $data['key'] = 'Discount';
        $data['value'] = $quotes->Company->price_list_adjustment."%";
        $data['pla_sign'] = $quotes->Company->PLA_sign;
        $data['pla'] = $quotes->Company->price_list_adjustment;
      }else{
        $data['key'] = 'Markup';
        $data['value'] = $quotes->Company->price_list_adjustment."%";
        $data['pla_sign'] = $quotes->Company->PLA_sign;
        $data['pla'] = $quotes->Company->price_list_adjustment;
      }
    }
    return $data;
  }

  public static function get_total_price($quote_id){
    $final_total = null;
    if($quote_id != null){
      // check if tax dropdown is selected. only then show total
      $tax = self::get_tax($quote_id);
      if($tax != null){
        $sub_total = self::get_subtotal($quote_id);
        // check if company is given discount or markup
        $data = self::get_pla($quote_id);
        if($data['pla_sign'] === 0){
          $discount = ($sub_total*$data['pla'])/100;
          $total = $sub_total - $discount;
        }else if($data['pla_sign'] === 1){
          $markup = ($sub_total*$data['pla'])/100;
          $total = $sub_total + $markup;
        }else{
          $total = $sub_total;
        }

        // now on subtotal apply tax and shipping cost
        $total = $total + $tax;
        // add shipping cost
        $quotes = Quotes::where('id',$quote_id)->first();
        $final_total = $total + $quotes->shipping_cost;
      }
    }
    return ($final_total !== null)?round($final_total,2):null;
  }

  public static function update_price($key,$price){
    $assign_prods = AssignProd::whereNull('approved_by')
                    ->whereNull('approved_date')
                    ->where('status',1)
                    ->where('approved_status',0)->orWhereNull('approved_status')
                    ->get();
    foreach($assign_prods as $index=>$assign_prod){
        if($key == $index){
          $assign_prod->price = $price;
          $assign_prod->save();
        }
    }
  }

  public static function generateRandomString($length = 6) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

  public static function customDateFormat($date){
    return date('Y/m/d', strtotime($date));
  }

  public static function dbDateFormat($date){
    return date('Y-m-d', strtotime($date));
  }

  public static function readableDateFormat($date){
    return date('l, F j - Y', strtotime($date));
  }

  // suppliers functions
  public static function saveSupplier($request){
    // validation rules
    $rules = [
      'supplier_name'=>'required',
      'fk_term_id'=>'required'
    ];
    $messsages = [
      'fk_term_id.required'=>'Please select payment terms.'
    ];
    $validator = Validator::make(Input::all(), $rules, $messsages);
    if ($validator->fails()){
      $messages = $validator->messages();
      if (!empty($messages)) {
        foreach ($messages->all() as $error) {
          return ['validation',$error];
        }
      }
    }

    //validation rules ended
    $data = $request->all();
    $model = new PrSupplier();
    $data['status'] = Config::get('constants.success');
    $inserted_id = $model->create($data)->id;
    if($inserted_id>0){
      return ['success','Supplier Added successfully'];
    }else{
      return ['error',Config::get('constants.error_message')];
    }
  }

  public static function saveSupplierContact($request){
    // validation rules
    $rules = [
      'fk_supplier_id'=>'required',
      'first_name'=>'required',
      'last_name'=>'required',
      'email'=>'required|email'
    ];
    $messsages = [
      'fk_supplier_id.required'=>'Select Supplier field is required.'
    ];
    $validator = Validator::make(Input::all(), $rules, $messsages);
    if ($validator->fails()){
      $messages = $validator->messages();
      if (!empty($messages)) {
        foreach ($messages->all() as $error) {
          return ['validation',$error];
        }
      }
    }
    //validation rules ended
    $data = $request->all();
    $model = new PrSupplierContact();
    $data['status'] = Config::get('constants.success');
    if(isset($data['lead_contact']) && $data['lead_contact']==1){
      $lead_exists = PrSupplierContact::where([
                                                ['status',Config::get('constants.success')],
                                                ['fk_supplier_id',$data['fk_supplier_id']],
                                                ['lead_contact',1]
                                              ])->first();
      if(count($lead_exists)>0){
        return ['lead_exists','Lead Contact already exists'];
      }
    }
    $inserted_id = $model->create($data)->id;
    if($inserted_id>0){
      return ['success','Supplier Contact Added successfully'];
    }else{
      return ['error',Config::get('constants.error_message')];
    }
  }

  public static function updateSupplier($request){
    $rules = [
      'logo'=>'mimes:png,jpeg,jpg'
    ];
    $messsages = [
      'logo.mimes'=>'Upload only jpg,jpeg, or png images.'
    ];
    $validator = Validator::make(Input::all(), $rules, $messsages);
    if ($validator->fails()){
      $messages = $validator->messages();
      if (!empty($messages)) {
        foreach ($messages->all() as $error) {
          return ['validation',$error];
        }
      }
    }
    //validation rules ended
    $inserted_id = 0;
    $update_id = 0;
    $data = $request->all();
    $supplier = PrSupplier::where([
                                  ['status',Config::get('constants.success')],
                                  ['id',$data['fk_supplier_id']]
                                ])->first();
    $update_model = PrSupplierRating::where([
                                      ['status',Config::get('constants.success')],
                                      ['fk_supplier_id',$data['fk_supplier_id']]
                                    ])->first();

    $target_dir = public_path()."/uploads/supplier/";
    $rnd_string = Functions::generateRandomString();

    if(isset($_FILES["logo"]["name"]) && !empty($_FILES["logo"]["name"])){
      $extension = Input::file('logo')->getClientOriginalExtension();
      $name = explode(' ',$supplier->supplier_name);
      $inst_fileName = $rnd_string.'_'.$name[0].'.'.$extension;
      $inst_target_file = $target_dir .$inst_fileName;
      if($supplier->logo != null){
        $filename = public_path().'/uploads/supplier/'.$supplier->logo;
        \File::delete($filename);
      }
      if(move_uploaded_file($_FILES["logo"]["tmp_name"], $inst_target_file)){
        $data['logo'] = $inst_fileName;
        $supplier->logo = $data['logo'];
        $supplier->save();
      }
    }

    $data['ranking'] = round((
                            ($data['credit']+
                            $data['payment_terms']+
                            $data['delivery_time']+
                            $data['timeliness']+
                            $data['likeability']+
                            $data['meticulosity'])/6),1);

    if(count($update_model)>0){
      $update_model->ranking = $data['ranking'];
      $update_id = $update_model->fill($data)->save();
    }else{
      $model = new PrSupplierRating();
      $data['status'] = Config::get('constants.success');
      $inserted_id = $model->create($data)->id;
    }

    if($inserted_id>0  || $update_id>0){
      return ['success','Supplier Information Updated successfully'];
    }else{
      return ['error',Config::get('constants.error_message')];
    }
  }

  // date range filter
  public static function date_range($date_filter,$table_name=""){
    $filter_this = "";
    $filter_by = "";
    switch($date_filter){
      case "this_month":
        $filter_this = !empty($table_name) ? "MONTH(date(".$table_name.".created_at))" : "MONTH(date(created_at))";
        $filter_by = "MONTH(NOW())";
        break;
      case "this_year":
        $filter_this = !empty($table_name) ? "YEAR(date(".$table_name.".created_at))" : "YEAR(date(created_at))";
        $filter_by = "YEAR(NOW())";
        break;
      case "last_week":
        $filter_this = !empty($table_name) ? "YEARWEEK(date(".$table_name.".created_at))" : "YEARWEEK(date(created_at))";
        $filter_by = "YEARWEEK(NOW() - INTERVAL 1 WEEK)";
        break;
      case "last_month":
        $filter_this = !empty($table_name) ? "MONTH(date(".$table_name.".created_at))" : "MONTH(date(created_at))";
        $filter_by = "MONTH(NOW() - INTERVAL 1 MONTH)";
        break;
      case "last_year":
        $filter_this = !empty($table_name) ? "YEAR(date(".$table_name.".created_at))" : "YEAR(date(created_at))";
        $filter_by = "YEAR(NOW() - INTERVAL 1 YEAR)";
        break;
      default:
        $filter_this = !empty($table_name) ? "YEARWEEK(date(".$table_name.".created_at))" : "YEARWEEK(date(created_at))";
        $filter_by = "YEARWEEK(NOW())";
        break;
    }
    $return[0] = $filter_this;
    $return[1] = $filter_by;
    return json_encode($return);
  }

  //outstanding amount
  public static function getOutstandingAmount(){
    $model = new PrPurchaseOrder;
    $query = $model->newQuery();
    $query->join('pr_supplier','pr_supplier.id', '=', 'pr_purchase_order.fk_supplier_id');
    $query->join('payment_terms','payment_terms.id', '=', 'pr_supplier.fk_term_id');
    $query->where('pr_purchase_order.accounting_approval',Config::get('constants.accounting_purchase_order_approved'));
    $query->where('pr_purchase_order.status',Config::get('constants.success'));
    $query->where(DB::raw("CURDATE()"),'>',DB::raw("DATE_ADD(pr_purchase_order.accounting_approval_at, INTERVAL payment_terms.payment_days DAY)"));
    $data = $query->select(DB::raw("DATE_ADD(pr_purchase_order.accounting_approval_at, INTERVAL payment_terms.payment_days DAY) AS maturity_date"),DB::raw("SUM(pr_purchase_order.cost) as amount"),'pr_purchase_order.id')->first();
    if($data->amount>0){
      return $data->amount;
    }else{
      return 0;
    }
  }

  //past due amount
  public static function getPastDueAmount(){
    $model = new Quotes;
    $query = $model->newQuery();
    $query->join('payment_terms','payment_terms.id', '=', 'quotes.fk_term_id');
    $query->where('quotes.order_reviewed','yes');
    $query->where('quotes.order_status','won');
    $query->where('quotes.status',Config::get('constants.success'));
    $query->where(DB::raw("CURDATE()"),'>',DB::raw("DATE_ADD(quotes.order_reviewed_at, INTERVAL payment_terms.payment_days DAY)"));
    $data = $query->select(DB::raw("DATE_ADD(quotes.order_reviewed_at, INTERVAL payment_terms.payment_days DAY) AS maturity_date"),DB::raw("SUM(quotes.final_amount) as amount"),'quotes.id')->first();
    if($data->amount>0){
      return $data->amount;
    }else{
      return 0;
    }
  }

  // estimated expense of current month
  public static function getEstimatedExpense(){
    $model = new PrPurchaseOrder;
    $query = $model->newQuery();
    $query->join('pr_supplier','pr_supplier.id', '=', 'pr_purchase_order.fk_supplier_id');
    $query->join('payment_terms','payment_terms.id', '=', 'pr_supplier.fk_term_id');
    $query->where('pr_purchase_order.accounting_approval',Config::get('constants.accounting_purchase_order_approved'));
    $query->where('pr_purchase_order.status',Config::get('constants.success'));
    $query->where(DB::raw("MONTH(CURDATE())"),DB::raw("MONTH(DATE_ADD(pr_purchase_order.accounting_approval_at, INTERVAL payment_terms.payment_days DAY))"));
    $data = $query->select(
      DB::raw("DATE_ADD(pr_purchase_order.accounting_approval_at, INTERVAL payment_terms.payment_days DAY) AS maturity_date"),
      DB::raw("SUM(pr_purchase_order.cost) as amount"),'pr_purchase_order.id')->first();
    if($data->amount>0){
      return $data->amount;
    }else{
      return 0;
    }
  }
  //estimated income of current month
  public static function getEstimatedIncome(){
    $model = new Quotes;
    $query = $model->newQuery();
    $query->join('payment_terms','payment_terms.id', '=', 'quotes.fk_term_id');
    $query->where('quotes.order_reviewed','yes');
    $query->where('quotes.order_status','won');
    $query->where('quotes.status',Config::get('constants.success'));
    $query->where(DB::raw("MONTH(CURDATE())"),'>',DB::raw("MONTH(DATE_ADD(quotes.order_reviewed_at, INTERVAL payment_terms.payment_days DAY))"));
    $data = $query->select(DB::raw("DATE_ADD(quotes.order_reviewed_at, INTERVAL payment_terms.payment_days DAY) AS maturity_date"),DB::raw("SUM(quotes.final_amount) as amount"),'quotes.id')->first();
    if($data->amount>0){
      return $data->amount;
    }else{
      return 0;
    }
  }

  // exchange rate of currency
  public static function exchange_rate($from='MXN',$to='USD'){
    $response = file_get_contents('http://api.fixer.io/latest?symbols='.$to.'&base='.$from);
    return json_decode($response)->rates->USD;
  }

  // get client toggle settings
  public static function getClientToggleSettings(){
    $sidebar_toggle = "";
    $sidebar = ClientSettings::where([
                                      ['fk_user_id',Auth::user()->id],
                                      ['status',Config::get('constants.success')]
                                    ])->select('sidebar_toggle')->first();
    if(count($sidebar)>0){
      $sidebar_toggle = $sidebar->sidebar_toggle;
    }
    return $sidebar_toggle;
  }
}
?>
