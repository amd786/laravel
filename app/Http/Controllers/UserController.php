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
use App\Models\Contact;
use App\Library\Functions;
use Illuminate\Validation\Rule;
use App\Models\Quotes;
use Config;
use Mail;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['welcome','DeveloperMode','DeveloperModulesTable','EditModule','SaveModule','SaveEditModule','AddModule','DeleteModule']);
        $this->middleware('check')->except(['dashboard','index','anyData','EditProfile','welcome','DeveloperMode','DeveloperModulesTable','EditModule','SaveModule','SaveEditModule','AddModule','DeleteModule']);
    }

    public function Welcome(){
      if (Auth::user()) {   // Check is user logged in
        Auth::logout();
      }
      return view('welcome');
    }
    public function Dashboard(){
      $data['weekly_sales'] = 0;
      $data['weekly_lost_sale'] = 0;
      $data['monthly_sales'] = 0;
      $data['monthly_lost_sale'] = 0;

      $data['order_won_count'] = Quotes::where([['status',1],['order_status','won']])->count();
      $data['order_lost_count'] = Quotes::where([['status',1],['order_status','lost']])->count();

      $lastWeekStartTime = strtotime("-1 week");
      $lastWeekEndTime = strtotime("now");
      $lastWeekStart = date("Y-m-d",$lastWeekStartTime);
      $lastWeekEnd = date("Y-m-d",$lastWeekEndTime);

      $first_day_month =  date("Y-m-d", strtotime("first day of previous month"));
      $last_day_month =  date("Y-m-d", strtotime("last day of previous month"));

      $weekly_sales = Quotes::where([['status',1],['order_status','won']])->whereBetween( DB::raw('date(created_at)'), [$lastWeekStart, $lastWeekEnd])->get();
      if(count($weekly_sales)>0){
        foreach($weekly_sales as $weekly_sale){
          $data['weekly_sales'] = $data['weekly_sales'] + $weekly_sale->final_amount + $weekly_sale->shipping_cost;
        }
      }

      $weekly_lost_sales = Quotes::where([['status',1],['order_status','lost']])->whereBetween( DB::raw('date(created_at)'), [$lastWeekStart, $lastWeekEnd])->get();
      if(count($weekly_lost_sales)>0){
        foreach($weekly_lost_sales as $weekly_lost_sale){
          $data['weekly_lost_sale'] = $data['weekly_lost_sale'] + $weekly_lost_sale->final_amount + $weekly_lost_sale->shipping_cost;
        }
      }

      $monthly_sales = Quotes::where([['status',1],['order_status','won']])->whereBetween( DB::raw('date(created_at)'), [$first_day_month, $last_day_month])->get();
      if(count($monthly_sales)>0){
        foreach($monthly_sales as $monthly_sale){
          $data['monthly_sales'] = $data['monthly_sales'] + $monthly_sale->final_amount + $monthly_sale->shipping_cost;
        }
      }

      $monthly_lost_sales = Quotes::where([['status',1],['order_status','lost']])->whereBetween( DB::raw('date(created_at)'), [$first_day_month, $last_day_month])->get();
      if(count($monthly_lost_sales)>0){
        foreach($monthly_lost_sales as $monthly_lost_sale){
          $data['monthly_lost_sale'] = $data['monthly_lost_sale'] + $monthly_lost_sale->final_amount + $monthly_lost_sale->shipping_cost;
        }
      }


      return view('user.dashboard',['data'=>$data]);
    }

    public function Index()
    {
      return view('user.index');
    }

    public function AllUser()
    {
      return view('user.all_users');
    }

    public function anyData(Request $request)
    {

      DB::statement(DB::raw('set @rownum=0'));
      $query = User::select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'first_name',
                'last_name',
                'phone_no',
                'email',
                'updated_at'])
              ->where('status','=',1)->get();

      $data = Datatables::of($query)
                      ->addColumn('action', function ($userlist) {
                          return '<a href="'.action("UserController@EditUser",['id'=>$userlist->id]).'" class=""><i class="fa fa-pencil-square fa-lg fs30 padT10"></i></a>&nbsp;<a href="'.action("UserController@delete",['id'=>$userlist->id]).'" class="" onclick="return confirm(\'Do you want to delete this user?\')"><i class="fa fa-trash-o fa-2x fs30 padT10"></i></a>';
                      });
      if ($keyword = $request->get('search')['value']) {
            $data->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
      }
      return $data->make(true);
    }

    public function AddUser(){
      return view('user.add',['model'=>new User()]);
    }

    public function SaveUser(Request $request){

      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'fk_role_id'=>'required',
          'first_name'=>'required',
          'last_name'=>'required',
          'email'=>'required|Email|unique:users,email,NULL,deleted_at',
          'country_code'=>'required_with:area_code,tel_no|numeric',
          'area_code'=>'required_with:country_code,tel_no|numeric',
          'tel_no'=>'required_with:area_code,tel_no|numeric',
          'new_password' => 'required',
          'password_confirm' => 'required|same:new_password'
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            if (!empty($messages)) {
                foreach ($messages->all() as $error) {
                   Session::flash('error', $error);
                   return redirect(action('UserController@AddUser'))->withInput();
                }
            }
        }
        //validation rules ended

        $data = $request->all();
        $model = new User();
        if(!empty($data['birthday']))
        $data['birthday'] = date('Y-m-d H:i:s', strtotime($data['birthday']));
        $data['password'] = $data['new_password'];
        $data['show_password'] = $data['new_password'];
        $data['status'] = 1;
        $model->create($data);
        Session::flash('success','User is added successfully.');
        return redirect(action('UserController@AddUser'));
      }
    }

    public function EditUser($id){

        $user = User::where([['id','=',$id]])->first();

        return view('user.edit',['model'=>$user]);
    }

    public function UserDetail($id){

        $user = User::where([['id','=',$id]])->first();

        return view('user.detail',['model'=>$user]);
    }

    public function EditProfile(Request $request){

      $user = User::where([['status','=',1],['id','=',Auth::User()->id]])->first();
      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'first_name'=>'required',
          'last_name'=>'required',
          'email'=>'required|Email|unique:users,email,'.Auth::User()->id.',id,deleted_at,NULL',
          'password_confirm' => 'required_with:new_password|same:new_password',
          'country_code'=>'required_with:area_code,tel_no|numeric',
          'area_code'=>'required_with:country_code,tel_no|numeric',
          'tel_no'=>'required_with:area_code,tel_no|numeric'
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
        if(!empty($data['birthday'])){
          $data['birthday'] = date('Y-m-d H:i:s', strtotime($data['birthday']));
        }else{
          $data['birthday'] = null;
        }
        if(!empty($data['new_password']))
        $data['password'] = $data['new_password'];
        $data['fk_role_id'] = Auth::User()->fk_role_id;
        $data['commission'] = $user->commission;
        $user->fill($data)->save();
        Session::flash('success', 'Profile Updated successfully!');
        return redirect(action('UserController@index'));
      }
      return view('user.edit',['model'=>$user]);
    }

    public function SaveEditUser($id,Request $request){

      $user = User::where([['id','=',$id]])->first();
      if($request->isMethod('post')){
        // validation rules
        $rules = [
          'fk_role_id'=>'required',
          'first_name'=>'required',
          'last_name'=>'required',
          'email'=>'required|Email|unique:users,email,'.$id.',id,deleted_at,NULL',
          'password_confirm' => 'required_with:new_password|same:new_password',
          'country_code'=>'required_with:area_code,tel_no|numeric',
          'area_code'=>'required_with:country_code,tel_no|numeric',
          'tel_no'=>'required_with:area_code,tel_no|numeric'
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
        if(!empty($data['birthday'])){
          $data['birthday'] = date('Y-m-d H:i:s', strtotime($data['birthday']));
        }else{
          $data['birthday'] = null;
        }
        if(!empty($data['new_password']))
        $data['password'] = $data['new_password'];
        $user->fill($data)->save();
        Session::flash('success', 'User Updated successfully!');
        return redirect(action('UserController@UserDetail',['id'=>$id]));
      }
    }

    public function delete($id){
        $user = User::where([['id','=',$id]])->first();
        if($user->delete()){
          if($user->is_client==1){
            $redirect_action = 'UserController@ExternalUsers';
          }else{
            $redirect_action = 'UserController@AllUser';
          }
          return redirect(action($redirect_action))->withSuccess('User is deleted successfully.');
        }else{
          return redirect(action('UserController@AllUser'))->withError('Something went wrong.');
        }
        return redirect()->back();
    }

    public function roles(){

      $roles = Role::where([['status','=',1]])->get();
      $modules = Module::where([['status','=',1]])->groupBy('cat_name')->orderBy('sort_order')->get();
      foreach($modules as $module){
        $sub_modules[$module->cat_name] = Module::where([['cat_name','=',$module->cat_name],['status','=',1]])->orderBy('sort_order')->get();
      }

      $users = User::where('status','=',1)->get();

      return View('user.roles',['roles'=>$roles,'modules'=>$modules,'sub_modules'=>$sub_modules,'users'=>$users]);
    }

    public function AddRole(){

      // validation rules
      $rules = [
        'role_name'=>'required'
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
      $model = new Role;
      $data['status'] = 1;
      if($model->create($data))
      Session::flash('success','New role added successfully.');
      return redirect(action('UserController@roles'));
    }

    public function SavePermission(){

      $data = Input::all();
      if(isset($data['module_id']) && count($data['module_id'])>0){
        foreach($data['module_id'] as $key=>$val){
          $data['fk_module_id'] = $key;
          $data['permission'] = $val;
          $data['fk_role_id'] = $data['role_id'];
          $model = new Permission;
          $model->create($data);
        }
        Session::flash('success','Permission saved successfully.');
        return redirect(action('UserController@roles'));
      }else{
        Session::flash('error','Please assign permissions.');
        return redirect(action('UserController@roles'));
      }
    }

    public function DeleteRole($id){

      $role = Role::where('role_id','=',$id)->first();
      $role->Permissions()->delete();
      $role->Users()->delete();
      Role::where('role_id','=',$id)->delete();
      Session::flash('success','Role is deleted successfully.');
      return redirect()->back();
    }

    public function AssignUsers(){
      $data = Input::all();
      if(isset($data['users']) && count($data['users'])>0){
        foreach($data['users'] as $user_id){
          $model = User::where('id','=',$user_id)->first();
          $model->fk_role_id = $data['role_id'];
          $model->save();
        }
        Session::flash('success','Role is successfully assigned to users.');
        return redirect()->back();
      }else{
        Session::flash('error','Please select Users.');
        return redirect()->back();
      }
    }

    public function EditRole($role_id){

      $role = Role::where([['role_id','=',$role_id],['status','=','1']])->first();
      $modules = Module::where('status','=',1)->groupBy('cat_name')->orderBy('sort_order')->get();
      foreach($modules as $module){
        $sub_modules[$module->cat_name] = Module::where([['cat_name','=',$module->cat_name],['status','=',1]])->orderBy('sort_order')->get();
      }
      $users = User::where('status','=',1)->get();

      return View('user.edit_role',['role'=>$role,'modules'=>$modules,'sub_modules'=>$sub_modules,'users'=>$users]);
    }

    public function SaveEditRole($role_id){

      $data = Input::all();
      // validation
      if(empty($data['role_name'])){
        Session::flash('error','Please type role name.');
        return redirect()->back();
      }else if(empty($data['module_id'])){
        Session::flash('error','Please select modules.');
        return redirect()->back();
      }else if(empty($data['users'])){
        Session::flash('error','Please select users.');
        return redirect()->back();
      }
      // end validation
      // save role name
      $role = Role::where('role_id','=',$role_id)->first();
      $role->role_name = $data['role_name'];
      $role->save();
      // assign permissions
      foreach($data['module_id'] as $module_id=>$permission){
        $per = Permission::where([['fk_role_id','=',$role_id],['fk_module_id','=',$module_id]])->first();
        if($per == null){
          $per = new Permission;
        }
        $per->fk_role_id = $role_id;
        $per->fk_module_id = $module_id;
        $per->permission = $permission;
        $per->save();

      }
      // to save users
      foreach($data['users'] as $user_id){
        $model = User::where('id','=',$user_id)->first();
        $model->fk_role_id = $data['role_id'];
        $model->save();
      }
      Session::flash('success','Role is edited successfully');
      return redirect(action('UserController@roles'));

    }

    public function UsersTable(){
      $users = User::where([
                            ['is_client',0]
                          ])->select('id','first_name','last_name','email','status')->orderBy('id','desc')->Paginate(10);
      return view('user.users_table',['users'=>$users]);
    }

    public function DisableUser($id){
      $message = '';
      $user = User::where([
                            ['id','=',$id]
                          ])->first();
      if($user->status==0){
        $user->status = 1;
        $message = 'User Enabled Successfully';
      }else{
        $user->status = 0;
        $message = 'User Disabled Successfully';
      }
      if($user->save()){
        if($user->is_client==1){
          $redirect_action = 'UserController@ExternalUsers';
        }else{
          $redirect_action = 'UserController@AllUser';
        }
        return redirect(action($redirect_action))->withSuccess($message);
      }else{
        return redirect(action('UserController@AllUser'))->withError(Config::get('constants.error_message'));
      }
    }

    // reset password
    public function ResetPassword(Request $request,$id){
      // validation rules
      $rules = [
        'new_password'=>'required',
        'password_confirm' => 'required|same:new_password'
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
      $user = User::where([
                            ['id','=',$id]
                          ])->first();
      $user->password = $data['new_password'];
      if($user->save()){
        if($user->is_client==1){
          $redirect_action = 'UserController@ExternalUsers';
        }else{
          $redirect_action = 'UserController@AllUser';
        }
        return redirect(action($redirect_action))->withSuccess('Password Reseted Successfully');
      }else{
        return redirect(action('UserController@AllUser'))->withError(Config::get('constants.error_message'));
      }
    }

    // external users
    public function ExternalUsers(){
      return view('user.external_users');
    }

    public function ExternalUsersTable(){
      $clients = User::where([
                            ['is_client',1],
                            ['parent_user_id',0]
                          ])->select('id','first_name','last_name','email','status')->orderBy('id','desc')->Paginate(10);
      return view('user.external_users_table',['clients'=>$clients]);
    }

    public function AddClient(){
      $user_model = new User;
      return view('user.add_client',['user_model'=>$user_model]);
    }

    // save client
    public function SaveClient(Request $request){
      $inserted_id = 0;
      // validation rules
      $rules = [
        'fk_role_id'=> 'required',
        'fk_contact_id'=>'required',
        'first_name'=>'required',
        'last_name'=>'required',
        'new_password'=>'required',
        'password_confirm' => 'required|min:6|same:new_password'
      ];
      $messages = [
        'fk_role_id.required'=> 'Select Client Role is required.',
        'fk_contact_id.required'=> 'Select Contact is required.'
      ];
      $validator = Validator::make(Input::all(), $rules,$messages);
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

      // update contact table
      $contact = Contact::where([
                            ['status',Config::get('constants.success')],
                            ['contact_id',$data['fk_contact_id']]
                          ])->first();
      $contact->is_client = 1;
      $contact->save();

      // save to users table
      $model = new User();
      $data['password'] = $data['new_password'];
      $data['status'] = 1;
      $data['fk_role_id'] = $data['fk_role_id'];
      $data['fk_contact_id'] = $contact->contact_id;
      $data['is_client'] = 1;
      $data['email'] = $contact->email;
      $inserted_id = $model->create($data)->id;
      if($inserted_id>0){
        // send mail
        Mail::send('email.login_details', ['data'=>$data], function ($m) use ($contact) {
            $m->from('admin@powersealerp.com', 'Powerseal ERP');
            $m->to($contact->email, null)->subject('Thanks for Registration');
        });
        return redirect(action('UserController@ExternalUsers'))->withSuccess('Client Added Successfully');
      }else{
        return redirect(action('UserController@ExternalUsers'))->withError(Config::get('constants.error_message'));
      }
    }

    public function ContactDetail(){
      $contact = array();
      $fk_contact_id  = Functions::test_input(Input::get('fk_contact_id'));
      if($fk_contact_id>0){
        $contact = Contact::where([
                              ['status',Config::get('constants.success')],
                              ['contact_id',$fk_contact_id]
                            ])->select('first_name','last_name')->first();
      }
      return json_encode($contact);
    }

    // developer mode functions
    public function DeveloperMode($var){
      $authenticated = 0;
      $pwd = Config::get('constants.developer_mode');
      if($var===$pwd){
        $authenticated = 1;
      }
      return view('user.developer_mode',['pwd'=>$pwd,'authenticated'=>$authenticated]);
    }

    public function DeveloperModulesTable(){
      $pwd  = Functions::test_input(Input::get('pwd'));
      $modules = Module::where([['status',Config::get('constants.success')]])->orderBy('sort_order')->get();
      return view('user.developer_modules_table',['modules'=>$modules,'pwd'=>$pwd]);
    }

    public function EditModule($pwd,$id){
      $module = Module::where([
                                ['status',Config::get('constants.success')],
                                ['module_id',$id]
                              ])->first();
      return view('user.edit_module',['module'=>$module,'pwd'=>$pwd]);
    }

    public function DeleteModule($pwd,$id){
      $module = Module::where([
                                ['module_id',$id]
                              ])->first();
      if($module->delete()){
        return redirect(action('UserController@DeveloperMode',['var'=>$pwd]))->withSuccess('Module Deleted successfully');
      }else{
        return redirect(action('UserController@DeveloperMode',['var'=>$pwd]))->withError(Config::get('constants.error_message'));
      }
    }

    public function SaveModule(Request $request){
      $data = $request->all();
      $rules = [
        'controller'=>'required',
        'cat_name'=>'required',
        'subcat_name'=>'required',
        'actions'=>'required',
        'sort_order'=>'required'
      ];;
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
      $model = new Module();
      $data['status'] = Config::get('constants.success');
      $model->create($data);
      return redirect(action('UserController@DeveloperMode',['var'=>$data['pwd']]))->withSuccess('Module Added successfully');
    }

    public function SaveEditModule(Request $request, $id){
      $data = $request->all();
      $module = Module::where([
                                ['status',Config::get('constants.success')],
                                ['module_id',$id]
                              ])->first();
      // validation rules
      $rules = [
        'controller'=>'required',
        'cat_name'=>'required',
        'subcat_name'=>'required',
        'actions'=>'required',
        'sort_order'=>'required'
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
      $module->fill($data)->save();
      return redirect(action('UserController@DeveloperMode',['var'=>$data['pwd']]))->withSuccess('Module Updated successfully');
    }

    public function AddModule($var){
      $module = new Module;
      return view('user.add_module',['module'=>$module,'pwd'=>$var]);
    }

    // roles by ajax
    public function RolePermission(){
      $html = "";
      $expand = 0;
      $role_id  = Functions::test_input(Input::get('role_id'));
      $role_model = new Role;
      if($role_id>0){
        $status =  $role_model->getPermissionValue($role_id);
        $i = 0;
        if(isset($status['status']) && $status['status']=="All Pages"){
          $data['html'] = "All Pages";
        }elseif(isset($status['status']) && $status['status']=="Not Assigned"){
          $data['html'] = "<img src= url('/img/grey-plus.png') class='assignrole cursor' data-toggle='modal' data-target='#assignrole' data-roleid=".$id.">";
        }else{
          foreach ($role_model->getPermissionValue($role_id) as $key=>$val) {
            $data['role'][] = array($key,$val);
        }
      }
      return ($data);
    }
  }
}
