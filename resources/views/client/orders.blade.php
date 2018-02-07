@extends('layouts.app')

@section('content')
<div class="portlet-body">
  @section('title','Orders')
  @include('client.sub_head')
  <div class="row">
    <div class="col-md-5 col-md-offset-1 ">
      <div class="users-panel">
        <a href="{{ action('ClientController@NewOrder') }}" class="user-panel-click">
          <img src="{{ url('/img/order-fulfilment.png') }}" alt="">
          <span class="user_title">New Order</span>
        </a>
      </div>
    </div>
    <div class="col-md-5">
      <div class="users-panel">
        <a href="{{ action('ClientController@OrderHistory') }}" class="user-panel-click">
          <img src="{{ url('/img/transactions.png') }}" alt="">
          <span class="user_title">Order History</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
