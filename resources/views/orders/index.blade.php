@extends('layouts.app')

@section('content')
@section('title', 'Client List')
@include('orders.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row text-center">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-offset-8 col-md-4 text-right">
            <div class="col-md-3 no_padding">
              <img src="{{ url('/img/search-icon.png') }}" alt="">
            </div>
            <div class="col-md-9 no_padding">
              {!! Form::text('filter',null,['class'=>'form-control', 'id'=>'search_client','placeholder'=>'Search by Client Name...']) !!}
            </div>
          </div>
        </div>
        <div class="row padT30">
          <div class="col-md-12 client_list_holder" id="client_list_holder">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function getClientList(){
  var search_text = $('#search_client').val();
  var url = '<?= action('OrdersController@ClientList')?>';
  $.ajax({
    url:url,
    data:{search_text:search_text},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#client_list_holder').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
}
$(function() {
  getClientList();
});
$("#search_client").change(function(e) {
    getClientList();
});
</script>
@endsection
