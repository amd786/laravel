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
use App\Models\PrPurchaseOrder;
use App\Models\PrSupplier;
use App\Models\Quotes;
use App\Models\QuoteDetails;
use App\Models\Company;
use App\Models\Contact;
use App\Models\PrSupplierContact;
use App\Models\PaymentTerm;
use App\Models\AccInvoices;
use App\Models\AccAccounts;
use App\Models\AssignProd;
use App\Models\AccInvoiceItems;
use App\Models\AccTransactions;
use App\Models\AccAccountCategories;
use App\Models\AccAccountDetails;

class AccountingController extends Controller
{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('check')->except(['index']);
  }

  public function index(){
    return view('accounting.index');
  }

  public function PurchaseOrders(){
    return view('accounting.purchase_orders');
  }

  public function PendingPurchaseOrders(PrPurchaseOrder $pending_orders){
    $search = Functions::test_input(Input::get('search'));
    $date_filter = Functions::test_input(Input::get('date_filter'));
    $query = $pending_orders->newQuery();
    $query->where([
                    ['status',Config::get('constants.success')],
                    ['accounting_approval',Config::get('constants.accounting_purchase_order_pending')]
                  ]);
    if(!empty($search)){
      $query->where('purchase_order', 'like', '%'.strtoupper($search).'%');
    }
    if(!empty($date_filter)){
      $return = Functions::date_range($date_filter);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    $pending_orders = $query->orderBy('id','desc')->Paginate(5);
    return view('accounting.pending_purchase_orders',
                  ['pending_orders'=>$pending_orders]
                )->render();
  }

  public function ApprovedPurchaseOrders(PrPurchaseOrder $approved_orders){
    $search = Functions::test_input(Input::get('search'));
    $date_filter = Functions::test_input(Input::get('date_filter'));
    $query = $approved_orders->newQuery();
    $query->where([
                    ['status',Config::get('constants.success')],
                    ['accounting_approval',Config::get('constants.accounting_purchase_order_approved')]
                  ]);
    if(!empty($search)){
      $query->where('purchase_order', 'like', '%'.strtoupper($search).'%');
    }
    if(!empty($date_filter)){
      $return = Functions::date_range($date_filter);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    $approved_orders = $query->orderBy('id','desc')->Paginate(5);
    return view('accounting.approved_purchase_orders',
                  ['approved_orders'=>$approved_orders]
                )->render();

  }

  public function RejectedPurchaseOrders(PrPurchaseOrder $rejected_orders){
    $search = Functions::test_input(Input::get('search'));
    $date_filter = Functions::test_input(Input::get('date_filter'));
    $query = $rejected_orders->newQuery();
    $query->where([
                    ['status',Config::get('constants.success')],
                    ['accounting_approval',Config::get('constants.accounting_purchase_order_rejected')]
                  ]);
    if(!empty($search)){
      $query->where('purchase_order', 'like', '%'.strtoupper($search).'%');
    }
    if(!empty($date_filter)){
      $return = Functions::date_range($date_filter);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    $rejected_orders = $query->orderBy('id','desc')->Paginate(5);
    return view('accounting.rejected_purchase_orders',
                  ['rejected_orders'=>$rejected_orders]
                )->render();

  }

  public function RejectPurchaseOrder(Request $request){
    $data = $request->all();
    if($data['order_id']>0){
      $update_purchase_order = PrPurchaseOrder::where([
                                                        ['status',Config::get('constants.success')],
                                                        ['id',$data['order_id']]
                                                      ])->first();
      $update_purchase_order->accounting_approval = Config::get('constants.accounting_purchase_order_rejected');
      if($update_purchase_order->save()){
        return redirect(action('AccountingController@PurchaseOrders'))->withSuccess('Order Rejected Successfully');
      }
    }
  }

  public function ApprovePurchaseOrder(Request $request){
    $data = $request->all();
    if($data['order_id']>0){
      $update_purchase_order = PrPurchaseOrder::where([
                                                        ['status',Config::get('constants.success')],
                                                        ['id',$data['order_id']]
                                                      ])->first();
      $update_purchase_order->accounting_approval = Config::get('constants.accounting_purchase_order_approved');
      $update_purchase_order->accounting_approval_at = date('y-m-d');
      if($update_purchase_order->save()){
        return redirect(action('AccountingController@PurchaseOrders'))->withSuccess('Order Approved Successfully');
      }
    }
  }

  public function PurchaseOrderDetails(PrPurchaseOrder $order_detail, $id){
    $order_detail = PrPurchaseOrder::where([
                                            ['status',Config::get('constants.success')],
                                            ['id',$id]
                                          ])->first();
    return view('accounting.purchase_order_details',
                  ['order_detail'=>$order_detail]
                )->render();
  }

  public function Accounts(){
    $module = "accounting";
    $suppliers = PrSupplier::select('id','supplier_name')->where([
                                                                  ['status',Config::get('constants.success')]
                                                                ])->get();
    return view('purchase.supplier_address_book',['suppliers'=>$suppliers,'module'=>$module])->render();
  }

  public function AddSupplier(){
    $module = "accounting";
    $payment_terms = new PaymentTerm;
    return view('purchase.add_supplier',['module'=>$module,'payment_terms'=>$payment_terms])->render();
  }

  public function SaveSupplier(Request $request){
    $return = Functions::saveSupplier($request);
    if($return[0]=='validation'){
      Session::flash('error', $return[1]);
      return redirect(action('AccountingController@AddSupplier'))->withInput();
    }elseif($return[0]=='success'){
      return redirect(action('AccountingController@Accounts'))->withSuccess($return[1]);
    }else{
      return redirect(action('AccountingController@Accounts'))->withError($return[1]);
    }
  }

  public function AddSupplierContact(){
    $module = "accounting";
    $PrSupplier = new PrSupplier;
    return view('purchase.add_supplier_contact',['PrSupplier'=>$PrSupplier,'module'=>$module])->render();
  }

  public function SaveSupplierContact(Request $request){
    $return = Functions::saveSupplierContact($request);
    if($return[0]=='validation'){
      Session::flash('error', $return[1]);
      return redirect(action('AccountingController@AddSupplierContact'))->withInput();
    }elseif($return[0]=='lead_exists'){
      return redirect(action('AccountingController@AddSupplierContact'))->withError($return[1]);
    }elseif($return[0]=='success'){
      return redirect(action('AccountingController@Accounts'))->withSuccess($return[1]);
    }else{
      return redirect(action('AccountingController@Accounts'))->withError($return[1]);
    }
  }

  public function SupplierProfile($id){
    $module = "accounting";
    $supplier = PrSupplier::where([
                                    ['status',Config::get('constants.success')],
                                    ['id',$id]
                                  ])->first();
    return view('purchase.supplier_profile',['supplier'=>$supplier,'module'=>$module])->render();
  }

  public function UpdateSupplier(Request $request){
    $data = $request->all();
    $return = Functions::updateSupplier($request);
    if($return[0]=='validation'){
      Session::flash('error', $return[1]);
      return redirect(action('AccountingController@AddSupplierContact'))->withInput();
    }elseif($return[0]=='success'){
      return redirect(action('AccountingController@SupplierProfile',['id'=>$data['fk_supplier_id']]))->withSuccess($return[1]);
    }else{
      return redirect(action('AccountingController@SupplierProfile',['id'=>$data['fk_supplier_id']]))->withError($return[1]);
    }
  }

  public function OrdersByDate(PrPurchaseOrder $orders){
    $search = Functions::test_input(Input::get('search'));
    $date_filter = Functions::test_input(Input::get('date_filter'));
    $query = $orders->newQuery();
    $query->where([
                    ['status',Config::get('constants.success')]
                  ]);
    if(!empty($search)){
      $query->where('purchase_order', 'like', '%'.strtoupper($search).'%');
    }
    if(!empty($date_filter)){
      $return = Functions::date_range($date_filter);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    $orders = $query->groupBy(DB::raw('Date(created_at)'))->orderBy('created_at', 'desc')->Paginate(5);
    return view('accounting.orders_by_date',
                  ['orders'=>$orders,'search'=>$search,'date_filter'=>$date_filter]
                )->render();
  }

  public function PaymentSchedule(Quotes $quotes){
    $sales = [];
    // fetch sales
    $quotes = Quotes::where([
                            ['status',Config::get('constants.success')],
                            ['order_reviewed','yes'],
                            ['order_status','won']
                          ])->get();
    if(count($quotes)>0){
      foreach ($quotes as $quote) {
        $sales[] = array(
                          'id' =>$quote->id,
                          'cost'=>'$'.$quote->final_amount,
                          'name'=>ucwords($quote->Company->company_name),
                          'start'=>date('Y-m-d', strtotime($quote->order_reviewed_at. ' + '.$quote->PaymentTerm->payment_days.' days')),
                          'end'=>date('Y-m-d', strtotime($quote->order_reviewed_at. ' + '.$quote->PaymentTerm->payment_days.' days')),
                          'className'=>'transaction_title sales '.$quote->getPaymentRecord(),
                          'company_id'=>$quote->fk_company_id,
                          'transaction' => 'sales',
                          'term_id' => $quote->fk_term_id,
                          'payment_status' => $quote->getPaymentRecord()
                        );
      }
    }
    $expense = [];
    // fetch expenses
    $purchase_orders = PrPurchaseOrder::where([
                                      ['status',Config::get('constants.success')],
                                      ['accounting_approval',Config::get('constants.accounting_purchase_order_approved')]
                                    ])->get();
    if(count($purchase_orders)>0){
      foreach ($purchase_orders as $purchase_order) {
        $expense[] = array(
                            'id' =>$purchase_order->id,
                            'cost'=>'($'.round($purchase_order->cost,2).')',
                            'name'=>ucwords($purchase_order->getSupplierDetail->supplier_name),
                            'start'=>date('Y-m-d', strtotime($purchase_order->accounting_approval_at. ' + '.$purchase_order->getSupplierDetail->getPaymentTermDetail->payment_days.' days')),
                            'end'=>date('Y-m-d', strtotime($purchase_order->accounting_approval_at. ' + '.$purchase_order->getSupplierDetail->getPaymentTermDetail->payment_days.' days')),
                            'className'=>'transaction_title expense '.$purchase_order->getPaymentRecord(),
                            'supplier_id'=>$purchase_order->fk_supplier_id,
                            'transaction'=> 'expense',
                            'term_id' => $purchase_order->getSupplierDetail->fk_term_id,
                            'payment_status' => $purchase_order->getPaymentRecord()
                          );
      }
    }
    $transactions = array_merge($sales,$expense);
    return view('accounting.payment_schedule',['transactions'=>json_encode($transactions)])->render();
  }

  // get company lead
  public function GetLeadContact(){
    $payment_record = array();
    $id = Functions::test_input(Input::get('id'));
    $transaction = Functions::test_input(Input::get('transaction'));
    $cost = Functions::test_input(Input::get('cost'));
    $term_id = Functions::test_input(Input::get('term_id'));
    $order_id = Functions::test_input(Input::get('order_id'));
    if($id>0){
      if($transaction=='sales'){
        $db_transaction = 0;
        $accounts_exists = AccAccounts::where([
                                              ['status',Config::get('constants.success')],
                                              ['fk_company_id',$id]
                                            ])->first();
        if(count($accounts_exists)>0){
          $payment_record = AccTransactions::where([
                                  ['status',Config::get('constants.success')],
                                  ['fk_quote_id',$order_id],
                                  ['fk_account_id',$accounts_exists->id]
                                ])->first();
        }
        $account = Company::where([
                                ['status',Config::get('constants.success')],
                                ['company_id',$id]
                              ])->first();
        $lead = Contact::where([
                                ['status',Config::get('constants.success')],
                                ['fk_company_id',$id],
                                ['lead_contact',1]
                              ])->first();
        $order_detail = Quotes::where([
                                ['status',Config::get('constants.success')],
                                ['id',$order_id]
                              ])->first();

      }elseif ($transaction=='expense') {
        $db_transaction = 1;
        $accounts_exists = AccAccounts::where([
                                              ['status',Config::get('constants.success')],
                                              ['fk_supplier_id',$id]
                                            ])->first();
        if(count($accounts_exists)>0){
          $payment_record = AccTransactions::where([
                                  ['status',Config::get('constants.success')],
                                  ['fk_purchase_order_id',$order_id],
                                  ['fk_account_id',$accounts_exists->id]
                                ])->first();
        }
        $account = PrSupplier::where([
                                ['status',Config::get('constants.success')],
                                ['id',$id]
                              ])->first();
        $lead = PrSupplierContact::where([
                                ['status',Config::get('constants.success')],
                                ['fk_supplier_id',$id],
                                ['lead_contact',1]
                              ])->first();
        $order_detail = PrPurchaseOrder::where([
                                ['status',Config::get('constants.success')],
                                ['id',$order_id]
                              ])->first();
      }
      $payment_terms = PaymentTerm::where([
                              ['status',Config::get('constants.success')],
                              ['id',$term_id]
                            ])->select('incoterm')->first();
    }
    return view('accounting.lead_contact',['lead'=>$lead,'account'=>$account,'cost'=>$cost,'payment_terms'=>$payment_terms,'order_detail'=>$order_detail,'db_transaction'=>$db_transaction,'payment_record'=>$payment_record])->render();
  }

  // record payment
  public function SavePaymentRecord(Request $request){
    $data = $request->all();
    if($data['db_transaction']==0){ //sales
      $account = AccAccounts::where([
                              ['status',Config::get('constants.success')],
                              ['fk_company_id',$data['fk_company_id']]
                            ])->select('id')->first();
    }elseif($data['db_transaction']==1){ // expense
      $account = AccAccounts::where([
                              ['status',Config::get('constants.success')],
                              ['fk_supplier_id',$data['fk_supplier_id']]
                            ])->select('id')->first();
    }
    if(count($account)>0){
      if($data['db_transaction']==0){
        $record_exists = AccTransactions::where([
                                                  ['fk_quote_id',$data['fk_quote_id']],
                                                  ['status',Config::get('constants.success')]
                                                ])->first();
      }elseif ($data['db_transaction']==1) {
        $record_exists = AccTransactions::where([
                                                  ['fk_purchase_order_id',$data['fk_purchase_order_id']],
                                                  ['status',Config::get('constants.success')]
                                                ])->first();
      }
      $data['fk_account_id'] = $account->id;
      $data['fk_user_id'] = Auth::user()->id;
      $data['transaction_date'] = Functions::dbDateFormat(str_replace('/','-',$data['transaction_date']));
      $data['status'] = Config::get('constants.success');
      if(count($record_exists)>0){
        $record_exists->fill($data)->save();
        $id = $record_exists->id;
      }else{
        $model = new AccTransactions();
        $id = $model->create($data)->id;
      }
      $record = AccTransactions::where([
                                        ['status',Config::get('constants.success')],
                                        ['id',$id]
                                      ])->first();
      $update_date = date('M d, Y',strtotime($record->updated_at));
      return json_encode(array('status'=>'success','updated_date'=>$update_date));
    }else{
      return json_encode(array('status'=>'failure','message'=>'Please Add Account in Accounts Book'));
    }
    /*$order_id = Functions::test_input(Input::get('order_id'));
    $transaction = Functions::test_input(Input::get('db_transaction'));
    $payment_date = Functions::test_input(Input::get('payment_date'));
    if($order_id>0){
      $data['payment_date'] = $payment_date;
      $data['payment_by'] = Auth::User()->id;
      $data['transaction'] = $transaction;
      $data['status'] = Config::get('constants.success');
      if($transaction==0){      // sales
        $data['fk_quote_id'] = $order_id;
        $record_exists = AccPaymentRecord::where([
                                                  ['fk_quote_id',$order_id],
                                                  ['status',Config::get('constants.success')]
                                                ])->first();
        if(count($record_exists)>0){
          //$model = $record_exists;
          $record_exists->fill($data)->save();
          $id = $record_exists->id;
        }else{
          $model = new AccPaymentRecord();
          $id = $model->create($data)->id;
        }
      }elseif($transaction==1){ // expense
        $data['fk_purchase_order_id'] = $order_id;
        $record_exists = AccPaymentRecord::where([
                                                  ['fk_purchase_order_id',$order_id],
                                                  ['status',Config::get('constants.success')]
                                                ])->first();
        if(count($record_exists)>0){
          //$model = $record_exists;
          $record_exists->fill($data)->save();
          $id = $record_exists->id;
        }else{
          $model = new AccPaymentRecord();
          $id = $model->create($data)->id;
        }
      }
      $record = AccPaymentRecord::where([
                                          ['status',Config::get('constants.success')],
                                          ['id',$id]
                                        ])->first();
      $update_date = date('M d, Y',strtotime($record->updated_at));
      return json_encode(array('status'=>'success','updated_date'=>$update_date));
    }*/
  }

  public function Invoices(){
    $invoice_model = new AccInvoices;
    return view('accounting.invoices',['invoice_model'=>$invoice_model])->render();
  }

  public function CreateInvoice(){
    $invoices = new AccInvoices;
    return view('accounting.create_invoice',['invoices'=>$invoices])->render();
  }

  public function AccountDetail(){
    $name = "";
    $response = array();
    $fk_account_id = Functions::test_input(Input::get('fk_account_id'));
    if($fk_account_id>0){
      $account = AccAccounts::where([
                                          ['status',Config::get('constants.success')],
                                          ['id',$fk_account_id]
                                        ])->first();
      if($account->fk_supplier_id>0){
        $account_detail = PrSupplier::where([
                                        ['status',Config::get('constants.success')],
                                        ['id',$account->fk_supplier_id]
                                      ])->first();
        $name = ucwords($account_detail->supplier_name);
      }elseif($account->fk_company_id>0){
        $account_detail = Company::where([
                                    ['status',Config::get('constants.success')],
                                    ['company_id',$account->fk_company_id]
                                  ])->first();
        $name = ucwords($account_detail->company_name);
      }else{
        $account_detail = AccAccountDetails::where([
                                              ['status',Config::get('constants.success')],
                                              ['fk_account_id',$account->id]
                                            ])->select('name')->first();
        $name = ucwords($account_detail->name);
      }
      $street = !empty($account_detail->street_name) ?ucwords($account_detail->street_name).'<br>' : "";
      $city = !empty($account_detail->city) ?ucwords($account_detail->city).', ' : "";
      $code = !empty($account_detail->postal_code) ? strtoupper($account_detail->postal_code).', ' : "";
      $country = !empty($account_detail->country) ? ucwords($account_detail->country) : "";
      $address = $street.$city.$code.$country;
      $response = array('status'=>'success','address'=>$address,'name'=>$name);

    }
    return json_encode($response);
  }

  // save invoice to db
  public function SaveInvoice(Request $request){
    // validation rules
    $rules = [
      'invoice_date'=>'required',
      'invoice_due_date'=>'required',
      'fk_account_id'=>'required',
      'fk_account_category_id'=>'required'
    ];
    $messsages = [
      'fk_account_id.required'=>'Account field is required.',
      'fk_account_category_id.required'=>'Type/Category field is required.'
    ];
    $validator = Validator::make(Input::all(), $rules, $messsages);
    if ($validator->fails())
    {
        $messages = $validator->messages();
        if (!empty($messages)) {
            foreach ($messages->all() as $error) {
               Session::flash('error', $error);
               return redirect(action('AccountingController@CreateInvoice'))->withInput();
            }
        }
    }
    //validation rules ended
    $data = $request->all();
    if(count($data['sku'])>0){
      foreach ($data['sku'] as $sku) {
        $product_detail = AssignProd::where([
                                              ['status',Config::get('constants.success')],
                                              ['code',$sku]
                                            ])->select('id')->first();
        $fk_assign_product_ids[] = $product_detail->id;
      }
    }

    // save to invoice table
    $model = new AccInvoices();
    $data['invoice_status'] = 0;
    $data['invoice_date'] = Functions::dbDateFormat(str_replace('/','-',$data['invoice_date']));
    $data['invoice_due_date'] = Functions::dbDateFormat(str_replace('/','-',$data['invoice_due_date']));
    $data['status'] = Config::get('constants.success');
    $data['added_by'] = Auth::User()->id;
    $fk_invoice_id = $model->create($data)->id;

    // save to invoice items table
    if(count($fk_assign_product_ids)>0){
      foreach ($fk_assign_product_ids as $key => $fk_assign_product_id) {
        $model = new AccInvoiceItems();
        $item['fk_invoice_id'] = $fk_invoice_id;
        $item['fk_assign_product_id'] = $fk_assign_product_id;
        $item['description'] = $data['description'][$key];
        $item['quantity'] = $data['quantity'][$key];
        $item['unit_cost'] = $data['unit_cost'][$key];
        $item['status'] = Config::get('constants.success');
        $model->create($item);
      }
    }
    return redirect(action('AccountingController@Invoices'))->withSuccess('Invoice Added successfully');
  }

  public function InvoicesByDate(AccInvoices $invoices){
    $start_date = "";
    $end_date = "";
    $fk_account_id = Functions::test_input(Input::get('fk_account_id'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));
    $date_filter = Functions::test_input(Input::get('date_filter'));
    $query = $invoices->newQuery();
    $query->where('status',Config::get('constants.success'));
    if($fk_account_id>0){
      $query->where('fk_account_id',$fk_account_id);
    }
    if(!empty($start_date) && !empty($end_date)){
      $query->whereBetween( DB::raw('date(created_at)'), [$start_date,$end_date]);
    }
    if(!empty($date_filter)){
      $return = Functions::date_range($date_filter);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    $invoices = $query->groupBy(DB::raw('Date(created_at)'))->orderBy('created_at', 'desc')->Paginate(5);
    return view('accounting.invoices_by_date',
                [ 'invoices'=>$invoices,
                  'fk_account_id'=>$fk_account_id,
                  'date_filter'=>$date_filter,
                  'start_date'=>$start_date,
                  'end_date'=>$end_date
                ])->render();
  }

  // overview and reports
  public function OverviewAndReports(){
    // fetch chart data
    $acc_transactions = new AccTransactions;
    $transactions = $acc_transactions->NetIncomeChartData();
    $expenses = $acc_transactions->ExpenseChartData();
    // expense chart
    $axis = array('Account','Amount');
    $paid_to = "";
    $pie_chart = array($axis);
    foreach ($expenses as $key => $expense) {
      if($expense->fk_supplier_id>0){
        $supplier = PrSupplier::where([
                                        ['status',Config::get('constants.success')],
                                        ['id',$expense->fk_supplier_id]
                                      ])->select('supplier_name')->first();
        $paid_to = ucwords($supplier->supplier_name);
      }elseif($expense->fk_company_id>0){
        $company = Company::where([
                                    ['status',Config::get('constants.success')],
                                    ['company_id',$expense->fk_company_id]
                                  ])->select('company_name')->first();
        $paid_to = ucwords($company->company_name);
      }else{
        $account_detail = AccAccountDetails::where([
                                              ['status',Config::get('constants.success')],
                                              ['fk_account_id',$expense->id]
                                            ])->select('name')->first();
        $paid_to = ucwords($account_detail->name);
      }
      $pie_chart[$key+1] = array($paid_to,$expense->amount);
    }
    // net income chart
    $months = array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
    $xaxis = array('Months','Income','Expenses');
    $area_chart = array($xaxis);
    foreach ($months as $key => $month) {
      $sales = 0;
      $expense = 0;
      foreach ($transactions as $transaction) {
        if($transaction->month_int==$key){
          if($transaction->type==Config::get('constants.account_payable')){
            $expense = $transaction->amount;
          }elseif($transaction->type==Config::get('constants.account_receivable')){
            $sales = $transaction->amount;
          }
        }
      }
      $area_chart[$key] = array($month,$sales,$expense);
    }
    // upcoming payments
    $sales = (Quotes::where([
                            ['status',Config::get('constants.success')],
                            ['order_reviewed',"'yes'"],
                            ['order_status',"'won'"]
                          ])->select(
                            DB::raw("'sale' as action"),'order_reviewed_at AS payment_date','final_amount as amount','fk_company_id'
                            )->orderBy(
                              'order_reviewed_at','desc'
                            ));
    $upcoming_payments = PrPurchaseOrder::where([
                                      ['status',Config::get('constants.success')],
                                      ['accounting_approval',Config::get('constants.accounting_purchase_order_approved')]
                                    ])->select(
                                      DB::raw("'expense' as action"),'accounting_approval_at AS payment_date','cost as amount','fk_supplier_id'
                                      )->orderBy(
                                        'accounting_approval_at','desc'
                                        )->union($sales)->orderBy('payment_date','desc')->limit(3)->get();
    return view('accounting.overview-and-reports',[
                                                    'area_chart'=>json_encode($area_chart),
                                                    'pie_chart'=>json_encode($pie_chart),
                                                    'upcoming_payments'=>$upcoming_payments
                                                  ])->render();
  }

  // transactions section
  public function Transactions(){
    $invoices = new AccInvoices;
    return view('accounting.transactions',[
                                          'invoices'=>$invoices
                                          ])->render();
  }

  public function SaveTransaction(Request $request){
    // validation rules
    $rules = [
      'transaction_date'=>'required',
      'amount'=>'required',
      'fk_account_id'=>'required',
      'fk_account_category_id'=>'required'
    ];
    $messsages = [
      'fk_account_id.required'=>'Account field is required.',
      'fk_account_category_id.required'=>'Type/Category field is required.'
    ];
    $validator = Validator::make(Input::all(), $rules, $messsages);
    if ($validator->fails())
    {
      $messages = $validator->messages();
      if (!empty($messages)) {
          foreach ($messages->all() as $error) {
             Session::flash('error', $error);
             return redirect(action('AccountingController@Transactions'))->withInput();
          }
      }
    }
    //validation rules ended
    $data = $request->all();
    $model = new AccTransactions();
    $data['transaction_date'] = Functions::dbDateFormat(str_replace('/','-',$data['transaction_date']));
    $data['status'] = Config::get('constants.success');
    $data['fk_user_id'] = Auth::User()->id;
    $id = $model->create($data)->id;
    if($id>0){
      return redirect(action('AccountingController@Transactions'))->withSuccess('Transaction added Successfully');
    }else{
      return redirect(action('AccountingController@Transactions'))->withError('Transaction cannot be addded');
    }
  }

  public function TransactionsByDate(AccTransactions $transactions){
    $start_date = "";
    $end_date = "";
    $search = "";
    $search = Functions::test_input(Input::get('search'));
    $fk_account_id = Functions::test_input(Input::get('fk_account_id'));
    $start_date = Functions::test_input(Input::get('start_date'));
    $end_date = Functions::test_input(Input::get('end_date'));
    $date_filter = Functions::test_input(Input::get('date_filter'));
    $query = $transactions->newQuery();
    $query->where('acc_transactions.status',Config::get('constants.success'));
    if(!empty($search)){
      $fk_account_category_ids  = AccAccountCategories::where([
                                                          ['status','=',Config::get('constants.success')],
                                                          ['category','like','%'.$search.'%']
                                                        ])->orderBy('id')->pluck('id');
      $query->join('acc_accounts','acc_accounts.id', '=','acc_transactions.fk_account_id');
      $query->where(function($query) use ($fk_account_category_ids,$search){
            $query->orWhere('amount', 'like', '%'.$search.'%');
            foreach($fk_account_category_ids as $fk_account_category_id) {
              $query->orWhere('acc_accounts.fk_account_category_id', '=', $fk_account_category_id);
            }
          });
    }
    if($fk_account_id>0){
      $query->where('fk_account_id',$fk_account_id);
    }
    if(!empty($start_date) && !empty($end_date)){
      $query->whereBetween( DB::raw('date(acc_transactions.created_at)'), [$start_date,$end_date]);
    }
    if(!empty($date_filter)){
      $table_name = 'acc_transactions';
      $return = Functions::date_range($date_filter,$table_name);
      $return = json_decode($return);
      $filter_this = $return[0];
      $filter_by = $return[1];
      $query->where(DB::raw($filter_this) , DB::raw($filter_by));
    }
    $transactions = $query->groupBy(DB::raw('Date(acc_transactions.created_at)'))->orderBy('acc_transactions.created_at', 'desc')->select('acc_transactions.created_at')->Paginate(5);
    $accounts_model = new AccAccounts;
    return view('accounting.transactions_by_date',
                [ 'transactions'=>$transactions,
                  'search'=>$search,
                  'fk_account_id'=>$fk_account_id,
                  'date_filter'=>$date_filter,
                  'start_date'=>$start_date,
                  'end_date'=>$end_date,
                  'accounts_model'=>$accounts_model
                ])->render();
  }

  public function AccountsPayable(){
    $accounts_model = new AccAccounts;
    $categories = AccAccountCategories::where([
                                    ['status',Config::get('constants.success')],
                                    ['type',1]
                                  ])->Paginate(10);
    return view('accounting.accounts',[
                                      'categories'=>$categories,
                                      'type'=>1,
                                      'title'=>'Accounts Payable',
                                      'accounts_model'=>$accounts_model
                                      ])->render();
  }
  public function AccountsReceivable(){
    $accounts_model = new AccAccounts;
    $categories = AccAccountCategories::where([
                                    ['status',Config::get('constants.success')],
                                    ['type',2]
                                  ])->Paginate(10);
    return view('accounting.accounts',[
                                      'categories'=>$categories,
                                      'type'=>2,
                                      'title'=>'Accounts Receivable',
                                      'accounts_model'=>$accounts_model
                                      ])->render();
  }

  public function AccountsByCategory(){
    $data = array();
    $fk_account_category_id = Functions::test_input(Input::get('fk_account_category_id'));
    if($fk_account_category_id>0){
      $accounts = AccAccounts::where([
                                    ['status','=',Config::get('constants.success')],
                                    ['fk_account_category_id',$fk_account_category_id]
                                  ])->orderBy('id')->get();
    }
    if(count($accounts)>0){
      foreach ($accounts as $key => $account) {
        if($account->fk_supplier_id>0){
          $supplier = PrSupplier::where([
                                        ['status','=',Config::get('constants.success')],
                                        ['id',$account->fk_supplier_id]
                                      ])->select('supplier_name')->first();
          $data[] = array('id'=>$account->id,'account_name'=>$supplier->supplier_name);
        }elseif($account->fk_company_id>0){
          $company = Company::where([
                                        ['status','=',Config::get('constants.success')],
                                        ['company_id',$account->fk_company_id]
                                      ])->select('company_name')->first();
          $data[] = array('id'=>$account->id,'account_name'=>$company->company_name);
        }else{
          $account_detail = AccAccountDetails::where([
                                        ['status','=',Config::get('constants.success')],
                                        ['fk_account_id',$account->id]
                                      ])->select('name')->first();
          $data[] = array('id'=>$account->id,'account_name'=>$account_detail->name);
        }
      }
    }

    //echo "<pre>"; print_r(json_encode($data)); die;
    return Response::json(array('status' => 'success', 'accounts' => $data));
  }

  public function SaveAccountCategory(Request $request){
    // validation rules
    $rules = [
      'category'=>'required'
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
    $data = $request->all();
    // save to accounts category table
    $model = new AccAccountCategories();
    $data['status'] = 1;
    $inserted_id = $model->create($data)->id;
    if($inserted_id>0){
      return redirect()->back()->withSuccess('Category Added Successfully');
    }else{
      return redirect()->back()->withError(Config::get('constants.error_message'));
    }
  }

  public function SaveAccount(Request $request){
    $data = $request->all();
    //echo "<pre>"; print_r($data); die;
    $already_exists = array();
    $rules = [
      'name'=>'required',
      'fk_account_category_id'=>'required'
    ];
    if($data['type']==1){// expense
      if($data['form_type']==1){
        $rules = [
          'fk_supplier_id'=>'required',
          'fk_account_category_id'=>'required'
        ];
      }
    }elseif($data['type']==2){ //sales
      if($data['form_type']==1){
        $rules = [
          'fk_company_id'=>'required',
          'fk_account_category_id'=>'required'
        ];
      }
    }
    // validation rules
    $messages = ['fk_supplier_id.required'=>'Select Supplier field is required.',
                  'fk_company_id.required'=>'Select Company field is required.'
                ];
    $validator = Validator::make(Input::all(), $rules, $messages);
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
    // if already added
    if($data['type']==1){// expense
      if($data['form_type']==1){
        $already_exists = AccAccounts::where([
                                              ['status','=',Config::get('constants.success')],
                                              ['fk_supplier_id',$data['fk_supplier_id']]
                                            ])->first();
      }
    }elseif($data['type']==2){ //sales
      if($data['form_type']==1){
        $already_exists = AccAccounts::where([
                                              ['status','=',Config::get('constants.success')],
                                              ['fk_company_id',$data['fk_company_id']]
                                            ])->first();
      }
    }
    if(count($already_exists)>0){
      return redirect()->back()->withError('Account already added');
    }else{
      // save to accounts table
      $model = new AccAccounts();
      $data['status'] = 1;
      if($data['form_type']==2){
        $data['fk_supplier_id'] = NULL;
        $data['fk_company_id'] = NULL;
      }
      $inserted_id = $model->create($data)->id;
      if($data['form_type']==2){
        $model = new AccAccountDetails();
        $data['fk_account_id'] = $inserted_id;
        $data['status'] = 1;
        $model->create($data);
      }
      if($inserted_id>0){
        return redirect()->back()->withSuccess('Account Added Successfully');
      }else{
        return redirect()->back()->withError(Config::get('constants.error_message'));
      }
    }
  }

  public function DeleteAccount($id){
    if($id>0){
      $account_detail = AccAccountDetails::where([['fk_account_id','=',$id]])->first();
      if(count($account_detail)>0){
        $account_detail->delete();
      }
      $account = AccAccounts::where([['id','=',$id]])->first();
      if($account->delete()){
        return redirect()->back()->withSuccess('Account deleted successfully.');
      }
    }
  }

  public function GetAccountDetail(){
    $account_detail = array();
    $account_id = Functions::test_input(Input::get('account_id'));
    if($account_id>0){
      $account_detail = AccAccountDetails::where([['fk_account_id','=',$account_id],['status',Config::get('constants.success')]])->first();
    }
    return view('accounting.edit_account',[
                                          'account_detail'=>$account_detail
                                          ])->render();
  }

  public function SaveEditAccount(Request $request){
    $data = $request->all();
    $rules = ['name'=>'required'];
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
    $account_detail = AccAccountDetails::where([['id','=',$data['account_detail_id']]])->first();
    if(count($account_detail)>0){
      $account_detail->name = $data['name'];
      $account_detail->street_name = $data['street_name'];
      $account_detail->city = $data['city'];
      $account_detail->country = $data['country'];
      $account_detail->postal_code = $data['postal_code'];
      if($account_detail->save()){
        return redirect()->back()->withSuccess('Account updated successfully.');
      }
    }
  }

}
?>
