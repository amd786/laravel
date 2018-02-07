@extends('layouts.app')

@section('content')
@section('title', 'Users')
@include('user.sub_head')
<div class="panel panel-default" style="border: 0px solid #000">
  <div class="panel-body">
    <div class="col-md-12">
      <div class="col-md-12">
        <!--<div class="col-md-1 no_padding">
          <span class="fs18"><img src="{{ url('/img/dots.png') }}" class="padR5">Actions&nbsp;</span>
        </div>-->
        <div class="col-md-12 no_padding">
          <a href="{{ action('ClientController@AddUser') }}" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10"><img src="{{ url('/img/invite-client.png') }}" class="padR5 padB5">Add New User
          </a>
        </div>
      </div>
      <div class="col-md-12">
        <hr class="group_by"></hr>
      </div>
      <div class="col-md-12 padT10">
        <div class="client_users_table" id="client_users_table">
        </div>
      </div>
    </div>
  </div>
</div>
@include('user.sub_foot')
<script>
$(function(){
  var url = '<?= action('ClientController@UsersTable') ?>';
  $.ajax({
    url:url,
    method:"GET",
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#client_users_table').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
});
</script>
@stop
