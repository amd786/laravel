<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <!--<h4 class="modal-title">
    @if ($attr_value->id == null)
      Add Attribute Value
    @else
      Edit Attribute Value
    @endif
  </h4>-->
</div>
<div class="modal-body">
  <div class="group-form">
    @if ($attr_value->id == null)
      {!! Form::model($attr_value,['url'=>action('ProductController@SaveAttrValue')]) !!}
    @else
      {!! Form::model($attr_value,['url'=>action('ProductController@SaveEditAttrValue',['id'=>$attr_value->id])]) !!}
    @endif

    <div class="col-sm-12">
      {!! Form::label('attribute_name','Attribute Name') !!}
      {!! Form::text('attribute_name',$attr->attribute_name,['class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="col-sm-12">
      {!! Form::label('attribute_value','Attribute Value') !!}
      {!! Form::text('attribute_value',null,['class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-sm-12">
      {!! Form::label('attribute_code','Attribute Code') !!}
      {!! Form::text('attribute_code',null,['class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-sm-12">
      {!! Form::label('notes','Notes') !!}
      {!! Form::textarea('notes',null,['class'=>'form-control','rows'=>2]) !!}
    </div>
    <div class="clearfix"></div>
    <br>

    <div class="col-sm-12 text-center">
      <input type="hidden" name="fk_attribute_id" value="{{ $attr->id }}">
      <button type="submit" class="save_btn"><img src="{{ url('/img/save_img.png')}}" class="cursor"></button>
    </div>
    <div class="clearfix"></div>
  {!! Form::close() !!}
  </div>
</div>
