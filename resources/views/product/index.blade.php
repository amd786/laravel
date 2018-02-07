@extends('layouts.app')

@section('content')
<div class="portlet-body">
  <div class="row">
    <div class="col-md-12 text-center padB30">
      <div class="col-md-3 padT10"></div>
      <div class="col-md-6 page-head-title">Product Management</div>
      <!-- <div class="col-md-3 padT20"><input type="text" name="search" class="search-textbox"><img src="{{ url('/img/search.png')}}" class="search-input"></div> -->
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@ProductClass') }}" class="user-panel-click">
          <img src="{{ url('/img/product-class.png') }}" alt="">
          <span class="user_title">Create Product Class</span>
        </a>
      </div>
    </div>
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@Product') }}" class="user-panel-click">
          <img src="{{ url('/img/create-product.png') }}" alt="">
          <span class="user_title">Create Product</span>
        </a>
      </div>
    </div>
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@Attributes') }}" class="user-panel-click">
          <img src="{{ url('/img/attributes.png') }}" alt="">
          <span class="user_title">Attributes</span>
        </a>
      </div>
    </div>
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@AssignPartNo') }}" class="user-panel-click">
          <img src="{{ url('/img/assign-number.png') }}" alt="">
          <span class="user_title">Assign Part Numbers</span>
        </a>
      </div>
    </div>
  </div>
  <div class="row user-panel-2">
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@PriceManagement') }}" class="user-panel-click">
          <img src="{{ url('/img/price.png') }}" alt="">
          <span class="user_title">Price Management</span>
        </a>
      </div>
    </div>
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@ViewAll') }}" class="user-panel-click">
          <img src="{{ url('/img/view-all.png') }}" alt="">
          <span class="user_title">View All</span>
        </a>
      </div>
    </div>
    <div class="col-md-3 col-sm-12">
      <div class="users-panel">
        <a href="{{ action('ProductController@BomImport') }}" class="user-panel-click">
          <img src="{{ url('/img/bom-import.png') }}" alt="">
          <span class="user_title">BOM Import</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
