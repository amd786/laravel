@extends('layouts.app')

@section('content')
@section('title', 'Order Progress')
@include('user.sub_head')
<div class="panel panel-default" style="border: 0px solid #000">
  <div class="panel-body">
    <div class="col-md-12">
      <div class="col-md-12">
        <div class="col-md-4 padT8 no_padding">
          <div class="col-md-12 no_padding">
            <a class="colorBlack fs18 date_filters">
              <img src="{{ url('/img/filter.jpg') }}" alt="">
              Filter
            </a>
          </div>
        </div>
        <div class="col-md-8 text-right no_padding">
          <div class="col-md-12 no_padding">
            <div class="col-md-7">
            </div>
            <div class="col-md-1 pull-left no_padding">
                <img src="{{ url('/img/search-icon.png') }}" alt="">
            </div>
            <div class="col-md-4 col-sm-7 no_padding">
              {!! Form::text('filter',null,['class'=>'form-control', 'id'=>'search_orders','placeholder'=>'Search by Order No.']) !!}
              {{ Form::hidden('quick_date', '', array('id' => 'quick_date')) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div id="group_by_holder">
          <span id="inside_holder" class="hide"></span>
          <hr class="marginT10 marginB15 group_by">
        </div>
      </div>
      <!--<div class="col-md-12 hide" id="group_by_options">
        <ul class="nav nav-pills marginB15">
          <li class="accounting-tabs active" id="list_by_status"><a href="#" class="fs18 padL0 marginR20 no_padding colorBlack" data-toggle="tab">Status</a></li>
          <li class="accounting-tabs" id="list_by_date"><a href="#" class="fs18 custom-tab padL0 no_padding colorBlack" data-toggle="tab">Date</a></li>
        </ul>
        <hr class="no_margin group_by">
      </div>-->
      <div class="col-md-12 hide" id="date_filter_options">
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
      <div class="col-md-12 padT50">
        <div class="orders_table_holder" id="orders_table_holder">
  			</div>
     </div>
    </div>
  </div>
</div>
@include('user.sub_foot')
<script>
// filter group
/*$('.group_orders').click(function(){
  $('#date_filter_options').addClass('hide');
  $('#group_by_options').toggleClass('hide');
  if(!$('#group_by_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','52%');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});*/
$('.date_filters').click(function(){
  $('#group_by_options').addClass('hide');
  $('#date_filter_options').toggleClass('hide');
  if(!$('#date_filter_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','3%');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});

// list by date
/*$('#list_by_date').click(function(){
  var search_text = $('#search_orders').val();
  var date_filter = $('#quick_date').val();
  getClientOrders(search_text,date_filter);
});

// list by status
$('#list_by_status').click(function(){
  $('#status_orders_holder').removeClass('hide');
  $('#date_orders_holder').html('');
  var search_text = $('#search_orders').val();
  var date_filter = $('#quick_date').val();
  getClientOrders(search_text,date_filter);
});*/

// serach table
$("#search_orders").change(function(e) {
    var search_text = $('#search_orders').val();
    var date_filter = $('#quick_date').val();
    getClientOrders(search_text,date_filter);
});

// filters
$('.quick_dates').click(function(){
  $('#quick_date').val($(this).attr('id'));
  var search_text = $('#search_orders').val();
  var date_filter = $('#quick_date').val();
  getClientOrders(search_text,date_filter);
  /*if($('#list_by_status').hasClass('active')){
    getClientOrders(search_text,date_filter);
  }
  if($('#list_by_date').hasClass('active')){
    getClientOrders(search_text,date_filter);
  }*/
});
function getClientOrders(search_text,date_filter){
  var url = '<?= action('ClientController@ClientOrdersTable')?>'+'?search='+search_text+'&date_filter='+date_filter;
  $.ajax({
    url:url,
    method:"GET",
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#orders_table_holder').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
}
$(function() {
  var search_text="";
  var date_filter = $('#quick_date').val();
  getClientOrders(search_text,date_filter);
});

</script>
@stop
