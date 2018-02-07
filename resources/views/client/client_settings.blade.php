@extends('layouts.app')

@section('content')
@section('title', 'Settings')
@include('client.sub_head')

<div class="panel panel-default" style="border: 0px solid #000">
    <div class="panel-heading text-center hide"><strong>Add new user</strong></div>

    <div class="panel-body">
      <div class="group-form">
        @if ($model->id == null)
          {!! Form::model($model,['url'=>action('ClientController@SaveClientSettings'),'enctype'=>'multipart/form-data']) !!}
        @else
          {!! Form::model($model,['url'=>action('ClientController@SaveClientSettings',['id'=>$model->id]) ,'enctype'=>'multipart/form-data']) !!}
        @endif

        <div class="col-md-6">
          <!--<div class="col-md-12">
            {!! Form::label('fk_currency_id','Select Currency') !!}
            {!! Form::select('fk_currency_id', $model->getCurrencyInDropdown(), Input::old('fk_currency_id'), ['placeholder' => 'Select Currency...','class'=>'form-control']) !!}
          </div>-->
          <div class="col-md-12">
            {!! Form::label('fk_language_id','Select Language') !!}
            {!! Form::select('fk_language_id', $model->getLanguageInDropdown(), Input::old('fk_language_id'), ['placeholder' => 'Select Language...','class'=>'form-control']) !!}
          </div>
          <div class="col-md-12 marginT10">
            {!! Form::label('theme','Select Theme') !!}
            {!! Form::select('theme', ['dark_theme'=>'Dark Theme'], Input::old('theme'), ['placeholder' => 'Select Theme...','class'=>'form-control']) !!}
          </div>
          <div class="col-md-12 marginT10">
            {!! Form::label('sidebar_toggle','Sidebar Toggle') !!}
            {!! Form::select('sidebar_toggle', ['compact'=>'Compact','expand'=>'Expand'], Input::old('sidebar_toggle'), ['placeholder' => 'Select Option...','class'=>'form-control']) !!}
          </div>
        </div>
        <div class="col-sm-6">
          <div class="col-md-12">
            {!! Form::label('logo','Company logo') !!}
            {!! Form::file('logo',null,['class'=>'form-control','placeholder'=>'Company logo']) !!}
          </div>
          <div class="col-md-12 marginT20">
            @if($model->logo != null)
              <img src="{{ url('/uploads/client_logo/'.$model->logo) }}" style="max-width:250px">
            @endif
          </div>
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
</div>

@endsection
