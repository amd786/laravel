@extends('layouts.app')

@section('content')
@section('title', 'Transactions')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 fs18 marginB40">
        <div class="bgGrey clearfix">
          <div class="col-md-6">
            <h4>Estimated Income for This Month (Sales): <strong>${{ round(App\Library\Functions::getEstimatedIncome(),2) }}</strong></h4>
          </div>
          <div class="col-md-6 text-right">
            <h4>Estimated Expenses for This Month (Suppliers): <strong>(${{ round(App\Library\Functions::getEstimatedExpense(),2) }})</strong></h4>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-1 pull-left no_padding widthAuto">
            <img src="{{ url('/img/search-icon.png') }}" alt="">
        </div>
        <div class="col-md-4 col-sm-7 padR0">
            {!! Form::text('filter',null,['class'=>'form-control', 'id'=>'search_transactions','placeholder'=>'Seach by Type/Category']) !!}
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
              <!--<a class="colorBlack group_orders fs18">
                <img src="{{ url('/img/group.jpg') }}" alt="">
                Group By
              </a>-->
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
            @if(count($invoices->getAccountsInDropdown())>0)
            @foreach($invoices->getAccountsInDropdown() as $account)
            <option value="{{$account->account_id}}">{{$account->account_name}}</option>
            @endforeach
            @endif
            @if(count($invoices->getAccountSuppliersInDropdown())>0)
            @foreach($invoices->getAccountSuppliersInDropdown() as $account)
            <option value="{{$account->account_id}}">{{$account->account_name}}</option>
            @endforeach
            @endif
            @if(count($invoices->getAccountCompaniesInDropdown())>0)
            @foreach($invoices->getAccountCompaniesInDropdown() as $account)
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
      <div class="col-md-12 clearfix">
        <div class="col-sm-1">
          <span class="fs18"><strong>Actions</strong>&nbsp;</span>
        </div>
        <div class="col-sm-2 no_padding">
          <a class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" data-toggle="modal" data-target="#new_transaction">New Transaction</a>
        </div>
        <div class="col-sm-3 width22">
          <a class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" href="{{ action('AccountingController@AccountsPayable') }}">See Accounts Payable</a>
        </div>
        <div class="col-sm-3">
          <a class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" href="{{ action('AccountingController@AccountsReceivable') }}">See Accounts Received</a>
        </div>
        <div class="col-sm-3">
        </div>
      </div>
    </div>
    <div class="transaction_table_holder padT30" id="transaction_table_holder">

    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="new_transaction" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['url'=>action('AccountingController@SaveTransaction')]) }}
      <div class="modal-header no-border">
        <h2 class="modal-title text-center">New Transaction</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-3 text-left no_padding">
                  {{ Form::label('transaction_date','Date',['class'=>'fs18']) }}
                </div>
                <div class="col-sm-6 no_padding">
                  <div class="input-group">
                    {{ Form::text('transaction_date',null,['class'=>'form-control dateformat_datepicker border1Black','data-date-format'=>'yyyy-mm-dd']) }}
                    <div class="input-group-addon border1Black noLBorder">
                        <span class="icon-calendar"></span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3">
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-3 text-left no_padding">
                  {{ Form::label('amount','Amount',['class'=>'fs18']) }}
                </div>
                <div class="col-sm-6 no_padding">
                  {{ Form::number('amount', null, ['class' => 'form-control border1Black','min'=>'0']) }}
                </div>
                <div class="col-sm-3">
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-3 text-left no_padding">
                  {{ Form::label('fk_account_category_id','Type/Category',['class'=>'fs18']) }}
                </div>
                <div class="col-sm-6 no_padding">
                  <select name="fk_account_category_id" class="form-control border1Black" data-action="{{action('AccountingController@AccountsByCategory')}}" id="fk_account_category_id">
                    <option value=''>Select option...</option>
                    @if(count($invoices->getAccountsCategoriesDropdown())>0)
                    @foreach($invoices->getAccountsCategoriesDropdown() as $category)
                    <option value='{{$category->id}}'>{{$category->category}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
                <div class="col-sm-3">
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-3 text-left no_padding">
                  {{ Form::label('fk_account_id','Account',['class'=>'fs18']) }}
                </div>
                <div class="col-sm-6 no_padding">
                  {{ Form::select('fk_account_id', [''=>'Select option...'], Input::old('fk_account_id'), ['class'=>'form-control border1Black','id'=>'account_dropdown']) }}
                  <span class="icon-spinner hide"><i class="fa fa-spinner"></i></span>
                </div>
                <div class="col-sm-3">
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer no-border marginT20">
        {!! Form::submit('Add Transaction',array('class'=>'btn-default small_btn border1Black widthAuto round_btn padT10 padB10','id'=>'add_transaction')) !!}
        <button type="button" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" data-dismiss="modal">Cancel</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
<script>
function getTransactionsByDateFilter(){
  var search = $('#search_transactions').val();
  var fk_account_id = $('#fk_account_id').val();
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var date_filter = $('#quick_date').val();
  var url = '<?= action('AccountingController@TransactionsByDate')?>';
  $.ajax({
    url:url,
    data:{search:search,fk_account_id:fk_account_id,start_date:start_date,end_date:end_date,date_filter:date_filter},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#transaction_table_holder').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
}
$(function() {
  getTransactionsByDateFilter();
});
// date filter
$('.quick_dates').click(function(){
  $('.quick_dates').removeClass('active');
  $(this).addClass('active');
  $('#quick_date').val($(this).attr('id'));
  getTransactionsByDateFilter();
});

$('.date_filters').click(function(){
  $('.top_filters').removeClass('text-bold');
  $(this).addClass('text-bold');
  $('#group_by_options').addClass('hide');
  $('#date_filter_options').toggleClass('hide');
  if(!$('#date_filter_options').hasClass('hide')){
    $('#group_by_holder span#inside_holder').css('left','41%');
    $('#group_by_holder span#inside_holder').removeClass('hide');
  }else{
    $(this).removeClass('text-bold');
    $('#group_by_holder span#inside_holder').addClass('hide');
  }
});

// filter by date range
$('input[name="select_date_range"]').on('apply.daterangepicker', function(ev, picker) {
  var start_date  = picker.startDate.format('YYYY-MM-DD');
  var end_date = picker.endDate.format('YYYY-MM-DD');
  $('#start_date').val(start_date);
  $('#end_date').val(end_date);
  getTransactionsByDateFilter();
});

// filter by account
$('#fk_account_id').on('change',function(){
  getTransactionsByDateFilter();
});

// filter by search keyword
$("#search_transactions").change(function(e) {
    getTransactionsByDateFilter();
});

// account options
$('#fk_account_category_id').change(function(){
  var fk_account_category_id = $(this).find("option:selected").val();
  if(fk_account_category_id>0){
    var url = $(this).attr('data-action');
    $.ajax({
      type: "POST",
      dataType: "json",
      url : url,
      data: {'fk_account_category_id':fk_account_category_id},
      beforeSend: function() {
        $(".icon-spinner").removeClass("hide");
      },
      success:function(data){
        console.log(data.accounts.length);
        if(data.status === 'success' ){
          $(".icon-spinner").addClass("hide");
          $("#account_dropdown option.item").remove();
          for (var i = 0; i < data.accounts.length; i++) {
            $('#account_dropdown').append("<option class='item' value='"+data.accounts[i].id+"'>"+data.accounts[i].account_name+"</option>")
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $(".icon-spinner").addClass("hide");
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  }
});
</script>
@endSection
