@extends('layouts.welcome')

@section('content')
<div class="content widthAuto">
  <div class="col-md-12 welcome_text text-center">
    <h1 class="fs60"><strong>Welcome</strong></h1>
  </div>
  <div class="clearfix"></div>
  <div class="col-md-12 padT50">
    <div class="col-md-6 text-right padR30">
      <a href="{{ url('/login') }}" class="btn green dark-blue powerseal_login" id="admin_login">The PowerSeal Team</a>
    </div>

    <div class="col-md-6 padL30">
      <a href="{{ url('/login') }}" class="btn green dark-blue powerseal_login" id="client_login">Client Portal</a>
    </div>
  </div>
  <div class="col-md-12 padT50">
    <!-- BEGIN LOGO -->
    <div class="logo text-center">
      <a href="{{ url('') }}">
        <img src="img/logo.png" alt=""/> </a>
      </div>
      <!-- END LOGO -->
  </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
$('.powerseal_login').click(function(e){
  $.cookie("action",$(this).attr('id'));
  window.location = $(this).attr('href');
});
</script>
@endsection
