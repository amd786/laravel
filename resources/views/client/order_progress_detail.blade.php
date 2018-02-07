@extends('layouts.app')

@section('content')
@section('title', 'Order Name: '."<a href='#' id='order_name' data-url='".action('ClientController@SaveOrderEditable')."' data-pk='".$order->order_no."' class='editable'>".$order->order_name."</a>")
@include('user.sub_head')
<div class="panel panel-default" style="border: 0px solid #000">
  <div class="panel-body">
    @include('client.order_detail')
  </div>
</div>
<script>
$.fn.editable.defaults.mode = 'popup';
$.fn.editable.defaults.placement = 'bottom';
$(document).ready(function() {
    $('.editable').editable();
});
</script>
@endsection
