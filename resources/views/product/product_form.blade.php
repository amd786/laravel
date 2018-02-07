<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <!--<h4 class="modal-title">Add Product</h4>-->
</div>
<div class="modal-body">
  <div class="group-form">
    @if ($product->id == null)
      {!! Form::model($product,['url'=>action('ProductController@SaveProduct')]) !!}
    @else
      {!! Form::model($product,['url'=>action('ProductController@SaveEditProduct',['id'=>$product->id])]) !!}
    @endif

    <div class="col-sm-12">
      {!! Form::label('fk_product_class','Select Product Class') !!}
      {!! Form::select('fk_product_class', $product->getProductClassInDropdown(), Input::old('fk_product_class'), ['placeholder' => 'Select option...','class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-sm-12">
      {!! Form::label('product_name','Product Name') !!}
      {!! Form::text('product_name',null,['class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-sm-12">
      {!! Form::label('part_number','Product Part Number') !!}
      {!! Form::text('part_number',null,['class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <br>

    <div class="col-sm-12 text-center">
      <button type="submit" class="save_btn"><img src="{{ url('/img/save_img.png')}}" class="cursor"></button>
    </div>
    <div class="clearfix"></div>
  {!! Form::close() !!}
  </div>
</div>
