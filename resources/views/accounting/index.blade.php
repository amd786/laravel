@extends('layouts.app')

@section('content')
<div class="portlet-body">
  <div class="row">
    <div class="col-md-12 text-center padB30">
      <div class="col-md-3 padT10"></div>
      <div class="col-md-6 page-head-title">Accounting</div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('AccountingController@OverviewAndReports')}}" class="user-panel-click">
          <img src="{{ url('/img/overview-reports.png') }}" alt="">
          <span class="user_title">Overview & Reports</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('AccountingController@PurchaseOrders') }}" class="user-panel-click">
          <img src="{{ url('/img/order-fulfilment.png') }}" alt="">
          <span class="user_title">Purchase Orders</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('AccountingController@PaymentSchedule') }}" class="user-panel-click">
          <img src="{{ url('/img/payment-schedule.png') }}" alt="">
          <span class="user_title">Payment Schedule</span>
        </a>
      </div>
    </div>
  </div>
  <div class="row user-panel-2">
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('AccountingController@Invoices') }}" class="user-panel-click">
          <img src="{{ url('/img/invoices.png') }}" alt="">
          <span class="user_title">Invoices</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('AccountingController@Transactions') }}" class="user-panel-click">
          <img src="{{ url('/img/transactions.png') }}" alt="">
          <span class="user_title">Transactions</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('AccountingController@Accounts') }}" class="user-panel-click">
          <img src="{{ url('/img/accounts.png') }}" alt="">
          <span class="user_title">Accounts</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
