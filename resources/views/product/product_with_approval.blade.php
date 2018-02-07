@extends('layouts.app')

@section('content')
@section('title', 'Product With Price')
  @include('product.sub_head')
  <div class="col-sm-7 col-sm-offset-2 padB30 text-center address-text hide">Product With Price</div>
  <div class="row marginB5">
    <div class="col-sm-12" style="padding:15px 0 5px 0;">
      <div class="col-md-2">
        <label class="bold">Product Class</label><br>
        <div class="col-sm-12 no_padding">
          {!! Form::select('product_class',$prod_class->getProductClassInDropdown(), Input::old('product_class'), ['class'=>'form-control product_class select2']) !!}
        </div>
      </div>
      <div class="col-md-2">
        <label class="bold">Product Name</label><br>
        <div class="col-sm-12 no_padding">
          {!! Form::select('product_name',$product->getProductInDropdown(), Input::old('product_name'), ['class'=>'form-control product_name select2','placeholder'=>'Please Select']) !!}
        </div>
      </div>
      <div class="col-md-8">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 products_with_approval_holder" id="products_with_approval_holder">
    </div>
  </div>

  <!-- modal -->
  <div id="modal_without_approval" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">

      </div>
    </div>
  </div>
<script>
$(function() {
  ProductsWithApprovalAjax();
  $('.product_class').on('change', function(e){
    ProductsWithApprovalAjax();
  });
  $('.product_name').on('change', function(e){
    ProductsWithApprovalAjax();
  });
});
function ProductsWithApprovalAjax(){
  var url = '<?= action('ProductController@ProductsWithApprovalTable') ?>';
  var product_class = $('.product_class option:selected').val();
  var product_name = $('.product_name option:selected').val();
  if(product_class==''){
    product_class = 0;
  }
  if(product_name==''){
    product_name = 0;
  }
  if(product_name>0 || product_class>0){
    if(product_class>0 && product_name==0){
      url = url+'?class='+product_class;
    }else if(product_class==00 && product_name>0){
      url = url+'?product='+product_name;
    }else{
      url = url+'?class='+product_class+'&product='+product_name;
    }
  }
  $.ajax({
    url:url,
    method:'GET',
    beforeSend: function() {
      $("body").addClass("loading");
    },
    success:function(result){
      if(result){
        $("body").removeClass("loading");
        $('.products_with_approval_holder').html(result);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}
</script>
@include('product.sub_foot')
@endsection
