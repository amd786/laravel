@extends('layouts.app')

@section('content')
@section('title', 'Assign Part Numbers')
@include('product.sub_head')
<br><br>
<div class="col-md-7 text-center padB30 col-md-offset-2 hide"><h3>Assign Part Numbers</h3></div>
<div class="row">
  <div class="col-md-12 group-form">
    {!! Form::model($model,['id'=>'attr_form']) !!}
    <div class="col-md-4">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      {!! Form::label('fk_product_class_id','Product Class') !!}
      {!! Form::select('fk_product_class_id', $model->getProductClassInDropdown(), count($assign_parts)>0 ? $assign_parts->fk_product_class_id : Input::old('fk_product_class_id'), ['placeholder' => 'Select option...','class'=>'form-control']) !!}
    </div>
    <div class="clearfix"></div><br>
    <div class="col-md-4">
      {!! Form::label('fk_product_id','Product Name') !!}
      {!! Form::select('fk_product_id', count($assign_parts)>0 ? $model->getProductInDropdown($assign_parts->fk_product_class_id) : $model->getProductInDropdown($model->fk_product_class_id), count($assign_parts)>0 ? $assign_parts->fk_product_id : Input::old('fk_product_id'), ['id'=>'product_name_dd','placeholder' => 'Select option...','class'=>'form-control']) !!}
    </div><?= Input::get('id'); ?>
    <div class="clearfix"></div><br>
    <div class="col-md-12">
      {!! Form::label('attribute_selection','Attribute Selection') !!}
      <div class="col-md-9 no_padding attr_box padT10 padB10">
        @if(count($attributes)>0)
            <!--<div class="col-sm-6 attr_box_text">
              <div class="col-sm-9"></div>
              <div class="col-sm-1">All</div>
              <div class="col-sm-2">Select</div>
            </div>
            <div class="col-sm-6 attr_box_text">
              <div class="col-sm-9"></div>
              <div class="col-sm-1">All</div>
              <div class="col-sm-2">Select</div>
            </div>
            <div class="clearfix"></div><hr style="margin:0">-->
            @foreach($attributes as $attribute)
              <div class="col-sm-6 attr_box_text">
                <div class="col-sm-9">{{ $attribute->attribute_name }}</div>
                <div class="col-sm-1">
                  <div class="checkbox abc-checkbox abc-checkbox-success">
                    <input type="checkbox" class="all_attr_check" id="{{ $attribute->id }}" value="{{ $attribute->id }}" {{(in_array($attribute->id,$selected)) ? 'checked' : ''}}>
                    <label for="{{ $attribute->id }}"></label>
                  </div>
                </div>
                <div class="col-sm-2 text-center">
                  <i class="fa fa-location-arrow cursor open_attr_select {{(in_array($attribute->id,$single)) ? 'sel_check_act' : ''}}" id="sel{{ $attribute->id }}" data-url="{{ action('ProductController@ModalAttributeValSel',['id'=>$attribute->id]) }}"></i>
                </div>
              </div>
            @endforeach
        @else
          <div class="col-sm-12 attr_box_text_no padB8">No attributes found.</div>
        @endif
      </div>
      <div class="clearfix"></div><br>
      <div class="col-md-9 text-center">
        <div class="col-md-12">
          <div class="col-md-6 text-right">
            <button class="btn btn-default btn-lg" id="generate_prod">Generate</button>
          </div>
          <div class="col-md-6 text-left">
            <a class="btn btn-default btn-lg {{ count($assign_parts)==0 ? 'disabled' : '' }}" href="{{ action('ProductController@ClearAssignParts')}}">Clear</a>
          </div>
        </div>

      </div>
      <div class="clearfix"></div><br>
    </div>
    {{ Form::close() }}
  </div>
</div>

<!-- modal -->
<div id="attr_value_sel" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">

    </div>
  </div>
</div>
@endsection
