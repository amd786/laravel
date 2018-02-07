@extends('layouts.app')

@section('content')
@section('title', 'Purchase Order Approvals')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-2 pull-left no_padding widthAuto">
            <img src="{{ url('/img/search-icon.png') }}" alt="">
        </div>
        <div class="col-md-4 col-sm-7 no_padding">
            {!! Form::text('filter',null,['class'=>'form-control', 'id'=>'search_orders','placeholder'=>'Search by Purchase Order']) !!}
            {{ Form::hidden('quick_date', '', array('id' => 'quick_date')) }}
        </div>
        <div class="col-md-3 padT8">
          <div class="col-md-12 no_padding">
            <div class="col-md-4 no_padding">
              <a class="colorBlack fs18 date_filters">
                <img src="{{ url('/img/filter.jpg') }}" alt="">
                Filter
              </a>
            </div>
            <div class="col-md-6 no_padding">
              <a class="colorBlack group_orders fs18">
                <img src="{{ url('/img/group.jpg') }}" alt="">
                Group By
              </a>
            </div>
            <div class="col-md-2">
            </div>
          </div>
        </div>
        <div class="col-md-3">
        </div>
      </div>
    </div>
    <div id="group_by_holder">
      <span id="inside_holder" class="hide"></span>
      <hr class="marginT10 marginB15 group_by">
    </div>
    <div class="row hide" id="group_by_options">
      <div class="col-md-12">
        <ul class="nav nav-pills marginB15">
          <li class="accounting-tabs active" id="list_by_status"><a href="#" class="fs18 padL0 marginR20 no_padding colorBlack" data-toggle="tab">Status</a></li>
          <li class="accounting-tabs" id="list_by_date"><a href="#" class="fs18 custom-tab padL0 no_padding colorBlack" data-toggle="tab">Date</a></li>
        </ul>
        <hr class="no_margin group_by">
      </div>
    </div>
    <div class="row hide" id="date_filter_options">
      <div class="col-md-12">
        <ul class="list-unstyled list-inline nav nav-pills marginB15">
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack" id="this_week" data-toggle="tab">This Week</a>
          </li>
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack" id="this_month" data-toggle="tab">This Month</a>
          </li>
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack" id="this_year" data-toggle="tab">This Year</a>
          </li>
          <li class="accounting-tabs">
            <span class="fs18 padL0 marginR20 no_padding colorBlack">&#47;</span>
          </li>
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack" id="last_week" data-toggle="tab">Last Week</a>
          </li>
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack" id="last_month" data-toggle="tab">Last Month</a>
          </li>
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack" id="last_year" data-toggle="tab">Last Year</a>
          </li>
        </ul>
        <hr class="no_margin group_by">
      </div>
    </div>
    <div class="row text-center padT30" id="status_orders_holder">
      <div class="col-md-12">
        <h3 class="text-left">Pending</h3>
  			<div class="pending_table_holder" id="pending_table_holder">
  			</div>
  			<div class="padT50"></div>
  			<h3 class="text-left">Approved</h3>
  			<div class="approved_table_holder" id="approved_table_holder">
  			</div>
        <div class="padT50"></div>
  			<h3 class="text-left">Rejected</h3>
  			<div class="rejected_table_holder" id="rejected_table_holder">
  			</div>
     </div>
    </div>
    <div class="row text-center padT30 date_orders_holder" id="date_orders_holder">
    </div>
  </div>
</div>
<div class="modal fade" id="reject_order" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content col-md-12 no_padding">
      <div class="modal-body main_div clearfix">
        <div class="col-md-12 fs20">
          <div class="col-md-4 text-center fs80 no_padding">
            <img src="{{ url('/img/cross-big.png') }}" alt="">
          </div>
          <div class="col-md-8 modal_approval">
            <div class="row marginT20">
              <div class="col-md-12">
                {{Form::open(array('url'=>action('AccountingController@RejectPurchaseOrder'),'id'=>'reject_order_form'))}}
                {{ Form::hidden('order_id', null, array('id' => 'order_id')) }}
                <h2 class="modal-title">Reject Purchase Order?</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
  		<div class="modal-footer clearfix no-border">
        <div class="row">
    			<div class="col-md-12">
            <div class="col-md-6">
            </div>
            <div class="col-md-3">
              {!! Form::submit('Ok',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn marginB5')) !!}
            </div>
            <div class="col-md-3">
              {!! Form::button('Cancel',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn','data-dismiss'=>'modal')) !!}
              {!!Form::close()!!}
            </div>
    			</div>
        </div>
  		</div>
    </div>
  </div>
</div>
<div class="modal fade" id="approve_order" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content col-md-12">
      <div class="modal-body main_div">
        <div class="col-md-12 fs20">
          <div class="col-md-4 text-center fs80 no_padding">
            <img src="{{ url('/img/tick-big.png') }}" alt="">
          </div>
          <div class="col-md-8 modal_checkout">
            <div class="row marginT20">
              <div class="col-md-12">
                {{Form::open(array('url'=>action('AccountingController@ApprovePurchaseOrder'),'id'=>'approve_order_form'))}}
                {{ Form::hidden('order_id', null, array('id' => 'order_id')) }}
                <h2 class="modal-title">Approve Purchase Order?</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer clearfix no-border">
        <div class="row">
           <div class="col-md-12">
              <div class="col-md-6">
              </div>
              <div class="col-md-3">
                {!! Form::submit('Ok',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn marginB5')) !!}
              </div>
              <div class="col-md-3">
                {!! Form::button('Cancel',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn','data-dismiss'=>'modal')) !!}
                {!!Form::close()!!}
              </div>
           </div>
        </div>
     </div>
    </div>
  </div>
</div>
<script>
$(function() {
  var search_text="";
  var date_filter = $('#quick_date').val();
  getPendingOrders(search_text,date_filter);
  getApprovedOrders(search_text,date_filter);
  getRejectedOrders(search_text,date_filter);
});

// get pending orders
function getPendingOrders(search_text,date_filter){
  var url = '<?= action('AccountingController@PendingPurchaseOrders')?>'+'?search='+search_text+'&date_filter='+date_filter;
  $.ajax({
    url:url,
    method:'GET',
    beforeSend: function() {
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      if(result){
        $('.pending_table_holder').html(result);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}

// get approved orders
function getApprovedOrders(search_text,date_filter){
  var url = '<?= action('AccountingController@ApprovedPurchaseOrders')?>'+'?search='+search_text+'&date_filter='+date_filter;
  $.ajax({
    url:url,
    method:'GET',
    success:function(result){
      if(result){
        $('.approved_table_holder').html(result);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}

// get rejected orders
function getRejectedOrders(search_text,date_filter){
  var url = '<?= action('AccountingController@RejectedPurchaseOrders')?>'+'?search='+search_text+'&date_filter='+date_filter;
  $.ajax({
    url:url,
    method:'GET',
    success:function(result){
      if(result){
        $('.rejected_table_holder').html(result);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}

// get orders by date
function getOrdersByDate(search_text,date_filter){
  var url = '<?= action('AccountingController@OrdersByDate')?>'+'?search='+search_text+'&date_filter='+date_filter;
  $.ajax({
    url:url,
    method:'GET',
    beforeSend: function() {
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      if(result){
        $('#status_orders_holder').addClass('hide');
        $('#date_orders_holder').html(result);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}

// modals
$(document).on('click', '.reject_order', function() {
  var order_id = $(this).attr('data-id');
  $('#reject_order_form').find('input[id="order_id"]').val(order_id);
  $('#reject_order').modal('show');
});
$(document).on('click', '.approve_order', function() {
  var order_id = $(this).attr('data-id');
  $('#approve_order_form').find('input[id="order_id"]').val(order_id);
  $('#approve_order').modal('show');
});

// filter group
$('.group_orders').click(function(){
  $('#date_filter_options').addClass('hide');
  $('#group_by_options').toggleClass('hide');
  if(!$('#group_by_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','49%');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});
$('.date_filters').click(function(){
  $('#group_by_options').addClass('hide');
  $('#date_filter_options').toggleClass('hide');
  if(!$('#date_filter_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','40%');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});

// list by date
$('#list_by_date').click(function(){
  var search_text = $('#search_orders').val();
  var date_filter = $('#quick_date').val();
  getOrdersByDate(search_text,date_filter);

});

// list by status
$('#list_by_status').click(function(){
  $('#status_orders_holder').removeClass('hide');
  $('#date_orders_holder').html('');
  var search_text = $('#search_orders').val();
  var date_filter = $('#quick_date').val();
  getPendingOrders(search_text,date_filter);
  getApprovedOrders(search_text,date_filter);
  getRejectedOrders(search_text,date_filter);
});

// serach table
$("#search_orders").change(function(e) {
    var search_text = $('#search_orders').val();
    var date_filter = $('#quick_date').val();
    if($('#list_by_status').hasClass('active')){
      getPendingOrders(search_text,date_filter);
      getApprovedOrders(search_text,date_filter);
      getRejectedOrders(search_text,date_filter);
    }
    if($('#list_by_date').hasClass('active')){
      getOrdersByDate(search_text,date_filter);
    }
});

// filters
$('.quick_dates').click(function(){
  $('#quick_date').val($(this).attr('id'));
  var search_text = $('#search_orders').val();
  var date_filter = $('#quick_date').val();
  if($('#list_by_status').hasClass('active')){
    getPendingOrders(search_text,date_filter);
    getApprovedOrders(search_text,date_filter);
    getRejectedOrders(search_text,date_filter);
  }
  if($('#list_by_date').hasClass('active')){
    getOrdersByDate(search_text,date_filter);
  }
});
</script>
@endsection
