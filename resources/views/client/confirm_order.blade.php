@extends('layouts.app')

@section('content')
@section('title','Confirm and Place Order')
@include('client.sub_head')
<div class="row padB30 bom_page" style="font-size:18px;margin-top:30px;line-height:29px">
  {{ Form::open(['url'=>action('ClientController@PlaceOrder',['id'=> $order->order_no , 'value' => 'yes'])]) }}
  <div class="col-md-5 col-md-offset-1 no_padding" >
    <div class="col-md-12 bold" style="font-size:22px;margin-bottom: 5px;">Details</div>
    <div class="col-md-5">Order Name: </div>
    <div class="col-md-7">
      {{ Form::text('order_name',null,['class'=>(Session::has('error') && Session::get('error')=='The order name field is required.') ?'form-control height30 width70 BorderRed' :'form-control height30 width70','id'=>'order_name','autofocus']) }}
    </div>
    <div class="clearfix"></div>

    <div class="col-md-5">Order Number: </div><div class="col-md-7">{{ $order->order_no }}</div>
    <div class="clearfix"></div>

    <div class="col-md-5">Ordered By: </div><div class="col-md-7">{{ $order->getOrderCreator->fullName() }}</div>
    <div class="clearfix"></div>

    <div class="col-md-5">Ordered On: </div><div class="col-md-7">{{ App\Library\Functions::date_format($order->created_at) }}</div>
    <div class="clearfix"></div>

    <div class="col-md-5">Delivery Time: </div><div class="col-md-7">-</div>
    <div class="clearfix"></div>

    <div class="col-md-5">Payment Terms: </div><div class="col-md-7">-</div>
    <div class="clearfix"></div>

    <div class="col-md-12 bold" style="font-size:22px;margin-bottom: 5px;margin-top: 20px;">Cost</div>
    <div class="col-md-5">Subtotal: </div>
    <div class="col-md-7">
      @if($order->getUsdTotal($order->order_no)>0)
      USD {{ number_format($order->getTotalInUSD($order->order_no), 2) }}
      @endif
      @if($order->getPesosTotal($order->order_no)>0)
      <br>
      MXN {{ number_format($order->getPesosTotal($order->order_no), 2) }}
      @endif
      <br>
    </div>
    <div class="clearfix"></div>

    <!--<div class="col-md-6">Tax: </div><div class="col-md-6">-</div>
    <div class="clearfix"></div>

    <div class="col-md-6">Shipping: </div><div class="col-md-6">-</div>
    <div class="clearfix"></div>-->

    <div class="col-md-5">Total: </div><div class="col-md-7">
      USD {{ number_format($order->getTotalInUSD($order->order_no), 2) }}
    </div>
    <div class="clearfix"></div>
  </div>
  <!-- end of left side -->
  <!-- right side -->
  <div class="col-md-6">
    <div class="col-md-12 bold" style="font-size:22px;margin-bottom: 5px;">Item List</div>
    <div class="col-md-12 no_padding">
      <div class="col-md-7">Item</div><div class="col-md-2 no_padding">Quantity</div><div class="col-md-3 no_padding">Price</div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 no_padding" style="max-height:400px;overflow-y:auto;font-size:16px">
      @if(count($order_details) > 0)
        @foreach($order_details as $order_detail)
          <div class="col-md-7">{{ $order_detail->product_code }}</div>
          <div class="col-md-2">{{ $order_detail->quantity }}</div>
          <div class="col-md-3">{{(!empty($order_detail->item_total)) ? '$ '.number_format($order_detail->item_total,2) : ''}}</div>
          <div class="clearfix"></div>
        @endforeach
      @endif
    </div>
  </div>
  <div class="clearfix"></div>
  <hr>
  <div class="col-md-12 text-center">
    <input type="submit" class="btn btn-primary" value="Place Order">
    <!--<a href="{{ action('ClientController@PlaceOrder',['id'=> $order->order_no , 'value' => 'yes']) }}"><button type="submit" class="btn btn-primary">Place Order</button></a>-->
    <a href="{{ action('ClientController@PlaceOrder',['id'=> $order->order_no , 'value' => 'no']) }}"><button class="btn btn-default">Cancel Order</button></a>
  </div>
  {{ Form::close() }}
</div>
@endsection
