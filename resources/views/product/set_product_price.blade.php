@extends('layouts.app')

@section('content')
@section('title', 'Set Product Price')
<div class="portlet-body">
  @include('product.sub_head')
  <div class="row" style="margin:100px 0px 0px 50px;">
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@ProductsWithoutApproval') }}" class="user-panel-click">
          <img src="{{ url('/img/without-approval.png') }}" alt="">
          <span class="user_title">Products Pending Price Approval</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@ProductsWithApproval') }}" class="user-panel-click">
          <img src="{{ url('/img/with-approval.png') }}" alt="">
          <span class="user_title">Products With Approved Price</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@PriceViewAll') }}" class="user-panel-click">
          <img src="{{ url('/img/view-all2.png') }}" alt="">
          <span class="user_title">View All</span>
        </a>
      </div>
    </div>																						
  </div>
</div>
@endsection