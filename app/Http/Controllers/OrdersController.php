<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Library\Functions;
use Illuminate\Support\Facades\Redirect;
use Session;
use Validator;
use Input;
use Response;
use Config;
use Auth;
use DB;
use App\Models\ClientImportOrders;
use App\Models\ClientImportOrderDetails;
use App\Models\User;

class OrdersController extends Controller
{

  public function __construct(){
      $this->middleware('auth');
      $this->middleware('check')->except(['index']);
  }

  public function index(){
    return view('orders.index');
  }

  public function ClientList(ClientImportOrders $clients){
    $user_ids = array();
    $search = Functions::test_input(Input::get('search_text'));
    $clients = $clients->newQuery();
    if(!empty($search)){
      $user_ids  = User::where([['status','=',Config::get('constants.success')],['first_name','like','%'.$search.'%']])->orderBy('id')->pluck('id');
    }
    $clients->where(function ($query) {
        $query->where('status',Config::get('constants.success'));
      })->where(function($query) use ($user_ids){
            foreach($user_ids as $user_id) {
              $query->orWhere('fk_user_id', '=', $user_id);
            }
          });
    $clients = $clients->groupBy('fk_user_id')->Paginate(10);
    return view('orders.client_list',
                  ['clients'=>$clients]
                )->render();
  }

  public function OrderManagement($fk_user_id){
    return view('orders.order_management',['fk_user_id'=>$fk_user_id]);
  }

  public function OrdersTable(ClientImportOrders $orders){
    $start_date = "";
    $end_date = "";
    $order_status = Functions::test_input(Input::get('order_status'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));
    $date_filter = Functions::test_input(Input::get('quick_date'));
    $search = Functions::test_input(Input::get('search_text'));
    $fk_user_id = Functions::test_input(Input::get('fk_user_id'));
    $query = $orders->newQuery();
    $query->where([
                    ['status',Config::get('constants.success')],
                    ['fk_user_id',$fk_user_id]
                  ]);
    if(!empty($search)){
      $query->where('order_no', 'like', '%'.strtoupper($search).'%');
    }
    if(!empty($date_filter)){
      $return = Functions::date_range($date_filter);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    if($order_status>0){
      if($order_status==1){
        $query->where('completion_percentage',100);
      }else{
        $query->where('completion_percentage','<>',100);
      }
    }
    $orders = $query->orderBy('pin_to_top','desc')->orderBy('order_no','desc')->Paginate(10);
    return view('orders.orders_table',
                  ['orders'=>$orders]
                )->render();
  }

  public function PinToTop($order_no){
    if($order_no>0){
      $order = ClientImportOrders::where([
                            ['status',Config::get('constants.success')],
                            ['order_no',$order_no]
                          ])->first();
      if($order->pin_to_top==1){
        $order->pin_to_top = 0;
      }else{
        $order->pin_to_top = 1;
      }
      if($order->save()){
        return redirect(action('OrdersController@OrderManagement',['id'=>$order->fk_user_id]));
      }else{
        return redirect(action('OrdersController@OrderManagement',['id'=>$order->fk_user_id]))->withError(Config::get('constants.error_message'));
      }
    }
  }

  public function OrderDetail($order_no){
    $order = ClientImportOrders::where([
                                        ['status',Config::get('constants.success')],
                                        ['order_no',$order_no]
                                      ])->first();
    return view('orders.order_detail',['order_no'=>$order_no,'order'=>$order]);
  }

  public function OrderDetailsTable(ClientImportOrderDetails $order_details){
    $search = "";
    $order_no = Functions::test_input(Input::get('order_no'));
    $priority = Functions::test_input(Input::get('priority'));
    $status = Functions::test_input(Input::get('status'));
    $search = Functions::test_input(Input::get('search'));
    if($order_no>0){
      $order_details = $order_details->newQuery();
      $order_details->where(function($query) use ($order_no,$priority,$status) {
          $query->where('fk_order_no',(int)$order_no);
          if($priority>0){
            $query->where('priority',$priority);
          }
          if($status>0){
            $query->where('status',$status);
          }
        })->where(function($query) use ($search){
          if(!empty($search)){
            $query->orWhere('product_code', 'like', '%'.strtoupper($search).'%')
            ->orWhere('purchase_code', 'like', '%'.$search.'%');
          }
        });
      $order_details = $order_details->get();
      return view('orders.order_details_table',['order_details'=>$order_details,'order_no'=>$order_no]);
    }
  }

  public function SaveOrderDetailsData(){
    $notes = Functions::test_input(Input::get('notes'));
    $id = Functions::test_input(Input::get('id'));
    $status = Functions::test_input(Input::get('status'));
    $item = Functions::test_input(Input::get('item'));
    $expected_delivery_date = Functions::test_input(Input::get('expected_delivery_date'));
    $priority = Functions::test_input(Input::get('priority'));
    if($id>0){
      $order_detail = ClientImportOrderDetails::where([
                            ['id',$id]
                          ])->first();

      if(!empty($notes)){
        $order_detail->status = Config::get('constants.client_order_onhold');
      }
      if($status == Config::get('constants.client_order_completed')){
        $order_detail->notes = NULL;
      }
      $order_detail->status = $status;
      $order_detail->notes = $notes;
      $order_detail->priority = $priority;
      if(!empty($expected_delivery_date)){
        $order_detail->expected_delivery_date = $expected_delivery_date;
      }else{
        $order_detail->expected_delivery_date = NULL;
      }
      $order_detail->save();
      $completed_orders = ClientImportOrderDetails::where([
                                              ['fk_order_no',$order_detail->fk_order_no],
                                              ['status',Config::get('constants.client_order_completed')]
                                            ])->get()->count();
      $total_orders = ClientImportOrderDetails::where([
                                              ['fk_order_no',$order_detail->fk_order_no]
                                            ])->get()->count();
      $completion_percentage = ($completed_orders/$total_orders)*100;
      $order = ClientImportOrders::where([
                            ['status',Config::get('constants.success')],
                            ['order_no',$order_detail->fk_order_no]
                          ])->first();
      $order->completion_percentage = $completion_percentage;
      $order->save();
      $data['response']='success';
      $data['item']=$item;
      $data['status'] = $status;
      $data['notes'] = $notes;
      return json_encode($data);
    }
  }

  public function SaveOrderData(){
    $order_no = Functions::test_input(Input::get('order_no'));
    $estimated_completion_date = Functions::test_input(Input::get('estimated_completion_date'));
    if($order_no>0){
      $order = ClientImportOrders::where([
                            ['order_no',$order_no],
                            ['status',Config::get('constants.success')]
                          ])->first();
      if(!empty($estimated_completion_date)){
        $order->estimated_completion_date = Functions::dbDateFormat(str_replace('/','-',$estimated_completion_date));
      }else{
        $order->estimated_completion_date = NULL;
      }
      if($order->save()){
        $data['status'] = 'success';
      }else{
        $data['status'] = 'error';
      }
      return json_encode($data);
    }
  }

  public function SaveOrderEditable(){
    $column_name = Input::get('name');
    $model = ClientImportOrderDetails::where('id',Input::get('pk'))->first();
    $model->$column_name = Input::get('value');
    $model->save();
  }

  public function DeleteOrder($order_no){
    $order = ClientImportOrders::where([
                                        ['status',Config::get('constants.success')],
                                        ['order_no',$order_no]
                                      ])->first();
    $fk_user_id = $order->fk_user_id;
    if($order->delete()){
      $order_detail = ClientImportOrderDetails::where([
                                          ['fk_order_no',$order_no]
                                        ])->delete();
      return redirect(action('OrdersController@OrderManagement',['id'=>$fk_user_id]))->withSuccess('Order deleted successfully.');
    }else{
    return redirect(action('OrdersController@OrderManagement',['id'=>$fk_user_id]))->withError(Config::get('constants.error_message'));
    }
  }
}
?>
