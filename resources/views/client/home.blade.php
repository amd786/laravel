@extends('layouts.app')

@section('content')
<div class="portlet-body">
  <div class="row">
    <div class="col-md-12 text-center padB30">
      <div class="col-md-3 padT10"></div>
      <div class="col-md-6 page-head-title">Welcome, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ClientController@Orders') }}" class="user-panel-click">
          <img src="{{ url('/img/order-fulfilment.png') }}" alt="">
          <span class="user_title">Orders</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ClientController@OrderProgress') }}" class="user-panel-click">
          <img src="{{ url('/img/overview-reports.png') }}" alt="">
          <span class="user_title">Order Progress</span>
        </a>
      </div>
    </div>
    <!-- <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="" class="user-panel-click">
          <img src="{{ url('/img/payment-schedule.png') }}" alt="">
          <span class="user_title">Account Balance</span>
        </a>
      </div>
    </div> -->
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ClientController@Users') }}" class="user-panel-click">
          <img src="{{ url('/img/all-users.png') }}" alt="">
          <span class="user_title">Users</span>
        </a>
      </div>
    </div>
  </div>
  <!-- <div class="row user-panel-2">
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ClientController@OrderProgress') }}" class="user-panel-click">
          <img src="{{ url('/img/overview-reports.png') }}" alt="">
          <span class="user_title">Order Progress</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">

    </div>
    <div class="col-md-4 col-sm-12">

    </div>
  </div> -->
</div>
@endsection
