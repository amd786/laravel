@extends('layouts.app')

@section('content')
@section('title', 'Invoices')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 fs18">
        <div class="col-md-6 no_padding">
          <h4>Outstanding: ${{ round(App\Library\Functions::getOutstandingAmount(),2) }}</h4>
        </div>
        <div class="col-md-6 no_padding">
          <h4>Past Due: ${{ round(App\Library\Functions::getPastDueAmount(),2)}}</h4>
        </div>
      </div>
    </div>
    <div class="row padT20">
      <div class="col-md-12">
        <div class="col-sm-1 no_padding">
          <a class="colorBlack fs18 date_filters top_filters">
            <img src="{{ url('/img/filter.jpg') }}" alt="">
            Filter
          </a>
        </div>
        <div class="col-sm-1 no_padding">
          <!--<a class="colorBlack group_orders fs18 top_filters">
            <img src="{{ url('/img/group.jpg') }}" alt="">
            Group
          </a>-->
        </div>
        <div class="col-sm-10">
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
        <hr class="group_by">
      </div>
    </div>
    <div class="row hide" id="date_filter_options">
      <div class="col-md-12">
        <span class="fs18 pull-left"><strong>Quick Date: </strong></span>
        <ul class="list-unstyled list-inline nav nav-pills marginB15">
          <li class="accounting-tabs">
            <a class="fs18 quick_dates padL0 marginR20 no_padding colorBlack active" id="this_week" data-toggle="tab">This Week</a>
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
      </div>
      <div class="col-md-12">
        <span class="fs18 pull-left"><strong>Date Range:&nbsp;&nbsp;</strong></span>
        <div class="col-md-3 no_padding">
          {{ Form::text('select_date_range',null,['class'=>'form-control select_date_range','id'=>'select_date_range','placeholder'=>'Select Date Range']) }}
          {{ Form::hidden('start_date', '', array('id' => 'start_date')) }}
          {{ Form::hidden('end_date', '', array('id' => 'end_date')) }}
          {{ Form::hidden('quick_date', 'this_week', array('id' => 'quick_date')) }}
        </div>
        <div class="col-md-9">
        </div>
      </div>
      <div class="col-md-12 padT10">
        <span class="fs18 pull-left"><strong>Account:&nbsp;&nbsp;</strong></span>
        <div class="col-md-3 no_padding">
          <select name="fk_account_id" class="form-control" id="fk_account_id">
            <option value="0">Select Account...</option>
            @if(count($invoice_model->getAccountsInDropdown())>0)
            @foreach($invoice_model->getAccountsInDropdown() as $account)
            <option value="{{$account->account_id}}">{{$account->account_name}}</option>
            @endforeach
            @endif
            @if(count($invoice_model->getAccountSuppliersInDropdown())>0)
            @foreach($invoice_model->getAccountSuppliersInDropdown() as $account)
            <option value="{{$account->account_id}}">{{$account->account_name}}</option>
            @endforeach
            @endif
            @if(count($invoice_model->getAccountCompaniesInDropdown())>0)
            @foreach($invoice_model->getAccountCompaniesInDropdown() as $account)
            <option value="{{$account->account_id}}">{{$account->account_name}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-9">
        </div>
      </div>
      <div class="col-md-12">
        <hr class="group_by">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <span class="fs18"><strong>Actions</strong>&nbsp;</span>
        <a href="{{ action('AccountingController@CreateInvoice') }}" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10">New Invoice
        </a>
      </div>
    </div>
    <div class="invoice_table_holder padT30" id="invoice_table_holder">

    </div>
  </div>
</div>
<script>
function getInvoicesByDateFilter(){
  var fk_account_id = $('#fk_account_id').val();
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var date_filter = $('#quick_date').val();
  var url = '<?= action('AccountingController@InvoicesByDate')?>';
  $.ajax({
    url:url,
    data:{fk_account_id:fk_account_id,start_date:start_date,end_date:end_date,date_filter:date_filter},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#invoice_table_holder').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
}
$(function() {
  getInvoicesByDateFilter();
});

// date filter
$('.quick_dates').click(function(){
  $('.quick_dates').removeClass('active');
  $(this).addClass('active');
  $('#quick_date').val($(this).attr('id'));
  getInvoicesByDateFilter();
});

// filter by date range
$('input[name="select_date_range"]').on('apply.daterangepicker', function(ev, picker) {
  var start_date  = picker.startDate.format('YYYY-MM-DD');
  var end_date = picker.endDate.format('YYYY-MM-DD');
  $('#start_date').val(start_date);
  $('#end_date').val(end_date);
  getInvoicesByDateFilter();
});

// filter by account
$('#fk_account_id').on('change',function(){
  getInvoicesByDateFilter();
});

// filter group
/*$('.group_orders').click(function(){
  $('.top_filters').removeClass('text-bold');
  $(this).addClass('text-bold');
  $('#date_filter_options').addClass('hide');
  $('#group_by_options').toggleClass('hide');
  if(!$('#group_by_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','52%');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $(this).removeClass('text-bold');
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});*/
$('.date_filters').click(function(){
  $('.top_filters').removeClass('text-bold');
  $(this).addClass('text-bold');
  $('#group_by_options').addClass('hide');
  $('#date_filter_options').toggleClass('hide');
  if(!$('#date_filter_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','30px');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $(this).removeClass('text-bold');
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});
</script>
@endsection
