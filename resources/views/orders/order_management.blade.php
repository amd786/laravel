@extends('layouts.app')

@section('content')
@section('title', 'Order Management')
@include('orders.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row text-center">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12 text-left">
            <div class="col-md-6 no_padding">
              <span class="fs20">Filters  <a class="show_filter"><i class="fa fa-caret-down" aria-hidden="true"></i></a></span>
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-4 text-right">
              <div class="row">
                <div class="col-md-12 no_padding">
                  <div class="col-md-2 no_padding text-right">
                      <img src="{{ url('/img/search-icon.png') }}" alt="">
                  </div>
                  <div class="col-md-10 pull-right text-right no_padding">
                    {!! Form::text('filter',null,['class'=>'form-control', 'id'=>'search_orders','placeholder'=>'Search by Order No.']) !!}
                    {{ Form::hidden('quick_date', '', array('id' => 'quick_date')) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="filter_holder">
          <div class="row">
            <div class="col-md-12 text-left">
              <ul class="list-unstyled list-inline">
                <li>
                  <span class="fs18">Quick Date:</span>
                </li>
                <li>
                  <a class="fs18 quick_dates active" id="this_week">This Week</a>
                </li>
                <li>
                  <span>/</span>
                </li>
                <li>
                  <a class="fs18 quick_dates" id="this_month">This Month</a>
                </li>
                <li>
                  <span>/</span>
                </li>
                <li>
                  <a class="fs18 quick_dates" id="this_year">This Year</a>
                </li>
                <li>
                  <span>/</span>
                </li>
                <li>
                  <a class="fs18 quick_dates" id="last_week">Last Week</a>
                </li>
                <li>
                  <span>/</span>
                </li>
                <li>
                  <a class="fs18 quick_dates" id="last_month">Last Month</a>
                </li>
                <li>
                  <span>/</span>
                </li>
                <li>
                  <a class="fs18 quick_dates" id="last_year">Last Year</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-12 padL0 padT10">
                <div class="col-md-2 no_padding  text-left">
                  <span class="fs18">Date Range: </span>
                </div>
                <div class="col-md-3 no_padding">
                  {{ Form::text('select_date_range',null,['class'=>'form-control select_date_range','placeholder'=>'Select Date Range']) }}
                  {{ Form::hidden('start_date', '', array('id' => 'start_date')) }}
                  {{ Form::hidden('end_date', '', array('id' => 'end_date')) }}
                  {{ Form::hidden('quick_date', 'this_week', array('id' => 'quick_date')) }}
                </div>
                <div class="col-md-7">
                </div>
              </div>
              <div class="col-md-12 no_padding padT10">
                <div class="col-md-2 no_padding  text-left">
                  <span class="fs18">Order Status: </span>
                </div>
                <div class="col-md-2 no_padding">
                  <select class="form-control order_status" name="order_status">
                    <option value="">Select Status...</option>
                    <option value="1">Completed</option>
                    <option value="2">In Progress</option>
                  </select>
                </div>
                <div class="col-md-7">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row padT30">
          <div class="col-md-12 order_management_holder" id="order_management_holder">
          </div>
        </div>
     </div>
    </div>
  </div>
</div>
<script>
function getData(url,order_status,start_date,end_date,quick_date,search_text,fk_user_id){
  $.ajax({
    url:url,
    data:{order_status:order_status,start_date:start_date,end_date:end_date,quick_date:quick_date,search_text:search_text,fk_user_id:fk_user_id},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#order_management_holder').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
}
function getClientOrders(){
  var order_status = $('.order_status').val();
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var quick_date = $('#quick_date').val();
  var search_text = $('#search_orders').val();
  var fk_user_id = '<?= $fk_user_id ?>';
  var url = '<?= action('OrdersController@OrdersTable')?>';
  getData(url,order_status,start_date,end_date,quick_date,search_text,fk_user_id);
}
$(function() {
  getClientOrders();
});
$('.quick_dates').click(function(){
  $('.quick_dates').removeClass('active');
  $(this).addClass('active');
  $('#quick_date').val($(this).attr('id'));
  getClientOrders();
});
$('.order_status').on('change',function(){
  getClientOrders();
});
$('input[name="select_date_range"]').on('apply.daterangepicker', function(ev, picker) {
  var start_date  = picker.startDate.format('YYYY-MM-DD');
  var end_date = picker.endDate.format('YYYY-MM-DD');
  $('#start_date').val(start_date);
  $('#end_date').val(end_date);
  getClientOrders();
});
$("#search_orders").change(function(e) {
  getClientOrders();
});
</script>
@endsection
