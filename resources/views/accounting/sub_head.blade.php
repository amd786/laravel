<?php
  $route = Route::currentRouteAction();
  $controller_action = class_basename($route);
  $controller_action = explode("@",$controller_action);
  $curr_controller = $controller_action[0];
  $curr_action = $controller_action[1];
?>
<div class="container">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 padB30">
        <div class="col-md-2 no_padding sub_head"><a href="{{ App\Library\Functions::previous_url($curr_controller,$curr_action) }}"><img src="{{ url('/img/back-button.png')}}"></a></div>
        <div class="col-md-7 page-head-title text-center">@yield('title')</div>
        <div class="col-md-3"></div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
