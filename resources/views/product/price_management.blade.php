@extends('layouts.app')

@section('content')
@section('title', 'Price Management')
<div class="portlet-body">
  @include('product.sub_head')
  <div class="row" style="margin:100px 0px 0px 50px;">
    <div class="col-md-6 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@SetProductPrice') }}" class="user-panel-click">
          <img src="{{ url('/img/set-product.png') }}" alt="">
          <span class="user_title">Set Product Price</span>
        </a>
      </div>
    </div>
    <div class="col-md-6 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@ProductClassTolerance') }}" class="user-panel-click">
          <img src="{{ url('/img/tolerance-price.png') }}" alt="">
          <span class="user_title">Product Class Tolerance Pricing</span>
        </a>
      </div>
    </div>
    <!--<div class="col-md-4 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@ProductViewAll') }}" class="user-panel-click">
          <img src="{{ url('/img/view-all2.png') }}" alt="">
          <span class="user_title">View All</span>
        </a>
      </div>
    </div>-->
  </div>
</div>
@endsection
