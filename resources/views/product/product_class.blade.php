@extends('layouts.app')

@section('content')
@section('title', 'Product Class')
@include('product.sub_head')
<div class="row padB20">
  <div class="col-sm-6 text-right address-text hide">Product Class</div>
  <div class="col-sm-12">
    <a href="javascript:void(0)" class="add_product_class btn-default small_btn border1Black widthAuto round_btn padT10 padB10" data-url="{{ action('ProductController@ModalProductClass') }}">Add Product Class
    </a>
  </div>
  <!--<div class="col-sm-1 col-sm-offset-11">
    <a href="javascript:void(0)" class="add_product_class" data-url="{{ action('ProductController@ModalProductClass') }}">
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
              <th>Description</th>
              <th width="11%">Edit & Delete</th>
            </tr>
          </thead>
          <tbody>
              @if(count($all_pc)>0)
                <?php $pc_count = 1; ?>
                @foreach($all_pc as $pc)
                  <tr>
                    <td>{{ $pc_count }}</td>
                    <td>{{ $pc->product_class }}</td>
                    <td>{{ $pc->short_description }}</td>
                    <td><a href="javascript:void(0)" class="add_product_class" data-url="{{ action('ProductController@ModalProductClass',['id'=>$pc->id]) }}"><i class="fa fa-pencil-square fa-lg fs30"></i></a>&nbsp;<a href="{{ action('ProductController@DeleteProductClass',['id'=>$pc->id]) }}" class="" onclick="return confirm('Do you want to delete this product class?')"><i class="fa fa-trash-o fa-2x fs30"></i></a></td>
                  </tr>
                <?php $pc_count++; ?>
                @endforeach
            @else
              <tr>
                <td colspan="4" class="text-center">No record found.</td>
              </tr>
            @endif
          </tbody>
        </table>
    </div>
    <div class="col-md-12 text-right no_padding">
      @if(count($all_pc)>0)
        {{  $all_pc->links() }}
      @endif
    </div>
  </div>
</div>

<!-- modal -->
<div id="product_class" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

    </div>
  </div>
</div>
@endsection
