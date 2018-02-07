@extends('layouts.app')

@section('content')
@section('title','Orders')
@include('client.sub_head')
<div class="portlet-body">  
  @if($value == 'yes')
    <div class="row text-center">
      <div class="col-md-12 bold" style="font-size:25px;">Order Placed !</div><div class="clearfix"></div><br><br>
      <div class="col-md-12"><img src="{{ url('/img/tick-big.png') }}"></div><div class="clearfix"></div><br><br>
      <div class="col-md-12 text-center">
        <a href="{{ action('ClientController@NewOrder') }}"><button class="btn btn-default">Go Back</button></a>
        <a href="{{ action('ClientController@OrderProgress') }}"><button class="btn btn-default">See Orders</button></a>
      </div>
    </div>
  @else 
    <div class="row text-center">
      <div class="col-md-12 bold" style="font-size:25px;">Order Cancelled !</div><div class="clearfix"></div><br><br>
      <div class="col-md-12"><img src="{{ url('/img/cross-big.png') }}"></div><div class="clearfix"></div><br><br>
      <div class="col-md-12 text-center">
        <a href="{{ action('ClientController@NewOrder') }}"><button class="btn btn-default">Go Back</button></a>
        <a href="{{ action('ClientController@OrderProgress') }}"><button class="btn btn-default">See Orders</button></a>
      </div>
    </div>
  @endif
</div>
@endsection
