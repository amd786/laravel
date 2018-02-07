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
use App\Models\User;
use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Company;
use App\Models\Contact;
use App\Models\ProductClass;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Library\Functions;
use Schema;
use Illuminate\Pagination\Paginator;
use App\Models\TempAssignParts;
use App\Models\AssignProd;
use Excel;
use Config;
use App\Models\BomImportMain;
use App\Models\BomRawMaterialList;
use App\Models\BomPakingList;
use App\Models\InvUnits;
use DateTime;
use Response;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check')->except(['ProductWithoutAppr','ModalClassTolerance','ModalWithoutApproval','index','ModalProductClass','ModalProduct','ModalAddAttributeValue','ModalAddAttribute','ModalAttributeValSel']);
        ini_set('max_execution_time', 1000);
    }

    public function ProductWithoutAppr(Request $request){
      $new_q = new AssignProd;
      $query = $new_q->newQuery();
      DB::statement(DB::raw('set @rownum=1'));
      $query->select([
                DB::raw('@rownum := @rownum + 1 AS rownum'),
                'id',
                'fk_product_id',
                'sel_attr_val_id',
                'price',
                'tolerance',
                'tolerance_sign',
                'approved_status'
              ]);
      for($i=1;$i<=10;$i++){
        if(!empty(Input::get("attr$i"))){
          $attr_id = Input::get("attr$i");
          $query->whereRaw("FIND_IN_SET($attr_id,sel_attr_val_id)");
        }
      }
      if(Input::get("product_class")>0){
        $product_class  = Input::get("product_class");
        $query->where('fk_product_class_id',$product_class);
      }
      if(Input::get("product_name")>0){
        $product_name  = Input::get("product_name");
        $query->where('fk_product_id',$product_name);
      }
      $query->where([['status',1],['approved_status',null],['approved_by',null],['approved_date',null]]);
      $query->get();

      $data = Datatables::of($query)
                      ->editColumn('check', function($query){
                        return "<input type='checkbox' class='check_prods' name='$query->id' value='$query->id'/>";
                      })
                      ->addColumn('retail_price', function ($query) {
                        $html_r = "<div class=$query->id>";
                            if($query->price !=null)
                              $html_r .= Functions::cal_retail_price($query->id);
                        $html_r .= "</div>";
                          return $html_r;
                      })
                      ->addColumn('action', function ($query) {
                          $first_class =  ($query->approved_status === 0)?'btn-danger':'btn-default';
                          $action = action('ProductController@ModalWithoutApproval',['id'=>$query->id]);
                          $html_a = "<button title='Edit' class='btn btn-default add_price_modal cursor' data-id='$query->id' data-url='$action'><i class='fa fa-pencil-square-o cursor' aria-hidden='true'></i></button>";
                          $html_a .= "<button class='btn btn-default approval_price' data-id='$query->id' data-value='1' title='Approve'><i class='fa fa-check' aria-hidden='true'></i></button>";
                          $html_a .= "<button class='btn approval_price $first_class' data-id='$query->id' data-value='0' title='Dis Approve'><i class='fa fa-times' aria-hidden='true'></i></button>";
                          return $html_a;
                      })
                      ->editColumn('price', function($query){
                        $url = url('/');
                        if(empty($query->price))
                          return "<a href='#' class='editable' id='price' data-type='text' data-pk='$query->id' data-url='$url/edit-price'></a>";
                        else
                          return "$ <a href='#' class='editable' id='price' data-type='text' data-pk='$query->id' data-url='$url/edit-price'>$query->price</a>";
                      })
                      ->editColumn('tolerance', function($query){
                        $url = url('/');
                        $first_class = ($query->tolerance_sign === 1)?'green':'';
                        $second_class = ($query->tolerance_sign === 0)?'red':'';

                        if(empty($query->tolerance))
                          return "<a href='#' class='editable' id='tolerance' data-type='text' data-pk='$query->id' data-url='$url/edit-price'></a>";
                        else
                          $html_t = "<div class='col-md-1'>";
                            $html_t .= "<i class='fa fa-plus change_tol_sign cursor $first_class ' data-value='1' data-id='$query->id' aria-hidden='true'></i>";
                            $html_t .= "<i class='fa fa-minus change_tol_sign cursor $second_class' data-value='0' data-id='$query->id'  aria-hidden='true'></i>";
                          $html_t .="</div>";
                          $html_t .="<div class='col-md-5'>";
                            $html_t .="<a href='#' class='editable' id='tolerance' data-type='text' data-pk='$query->id 'data-url='$url/edit-price' > $query->tolerance </a>%";
                          $html_t .="</div>";
                          return $html_t;
                      })
                      ->editColumn('sel_attr_val_id', function($query){
                        return utf8_decode(strtoupper(Functions::get_variants($query->id)));
                      })
                      ->editColumn('fk_product_id', function($query){
                        return $query->product->product_name;
                      });

      if ($keyword = $request->get('search')['value']) {
            $data->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
      }

      return $data->make(true);
    }

    public function ProductsWithoutApproval(){
      $attributes = Attribute::where([['status',1],['fk_product_class_id',16]])->get();
      $prod_class  = new ProductClass;
      $product  = new product;
      return view('product.product_without_approval',['attributes'=>$attributes,'prod_class'=>$prod_class,'product'=>$product]);
    }

    public function index(){
        return view('product.index');
    }

    public function ProductClass(){

      $product_class = new ProductClass();
      $all_pc = ProductClass::where('status',1)->Paginate(10);
      return view('product.product_class',['product_class'=>$product_class,'all_pc'=>$all_pc]);
    }

    public function ModalProductClass($id = null){
      if($id != null)
        $product_class = ProductClass::where([['id','=',$id],['status','=',1]])->first();
      else
        $product_class = new ProductClass();

      return view('product.product_class_form',['product_class'=>$product_class])->render();
    }

    public function SaveProductClass(Request $request){

      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'product_class'=>'required'
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            if (!empty($messages)) {
                foreach ($messages->all() as $error) {
                   Session::flash('error', $error);
                   return redirect(action('ProductController@ProductClass'))->withInput();
                }
            }
        }
        //validation rules ended

        $data = $request->all();
        $model = new ProductClass();
        $data['status'] = 1;
        $model->create($data);
        Session::flash('success','Product Class is added successfully.');
        return redirect(action('ProductController@ProductClass'));
      }
    }

    public function SaveEditProductClass($id,Request $request){

      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'product_class'=>'required'
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            if (!empty($messages)) {
                foreach ($messages->all() as $error) {
                   Session::flash('error', $error);
                   return redirect(action('ProductController@ProductClass'))->withInput();
                }
            }
        }
        //validation rules ended

        $data = $request->all();
        $model = ProductClass::where([['id','=',$id],['status','=',1]])->first();
        $data['status'] = 1;
        $model->fill($data)->save();
        Session::flash('success','Product Class is edited successfully.');
        return redirect(action('ProductController@ProductClass'));
      }
    }

    public function DeleteProductClass($id){

      $model = ProductClass::where([['id','=',$id],['status','=',1]])->first();
      $model->Products()->delete();
      $model->AssignProdClass()->delete();
      if($model->delete()){
        Session::flash('success','Product Class deleted successfully.');
      }else{
        Session::flash('error','Something went wrong.');
      }
      return redirect()->back();
    }

    public function Product(){

      $product_class = new Product();

      $all_products = Product::where('status',1)->Paginate(10);
      return view('product.product',['product'=>$product_class,'all_products'=>$all_products]);
    }

    public function ModalProduct($id = null){
      // no need to check permission
      if($id != null)
        $product = Product::where([['id','=',$id],['status','=',1]])->first();
      else
        $product = new Product();

      return view('product.product_form',['product'=>$product])->render();
    }

    public function SaveProduct(Request $request){

      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'fk_product_class'=>'required',
          'product_name'=>'required'
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            if (!empty($messages)) {
                foreach ($messages->all() as $error) {
                   Session::flash('error', $error);
                   return redirect(action('ProductController@Product'))->withInput();
                }
            }
        }
        //validation rules ended

        $data = $request->all();
        $model = new Product();
        $data['status'] = 1;
        $model->create($data);
        Session::flash('success','Product is added successfully.');
        return redirect(action('ProductController@Product'));
      }
    }

    public function SaveEditProduct($id,Request $request){

      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'fk_product_class'=>'required',
          'product_name'=>'required'
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            if (!empty($messages)) {
                foreach ($messages->all() as $error) {
                   Session::flash('error', $error);
                   return redirect(action('ProductController@Product'))->withInput();
                }
            }
        }
        //validation rules ended

        $data = $request->all();
        $model = Product::where([['id','=',$id],['status','=',1]])->first();
        $data['status'] = 1;
        $model->fill($data)->save();
        Session::flash('success','Product is edited successfully.');
        return redirect(action('ProductController@Product'));
      }
    }

    public function DeleteProduct($id){

      $model = Product::where([['id','=',$id],['status','=',1]])->first();
      $model->AssignProd()->delete();
      if($model->delete()){
        Session::flash('success','Product deleted successfully.');
      }else{
        Session::flash('error','Something went wrong.');
      }
      return redirect()->back();
    }

    public function Attributes(){
      $p_classes = ProductClass::where('status',1)->get();
      //$attributes = Attribute::where('status',1)->orderBy('sort_order')->Paginate(11);
      return view('product.attributes',['p_classes'=>$p_classes]);
    }

    public function SaveNewAttribute(){
      // validation rules
      $rules = [
        'attribute_name'=>'required'
      ];
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 Session::flash('error', $error);
                 return redirect()->back()->withInput();
              }
          }
      }
      //validation rules ended

      $data = Input::all();
      $attribute = new Attribute();
      $data['status'] = 1;
      $attr_id = $attribute->create($data)->id;
      foreach ($data['attr_values_split'] as $value) {
        $attr_value = new AttributeValue;
        $attr_value->fk_attribute_id = $attr_id;
        $attr_value->attribute_value = $value[0];
        $attr_value->attribute_code = $value[1];
        $attr_value->status = 1;
        $attr_value->save();
      }

      Session::flash('success','Attribute added successfully.');
      return redirect()->back();
    }

    public function ModalAddAttribute($class_id){
      $attr = new Attribute;
      return view('product.add_attribute_form',['attr'=>$attr,'class_id'=>$class_id])->render();
    }

    public function CopyAttribute($attr_id){
      $attribute = Attribute::where([['id','=',$attr_id],['status','=',1]])->first();
      return view('product.copy_attr_form',['attribute'=>$attribute])->render();
    }

    public function CopyAttributeSave($attr_id){
      // validation rules
      $rules = [
        'product_class'=>'required'
      ];
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 Session::flash('error', $error);
                 return redirect()->back()->withInput();
              }
          }
      }
      $copy_class = Input::get('product_class');
      $attr_info = Attribute::where('id',$attr_id)->first();
      // if copied to same class then prevent it
      if($attr_info->fk_product_class_id == $copy_class){
        Session::flash('error','Attribute can\'t be copied to same class.');
        return redirect()->back();
      }
      $new_attr = new Attribute;
      $new_attr->fk_product_class_id = $copy_class;
      $new_attr->attribute_name = $attr_info->attribute_name;
      $new_attr->separator = $attr_info->separator;
      $new_attr->status = $attr_info->status;
      $new_attr->save();
      // copy attr values
      $attr_vals =  $attr_info->AttributeValues()->get();
      foreach($attr_vals as $attr_val){
        $new_attr_val = new AttributeValue;
        $new_attr_val->fk_attribute_id = $new_attr->id;
        $new_attr_val->attribute_value = $attr_val->attribute_value;
        $new_attr_val->attribute_code = $attr_val->attribute_code;
        $new_attr_val->notes = $attr_val->notes;
        $new_attr_val->status = $attr_val->status;
        $new_attr_val->save();
      }
      Session::flash('success','Attribute is copied to class.');
      return redirect()->back();
    }

    public function EditAttribute($id){
      $attribute = Attribute::where([['id','=',$id],['status','=',1]])->first();
      $edit_attributes_values = AttributeValue::where([['status',1],['fk_attribute_id',$id]])->Paginate(10);
      return view('product.edit_attribute',['attribute'=>$attribute,'edit_attributes_values'=>$edit_attributes_values]);
    }

    public function DeleteAttribute($id){
      $edit_attributes = Attribute::where([['status',1],['id',$id]])->first();
      $edit_attributes->AttributeValues()->delete();
      if($edit_attributes->delete()){
          Session::flash('success','Attribute deleted successfully.');
          return redirect()->back();
      }
    }

    public function ModalAddAttributeValue($attr_id,$attr_value_id = null){
      // no need to check permission
      if($attr_value_id != null)
        $attr_value = AttributeValue::where([['fk_attribute_id','=',$attr_id],['id','=',$attr_value_id],['status','=',1]])->first();
      else
        $attr_value = new AttributeValue;

      $attr = Attribute::where([['status',1],['id',$attr_id]])->first();
      return view('product.attr_value_form',['attr_value'=>$attr_value,'attr'=>$attr])->render();
    }

    public function SaveAttrValue(){
      // validation rules
      $rules = [
        'attribute_value'=>'required',
        'attribute_code'=>'required'
      ];
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 Session::flash('error', $error);
                 return redirect()->back()->withInput();
              }
          }
      }
      //validation rules ended

      $data = Input::all();
      $model = new AttributeValue();
      $data['status'] = 1;
      $model->create($data);
      $attr = Attribute::where([['status',1],['id',$data['fk_attribute_id']]])->first();
      $attr->attribute_name = $data['attribute_name'];
      if($attr->save()){
        Session::flash('success','Attribute Value is added successfully.');
        return redirect()->back();
      }
    }

    public function SaveEditAttrValue($attr_value_id){
      // validation rules
      $rules = [
        'attribute_value'=>'required',
        'attribute_code'=>'required'
      ];
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 Session::flash('error', $error);
                 return redirect()->back()->withInput();
              }
          }
      }
      //validation rules ended

      $data = Input::all();
      $model = AttributeValue::where([['id','=',$attr_value_id],['status','=',1]])->first();
      $data['status'] = 1;
      $model->fill($data)->save();
      // save attribute value
      $attr = Attribute::where([['status',1],['id',$data['fk_attribute_id']]])->first();
      $attr->attribute_name = $data['attribute_name'];
      if($attr->save()){
        Session::flash('success','Attribute Value is edited successfully.');
        return redirect()->back();
      }
    }

    public function DeleteAttrValue($id){
      $model = AttributeValue::where([['status',1],['id',$id]])->delete();
      if($model){
        Session::flash('success','Attribute Value deleted successfully.');
        return redirect()->back();
      }
    }

    public function AssignPartNo(){
      $assign_parts = array();
      $assign_parts = TempAssignParts::where([['status',1],['fk_user_id',Auth::User()->id]])->first();
      return view('product.assign_part_no',['assign_parts'=>$assign_parts]);
    }

    public function AssignPart($id=null){
      $assign_parts = array();
      if($id== null){
        $model = new TempAssignParts;
      }else{
        $model = TempAssignParts::where('fk_product_class_id',$id)->first();
        if($model == null){
          $model = new TempAssignParts;
        }
      }
      $attributes = Attribute::where([['status',1],['fk_product_class_id',$id]])->get();
      $assign_parts = TempAssignParts::where([['status',1],['fk_user_id',Auth::User()->id]])->first();
      $selected = array();
      $single = array();
      // get selected checkboxes
      if(count($assign_parts)>0){
        $all_attributes = $assign_parts->attributes;
        $attribute_values = explode(',', $all_attributes);
        // get all attributes
        foreach ($attribute_values as $attribute_value) {
          $fk_attribute_id = AttributeValue::where([['status',1],['id',$attribute_value]])->select('fk_attribute_id')->first();
          if(count($fk_attribute_id)>0){
            $total_attributes = AttributeValue::where([['status',1],['fk_attribute_id',$fk_attribute_id->fk_attribute_id]])->select('id')->get();// get total attributes
            $temp = array();
            if(count($total_attributes)>0){
              foreach ($total_attributes as $total_attribute) {
                if (in_array($total_attribute->id, $attribute_values)){ // iftotal  attribute exists in attributes
                  $temp[] = $total_attribute->id;
                }
              }
              if(count($temp)==count($total_attributes)){ // all checked
                $attribute_values = array_diff($attribute_values, $temp);
                $attribute_values = array_values($attribute_values);
                $selected[] = $fk_attribute_id->fk_attribute_id;
              }else{
                if(!in_array($fk_attribute_id->fk_attribute_id,$selected)){ // single checked
                  $single[] = $fk_attribute_id->fk_attribute_id;
                }
              }
            }
          }
        }
      }
      return view('product.assign_part',['model'=>$model,'attributes'=>$attributes,'assign_parts'=>$assign_parts,'selected'=>$selected,'single'=>$single]);
    }

    public function ModalAttributeValSel($attr_id){
      $attr = Attribute::where([['status',1],['id',$attr_id]])->first();
      $attr_val = AttributeValue::where([['status',1],['fk_attribute_id',$attr_id]])->get();
      $tmp_assign_model = TempAssignParts::where([['status',1],['fk_user_id',Auth::User()->id]])->first();
      if($tmp_assign_model == null){
        $tmp_assign_model = new TempAssignParts;
      }
      return view('product.modal_attr_val_sel',['attr'=>$attr,'attr_val'=>$attr_val,'tmp_assign_model'=>$tmp_assign_model])->render();
    }

    public function PriceManagement(){
      return view('product.price_management');
    }

    public function SetProductPrice(){
      return view('product.set_product_price');
    }

    public function ModalWithoutApproval($id=null){
      if($id == null){
        $assign_prod= new AssignProd;
      }else{
        $assign_prod = AssignProd::where([['status',1],['id',$id]])->first();
      }
      return view('product._modal_without_approval',['assign_prod'=>$assign_prod])->render();
    }

    public function SavePrice($id=null){
      $data = Input::all();
      $assign_prod = AssignProd::where([['status',1],['id',$id]])->first();
      $assign_prod->price = $data['price'];
      $assign_prod->tolerance = $data['tolerance'];
      if(isset($data['tolerance_sign']))
        $assign_prod->tolerance_sign = $data['tolerance_sign'];
      $assign_prod->save();
      Session::flash('success','Data is Successfully saved.');
      return redirect()->back();
    }

    public function ProductsWithApproval(){
      $prod_class  = new ProductClass;
      $product  = new product;
      return view('product.product_with_approval',['prod_class'=>$prod_class,'product'=>$product]);
    }

    public function ProductsWithApprovalTable(AssignProd $assign_prod){
      $product_class = Functions::test_input(Input::get('class'));
      $product_name = Functions::test_input(Input::get('product'));
      $assign_prod = $assign_prod->newQuery();
      $assign_prod->where('status',Config::get('constants.success'));
      $assign_prod->where('approved_status',1);
      if($product_class > 0){
        $assign_prod->where('fk_product_class_id',$product_class);
      }
      if($product_name > 0){
        $assign_prod->where('fk_product_id',$product_name);
      }
      $assign_prod->orderBy('id','desc');
      $assign_prods = $assign_prod->Paginate(10);
      return view('product.product_with_approval_table',['assign_prods'=>$assign_prods]);
    }

    public function PriceViewAll(){
      $prod_class  = new ProductClass;
      $product  = new product;
      return view('product.price_view_all',['prod_class'=>$prod_class,'product'=>$product]);
    }

    public function PriceViewAllTable(AssignProd $assign_prod){
      $product_class = Functions::test_input(Input::get('class'));
      $product_name = Functions::test_input(Input::get('product'));
      $assign_prod = $assign_prod->newQuery();
      $assign_prod->where('status',Config::get('constants.success'));
      $assign_prod->where('approved_by','<>',null);
      if($product_class > 0){
        $assign_prod->where('fk_product_class_id',$product_class);
      }
      if($product_name > 0){
        $assign_prod->where('fk_product_id',$product_name);
      }
      $assign_prod->orderBy('id','desc');
      $assign_prods = $assign_prod->Paginate(10);
      return view('product.price_view_all_table',['assign_prods'=>$assign_prods]);
    }

    public function ProductClassTolerance(){
      $prod_class = ProductClass::where('status',1)->Paginate(10);
      return view('product.product_class_tolerance',['prod_class'=>$prod_class]);
    }

    public function ModalClassTolerance($id){
      $prod_class = ProductClass::where([['status',1],['id',$id]])->first();
      return view('product._modal_class_tolerance',['prod_class'=>$prod_class])->render();
    }

    public function SaveClassTolerance($id){
      // validation rules
      $rules = [
        'tolerance'=>'required',
        'tolerance_sign'=>'required'
      ];
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 Session::flash('error', $error);
                 return redirect()->back()->withInput();
              }
          }
      }
      //validation rules ended
      $data = Input::all();
      $prod_class = ProductClass::where([['status',1],['id',$id]])->first();
      $prod_class->tolerance = $data['tolerance'];
      $prod_class->short_description = $data['short_description'];
      $prod_class->tolerance_sign = $data['tolerance_sign'];
      $prod_class->save();
      Session::flash('success','Data is Successfully saved.');
      return redirect()->back();
    }

    public function ProductViewAll(){
      $prod_class  = new ProductClass;
      $product  = new product;
      return view('product.product_view_all',['prod_class'=>$prod_class,'product'=>$product]);
    }

    public function ProductViewAllTable(AssignProd $assign_prod){
      $product_class = Functions::test_input(Input::get('class'));
      $product_name = Functions::test_input(Input::get('product'));
      $assign_prod = $assign_prod->newQuery();
      $assign_prod->where('status',Config::get('constants.success'));
      $assign_prod->where('approved_status',1);
      if($product_class > 0){
        $assign_prod->where('fk_product_class_id',$product_class);
      }
      if($product_name > 0){
        $assign_prod->where('fk_product_id',$product_name);
      }
      $assign_prod->orderBy('id','desc');
      $assign_prods = $assign_prod->Paginate(10);
      return view('product.product_view_all_table',['assign_prods'=>$assign_prods]);
    }

    public function ViewAll(){
      $assign_prods = AssignProd::where('status',1)->orderBy('id','desc')->Paginate(10);
      $product = new Product();
      return view('product.view_all',['assign_prods'=>$assign_prods,'product'=>$product]);
    }

    public function ViewAllDetails($id){
      $raw_materials = array();
      $assign_prod = AssignProd::where([['status',Config::get('constants.success')],['id',$id]])->first();
      $main_bom = BomImportMain::where([['status',Config::get('constants.success')],['fk_sku',$assign_prod->code]])->first();
      $bom_raw_material_list = new BomRawMaterialList;
      if(count($main_bom)>0){
        $raw_materials = BomRawMaterialList::where([
                                                    ['status',Config::get('constants.success')],
                                                    ['fk_bom_import_id',$main_bom->id]
                                                  ])->get();
      }
      $inv_units = new InvUnits;
      return view('product.view_all_details',['assign_prod'=>$assign_prod,
                                              'raw_materials'=>$raw_materials,
                                              'inv_units'=>$inv_units,
                                              'bom_raw_material_list'=>$bom_raw_material_list
                                            ]);
    }

    public function importExcel()
  	{
      $all_attr = Input::get('attr');
      $query = new AssignProd;
      $query = $query->newQuery();

      foreach(Input::get('attr') as $key=>$val){
        if(!empty($val)){
          $query->whereRaw("FIND_IN_SET($val,sel_attr_val_id)");
        }
      }

      $query->where([['status',1],['approved_status',null],['approved_by',null],['approved_date',null]]);
      $assign_prods = $query->get();

  		if(Input::hasFile('import_file')){
  			$path = Input::file('import_file')->getRealPath();

        $data = Excel::load($path, function($reader) {
  			})->get();

        if($assign_prods != null){
          foreach($assign_prods as $key=>$assign_prod){
            if(!isset($data[$key])){
              break;
            }
            $assign_prod->price = $data[$key]->price;
            $assign_prod->save();
          }
        }
  		}else{
        Session::flash('error','Please select file.');
      }
  		return back();
  	}
    public function ProductFilter(){
      $prod_class = Functions::test_input(Input::get('product_class'));
      if($prod_class==0){
        $prod_class = 16;
      }
      $attributes = Attribute::where([['status',1],['fk_product_class_id',$prod_class]])->get();
      return view('product.product_filter',['attributes'=>$attributes]);

    }

    public function BomImport(){
      $model = new Product();
      return view('product.bom_import',['model'=>$model]);
    }

    public function SaveBomImport(){
      $response = [];
      // validation rules
      $rules = [
        'product_family'=>'required',
        'bom_file'=>'required|mimes:xls,xlsx'
      ];
      $messages = ['bom_file.required'=>'The BoM file field is required',
                    'bom_file.mimes'=>'The BoM file must be a file of type: xls, xlsx',
                  ];
      $validator = Validator::make(Input::all(), $rules, $messages);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 $response['status'] = 'error';
                 $response['message'] = $error;
                 echo json_encode($response);
                 die;
              }
          }
      }
      //validation rules ended
      // set variables
      $rm_r_start_count = 10; // raw materials start row count number
      $rm_c_start_count = 1; // raw materials start column count number
      $rm_r_count = 26; // raw materials end row count
      $rm_c_count = 9; // raw materials total column count
      $pk_r_start_count = 29; // paking row start count
      $pk_r_count = 39; // paking end row count

      // dynamic variables


      $product_family_id = Input::get('product_family');
      $update_bom = Input::get('update_bom');

      if(Input::hasFile('bom_file')){
        $upload_results = array();
        $count = array();
        $imported_count = 0;
        $sheetCount = 0;
  			$path = Input::file('bom_file')->getRealPath();
        //$data = Excel::getActiveSheet()->getCell('B8')->getValue();
        $data = Excel::load($path, function($reader) use (&$sheetCount,&$imported_count,&$upload_results,$product_family_id , $update_bom , $pk_r_count , $pk_r_start_count , $rm_r_count , $rm_c_count , $rm_r_start_count , $rm_c_start_count) {
          $sheetCount = $reader->getSheetCount();
          for($i = 1; $i <= $sheetCount; $i++){
            // set the active sheet by index
            $reader->setActiveSheetIndex($i-1);

            // dynamic counters
            $fk_sku = $reader->getActiveSheet()->getCellByColumnAndRow(3, 6)->getValue();
            $start = $rm_r_start_count;
            $raw_material_value = $reader->getActiveSheet()->getCellByColumnAndRow(1, $start)->getValue();
            while($raw_material_value!='PAKING'){
              $start++;
              $raw_material_value = $reader->getActiveSheet()->getCellByColumnAndRow(1, $start)->getValue();
            }
            $rm_r_count = $start-2;
            $pk_r_start_count = $start+1;
            $start  = $pk_r_start_count;
            $packing_value = $reader->getActiveSheet()->getCellByColumnAndRow(1, $start)->getValue();
            while($packing_value!=''){
              $start++;
              $packing_value = $reader->getActiveSheet()->getCellByColumnAndRow(1, $start)->getValue();
            }
            $pk_r_count = $start-1;

            // checkbox condition
            if($update_bom == 1){
              $bom_import_main = BomImportMain::where([['fk_sku',$fk_sku],['fk_product_family_id',$product_family_id],['status',1]])->first();
              if($bom_import_main != null){
                $upload_results[$i][] = "skipped";
                $upload_results[$i][] = $fk_sku;
                continue;
              }
            }else{
              $imported_count++;
              $upload_results[$i][] = 'imported';
              $upload_results[$i][] = $fk_sku;
            }


            // get the values for bom main table
            $bom_main = new BomImportMain();
            $bom_insert_id = $bom_main->saveBomImportValues($reader , $product_family_id);

            //delete all previous records
            $bom_main = BomImportMain::where([['fk_sku',$fk_sku],['fk_product_family_id',$product_family_id],['status',1]])->first();
            if($bom_main != null){
              $delete = BomRawMaterialList::where([['fk_bom_import_id',$bom_main->id],['is_import',1],['status',1]])->forceDelete();
            }

            // get the values of raw metrials list
            for($r = $rm_r_start_count; $r <= $rm_r_count; $r++){
              $raw_materials = [];
              for($c = $rm_c_start_count; $c <= $rm_c_count; $c++){
                $raw_materials[] = $reader->getActiveSheet()->getCellByColumnAndRow($c, $r)->getValue();
              }
              // save raw materials values to db
              $bom_raw = new BomRawMaterialList;
              $bom_raw->saveRawMaterialsValues($raw_materials , $bom_insert_id);
            }

            //delete all previous records
            $bom_main = BomImportMain::where([['fk_sku',$fk_sku],['fk_product_family_id',$product_family_id],['status',1]])->first();
            if($bom_main != null){
              $delete = BomPakingList::where([['fk_bom_import_id',$bom_main->id],['is_import',1],['status',1]])->forceDelete();
            }

            // get the values of paking list
            for($r_p = $pk_r_start_count; $r_p <= $pk_r_count; $r_p++){
              $paking_list = [];
              for($c_p = $rm_c_start_count; $c_p <= $rm_c_count; $c_p++){
                $paking_list[] = $reader->getActiveSheet()->getCellByColumnAndRow($c_p, $r_p)->getValue();
              }
              // save paking list values to db
              $bom_paking = new BomPakingList;
              $bom_paking->savePakingValues($paking_list , $bom_insert_id);
            }
          }
  			})->get();
        $count = array('total'=>$sheetCount,'imported'=>$imported_count);
        $returnHTML = view('product.bom_results',['upload_results'=>$upload_results,'count'=>$count])->render();
        $response = array('status' => 'success','message'=>'Successfully imported','html'=>$returnHTML);
        return Response::json($response);
      }
    }
    public function PreviewBom(){
      $response = [];
      // validation rules
      $rules = [
        'product_family'=>'required',
        'bom_file'=>'required|mimes:xls,xlsx'
      ];
      $messages = ['bom_file.required'=>'The BoM file field is required',
                    'bom_file.mimes'=>'The BoM file must be a file of type: xls, xlsx',
                  ];
      $validator = Validator::make(Input::all(), $rules, $messages);
      if ($validator->fails())
      {
          $messages = $validator->messages();
          if (!empty($messages)) {
              foreach ($messages->all() as $error) {
                 $response['status'] = 'error';
                 $response['message'] = $error;
                 echo json_encode($response);
                 die;
              }
          }
      }
      //validation rules ended
      $records = array();
      $sheetCount = 0;
      $response = array();
      // set variables
      $rm_r_start_count = 10; // raw materials start row count number
      $rm_c_start_count = 1; // raw materials start column count number
      $rm_r_count = 26; // raw materials end row count
      $rm_c_count = 9; // raw materials total column count
      $pk_r_start_count = 29; // paking row start count
      $pk_r_count = 39; // paking end row count

      $product_family_id = Input::get('product_family');
      $update_bom = Input::get('update_bom');

      if(Input::hasFile('bom_file')){
        $file_skus = array();
  			$path = Input::file('bom_file')->getRealPath();
        $data = Excel::load($path, function($reader) use (&$sheetCount,&$records,$product_family_id , $update_bom , $pk_r_count , $pk_r_start_count , $rm_r_count , $rm_c_count , $rm_r_start_count , $rm_c_start_count) {
          $sheetCount = $reader->getSheetCount();
          for($i = 1; $i <= $sheetCount; $i++){
            // set the active sheet by index
            $test = 1;
            $reader->setActiveSheetIndex($i-1);
            $fk_sku = $reader->getActiveSheet()->getCellByColumnAndRow(3, 6)->getValue();
            $product_name = $reader->getActiveSheet()->getCellByColumnAndRow(3, 5)->getValue();
            $records[$i]['sku'] = $fk_sku;
            $records[$i]['name'] = $product_name;
          }
  			})->get();
      }
      if($sheetCount==count($records)){
        $returnHTML = view('product.preview_bom',['records'=>$records])->render();
        $response = array('status' => 'success', 'html'=>$returnHTML);
      }else{
        $message = "Please upload valid excel file";
        $response = array('status' => 'failure','html'=>$message);
      }
      return Response::json($response);
    }

    public function SaveRawMaterialList(Request $request){
      $post_data = $request->all();
      if(!empty($post_data['sku'])){
        $bom_main = BomImportMain::where([
                                            ['status',Config::get('constants.success')],
                                            ['fk_sku',$post_data['sku']]
                                          ])->first();
        if(count($bom_main)>0){
          $fk_bom_import_id = $bom_main->id;
        }else{
          $product_detail = AssignProd::select('fk_product_id')->where([
                                                                  ['status',Config::get('constants.success')],
                                                                  ['code',$post_data['sku']]
                                                                ])->first();
          if(count($product_detail)>0){
            $bom_import_main = new BomImportMain;
            $data = array();
            $data['fk_sku'] = $post_data['sku'];
            $data['fk_product_family_id'] = $product_detail->fk_product_id;
            $data['status'] = Config::get('constants.success');
            $fk_bom_import_id = $bom_import_main->create($data)->id;
          }
        }
      }
      $records = json_decode($post_data['data'][0]);
      $bom_raw_material_list = new BomRawMaterialList;
      if(count($records)>0){
        foreach ($records as $record) {
          if($record->action=="add"){
            $data = array();
            $data['fk_bom_import_id'] = $fk_bom_import_id;
            $data['part_name'] = $record->part_name;
            $data['part_number'] = $record->part_number;
            $data['material'] = $record->material;
            $data['qty'] = $record->quantity;
            $data['fk_unit_id'] = $record->unit;
            $data['is_import'] = 0;
            $data['status'] = Config::get('constants.success');
            $bom_raw_material_list->create($data);
          }elseif($record->action=="update"){
            $update_record = BomRawMaterialList::where([
                                                        ['id',$record->id],
                                                        ['fk_bom_import_id',$fk_bom_import_id],
                                                        ['status',Config::get('constants.success')]
                                                      ])->first();
            if(count($update_record)>0){
              $update_record->part_name = $record->part_name;
              $update_record->part_number = $record->part_number;
              $update_record->material = $record->material;
              $update_record->qty = $record->quantity;
              $update_record->fk_unit_id = $record->unit;
              $update_record->save();
            }
          }elseif($record->action=="delete"){
            BomRawMaterialList::where([
                                        ['id',$record->id],
                                        ['fk_bom_import_id',$fk_bom_import_id],
                                        ['status',Config::get('constants.success')]
                                      ])->delete();
            $count_records = BomRawMaterialList::where([
                                        ['fk_bom_import_id',$fk_bom_import_id],
                                        ['status',Config::get('constants.success')]
                                      ])->get();
            if(count($count_records)==0){
              BomImportMain::where([
                                    ['id',$fk_bom_import_id],
                                    ['status',Config::get('constants.success')]
                                  ])->delete();
            }
          }
        }
        return redirect()->back()->withSuccess('Raw Material List Updated Successfully');
      }else{
        return redirect()->back();
      }
    }

    // clear attributes
    public function ClearAssignParts(){
      $assign_parts = TempAssignParts::where([['fk_user_id',Auth::User()->id]])->first();
      if(count($assign_parts)>0){
        $assign_parts->delete();
      }
      return redirect(action('ProductController@AssignPart'));
    }
}
?>
