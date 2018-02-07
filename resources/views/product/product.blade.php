@extends('layouts.app')

@section('content')
@section('title', 'Create New Product')
@include('product.sub_head')
<div class="row padB20">
  <div class="col-sm-6 text-right address-text hide">Create New Product</div>
  <div class="col-sm-12">
    <a href="javascript:void(0)" class="add_product btn-default small_btn border1Black widthAuto round_btn padT10 padB10" data-url="{{ action('ProductController@ModalProduct') }}">Add Product
    </a>
  </div>
  <!--<div class="col-sm-1 col-sm-offset-11">
    <a href="javascript:void(0)" class="add_product" data-url="{{ action('ProductController@ModalProduct') }}">
      <div class="text-center">
        <img src="{{ url('/img/plus.png') }}">
      </div>
      <div class="add_user_txt"></div>
    </a>
  </div>-->
  <div class="clearfix"></div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Product Class</th>
              <th>Product Name</th>
              <th>Part Number</th>
              <th width="12%">Edit & Delete</th>
            </tr>
          </thead>
          <tbody>
              @if(count($all_products)>0)
                <?php $pc_count = 1; ?>
                @foreach($all_products as $all_product)
                  <tr>
                    <td>{{ $pc_count }}</td>
                    <td>{{ $all_product->productClass->product_class or '' }}</td>
                    <td>{{ $all_product->product_name }}</td>
                    <td>{{ $all_product->part_number }}</td>
                    <td><a href="javascript:void(0)" class="add_product" data-url="{{ action('ProductController@ModalProduct',['id'=>$all_product->id]) }}"><i class="fa fa-pencil-square fa-lg fs30"></i></a>&nbsp;<a href="{{ action('ProductController@DeleteProduct',['id'=>$all_product->id]) }}" class="" onclick="return confirm('Do you want to delete this product?')"><i class="fa fa-trash-o fa-2x fs30"></i></a></td>
                  </tr>
                <?php $pc_count++; ?>
                @endforeach
            @else
              <tr>
                <td colspan="5" class="text-center">No record found.</td>
              </tr>
            @endif
          </tbody>
        </table>
    </div>
    <div class="col-md-12 text-right no_padding">
      @if(count($all_products)>0)
        {{  $all_products->links() }}
      @endif
    </div>
  </div>
</div>

<!-- modal -->
<div id="product" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

    </div>
  </div>
</div>
@endsection
