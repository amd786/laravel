@extends('layouts.app')

@section('content')
@section('title', 'View All')
@include('product.sub_head')
<div class="row hide">
  <div class="col-md-7 text-center col-md-offset-2 address-text padB30">View All</div>
  <div class="clearfix"></div>
</div>
<div class="col-md-12 padB20">
  <div class="col-md-4 col-md-offset-8 padR25">
    <div class="col-sm-1 no_padding">
      <img src="{{ url('/img/search-icon.png') }}" alt="">
    </div>
    <div class="col-sm-11 no_padding padL8">
      {!! Form::text('search_text',null,['class'=>'form-control','id'=>'search_text','placeholder'=>'Search by Keyword']) !!}
    </div>
  </div>
</div>
<div class="row padB30">
  <div class="col-md-3 text-center no_padding">
    <div class="text-center bold">Category</div>
    <div class="col-sm-8 col-md-offset-2 no_padding">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      {!! Form::select('fk_product_class', $product->getProductClassInDropdown(), Input::old('fk_product_class'), ['id'=>'product_class_dd','placeholder' => 'Select Product Class...','class'=>'form-control fk_product_class','data-save'=>'false']) !!}
    </div>
  </div>
  <div class="col-md-3 text-center no_padding">
    <div class="text-center bold">Product</div>
    <div class="col-sm-8 col-md-offset-2 no_padding">
      {!! Form::select('product', $product->getProductInDropdown(), Input::old('product'), ['id'=>'product_name_dd','placeholder' => 'Select Product Name...','class'=>'form-control product']) !!}
    </div>
  </div>
  <div class="col-md-3 text-center no_padding">
    <div class="text-center bold">Date Added</div>
    <div class="col-sm-8 col-md-offset-2 no_padding">
      <select class="form-control show_order">
        <option value="DESC">Newest First</option>
        <option value="ASC">Oldest First</option>
      </select>
    </div>
  </div>
  <div class="col-md-3 text-center no_padding">
    <div class="text-center bold">BOM</div>
    <div class="col-sm-8 col-md-offset-2 no_padding">
      <select class="form-control has_bom">
        <option value="0">Select BOM</option>
        <option value="1">Has BOM</option>
        <option value="2">Does not have BOM</option>
      </select>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<div class="row">
  <div class="col-md-12 block_container">
  </div>
</div>
@include('product.sub_foot')
<script>
function searchProducts(){
  var class_id = $('.fk_product_class').val();
  var p_id = $('.product').val();
  var show_order = $('.show_order').val();
  var has_bom = $('.has_bom').val();
  var search_text = $('#search_text').val();
  $.ajax({
    url:baseURL+'/search-products',
    data:{class_id:class_id,p_id:p_id,show_order:show_order,has_bom:has_bom,search:search_text},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('.block_container').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}
$(function(){
  searchProducts();
});
$("#search_text").change(function(e) {
  searchProducts();
});
</script>
@endsection
