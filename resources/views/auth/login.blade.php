{{--*/
if(isset($_COOKIE["action"])){
  $action = $_COOKIE["action"];
}else{
  header("Location:".url(''));
  die();
}
/*--}}
@extends('layouts.signup')

@section('content')
<div class="content">
  <!-- BEGIN LOGIN FORM -->
  <form class="login-form" action="{{ url('/login') }}" method="post">
    {{ csrf_field() }}
    @if($action=="admin_login")
    <h3 class="form-title font-red-login">Power Seal Login Page</h3>
    @elseif($action=="client_login")
    <h3 class="form-title font-red-login">Client Portal</h3>
    @endif
    <div class="alert alert-danger display-hide">
      <button class="close" data-close="alert"></button>
      <span> Enter any username and password. </span>
    </div>
    <div class="outer-login-modal">
      <h3 class="resource-title font-red-login">Welcome</h3>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <p class="label-title">Email:<label class="control-label visible-ie8 visible-ie9">Email</label></p>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" placeholder="Enter Email" name="email" value="{{ old('email') }}" />
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
        <p class="label-title">Password:<label class="control-label visible-ie8 visible-ie9">Username</label></p>
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="" name="password" />
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-actions">
        <button type="submit" class="btn green dark-blue">Log In</button>
      </div>
    </div>
  </form>
  <!-- END LOGIN FORM -->
</div>
@endsection
