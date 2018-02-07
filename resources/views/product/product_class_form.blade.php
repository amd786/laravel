<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <!--<h4 class="modal-title">Add Product Class</h4>-->
</div>
<div class="modal-body">
  <div class="group-form">
    @if ($product_class->id == null)
      {!! Form::model($product_class,['url'=>action('ProductController@SaveProductClass')]) !!}
    @else
      {!! Form::model($product_class,['url'=>action('ProductController@SaveEditProductClass',['id'=>$product_class->id])]) !!}
    @endif

    <div class="col-sm-12">
      {!! Form::label('product_class','Product Class') !!}
      {!! Form::text('product_class',null,['class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-sm-12">
      {!! Form::label('short_description','Short Description') !!}
      {!! Form::textarea('short_description',null,['class'=>'form-control','rows'=>2]) !!}
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
