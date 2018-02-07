<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App\Models\Module;
use App\Library\Functions;
use Session;

class Check
{
  
    public function __construct()
    {
        $this->module_id = null;
        $this->r_module_id = null;
    }
    
    public function handle($request, Closure $next)
    {
        /* if the action belongs to ajax controller then also write it in same module action in db if it needs to check permission.
         if it doesnt need to check permission then write in except middleware. if you don't want any action to perform check 
         validation then do not call middleware for that specific action.  */
        $route = Route::currentRouteAction();
        $controller_action = class_basename($route);
        $controller_action = explode("@",$controller_action);
        $curr_controller = $controller_action[0];
        $curr_action = $controller_action[1];
        
        if($curr_controller == 'AjaxController')
          $modules = Module::where('status',1)->get();
        else 
          $modules = Module::where('controller',$curr_controller)->get();
          
        foreach($modules as $module){
          // first check in all actions
          $actions = explode(",",$module->actions);
          foreach($actions as $action){
            if($curr_action == $action){
              $this->module_id = $module->module_id;
            }
          }
          // if module no action is present then check in read actions 
          if(!Functions::check($this->module_id)){
            $r_actions = explode(",",$module->read_permission_actions);
            foreach($r_actions as $r_action){
              if($curr_action == $r_action){
                 $this->r_module_id = $module->module_id;
              }
            }
          }
        }

        if(!empty($this->module_id) && Functions::check($this->module_id)){
          return $next($request);
        }else if(!empty($this->r_module_id) && Functions::check($this->r_module_id,'R')){
          return $next($request);
        }else{
          Session::flash('error','You do not have permission to perform this action.');
          return redirect()->back();
        }
      
    }
}
