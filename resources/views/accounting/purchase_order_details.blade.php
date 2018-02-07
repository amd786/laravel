@extends('layouts.app')

@section('content')
@section('title', 'Purchase Order')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row text-center">
      <div class="col-md-12">
        <div class="col-md-4 no_padding">
          <div class="col-sm-12 no_padding">
            <h2 class="text-left marginB30">Details</h2>
            <div class="table-responsive">
              <table class="no-border no-table-bg items-table width100">
                <tr>
                  <th class="no_padding padB10 fs20" width="60%">Purchase Order Number:</th>
                  <td width="40%" class="padB10 fs20 text-right">{{$order_detail->purchase_order}}</td>
                </tr>
                <tr>
                  <th class="no_padding padB10 fs20" width="60%">Supplier:</th>
                  <td width="40%" class="padB10 fs20 text-right">{{ucfirst($order_detail->getSupplierDetail->supplier_name)}}</td>
                </tr>
                <tr>
                  <th class="no_padding padB10 fs20" width="60%">Requested By:</th>
                  <td width="40%" class="padB10 fs20 text-right">{{ucfirst($order_detail->getUserDetail->first_name)}}&nbsp;{{ucfirst($order_detail->getUserDetail->last_name)}}</td>
                </tr>
                <tr>
                  <th class="no_padding padB10 fs20" width="60%">Requested On:</th>
                  <td width="40%" class="padB10 fs20 text-right">{{App\Library\Functions::customDateFormat($order_detail->created_at)}}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-7">
          <div class="row">
            <div class="col-md-12">
              <h2 class="text-left">Estimated Cost</h2>
              <div class="col-md-6 text-left fs20 no_padding">
                Amount
              </div>
              <div class="col-md-6 text-right fs20">
                @if($order_detail->cost>0)${{round($order_detail->cost,2)}}@endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h2 class="text-left marginB30">Purchase List</h2>
              <div class="table-responsive">
                <table class="no-border no-table-bg items-table width100">
                  <thead>
                    <tr>
                      <th width="50%" class="pad5 fs20">
                          Item
                      </th>
                      <th width="25%" class="pad5 fs20">
                          Total Qty
                      </th>
                      <th width="25%" class="pad5 fs20 text-right">
                          Work Order
                      </th>
                    </tr>
                  </thead>
                  <tbody class="items-body fs16">
                    @if(count($order_detail->getPurchaseList)>0)
                    @foreach($order_detail->getPurchaseList as $item)
                      <tr class="item_holder">
                        <td class="text-left pad5">
                          {{$item->sku}}
                        </td>
                        <td class="text-left pad5">
                          {{$item->quantity}}
                        </td>
                        <td class="text-left pad5 text-right">
                          {{$item->work_order}}
                        </td>
                      </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
     </div>
    </div>
    <hr class="group_by">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-4">
          <div class="row">
            <div class="col-md-12 no_padding">
              <div class="col-md-6 text-left fs20 no_padding">
                Status:
              </div>
              <div class="col-md-6 text-right fs20 no_padding">
                @if($order_detail->accounting_approval==0) Pending @elseif($order_detail->accounting_approval==1) Approved @else Rejected @endif
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-7 no_padding">
          <div class="col-md-12 no_padding">
            <div class="col-md-4">
              <a class="btn-default small_btn pull-right border1Black width100 round_btn text-center" data-id="{{$order_detail->id}}" id="approve_order">Approve</a>
            </div>
            <div class="col-md-4">
              <a class="btn-default small_btn pull-right border1Black width100 round_btn text-center" data-id="{{$order_detail->id}}" id="reject_order">Reject</a>
            </div>
            <div class="col-md-4">
              <a class="btn-default small_btn pull-right border1Black width100 round_btn text-center" href="{{action('AccountingController@PurchaseOrders')}}">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="reject_order_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content col-md-12 no_padding">
      <div class="modal-body main_div clearfix">
        <div class="col-md-12 fs20">
          <div class="col-md-4 text-center fs80 no_padding">
            <img src="{{ url('/img/cross-big.png') }}" alt="">
          </div>
          <div class="col-md-8 modal_approval">
            <div class="row marginT20">
              <div class="col-md-12">
                {{Form::open(array('url'=>action('AccountingController@RejectPurchaseOrder'),'id'=>'reject_order_form'))}}
                {{ Form::hidden('order_id', null, array('id' => 'order_id')) }}
                <h2 class="modal-title">Reject Purchase Order?</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
  		<div class="modal-footer clearfix no-border">
        <div class="row">
    			<div class="col-md-12">
            <div class="col-md-6">
            </div>
            <div class="col-md-3">
              {!! Form::submit('Ok',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn marginB5')) !!}
            </div>
            <div class="col-md-3">
              {!! Form::button('Cancel',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn','data-dismiss'=>'modal')) !!}
              {!!Form::close()!!}
            </div>
    			</div>
        </div>
  		</div>
    </div>
  </div>
</div>
<div class="modal fade" id="approve_order_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content col-md-12">
      <div class="modal-body main_div">
        <div class="col-md-12 fs20">
          <div class="col-md-4 text-center fs80 no_padding">
            <img src="{{ url('/img/tick-big.png') }}" alt="">
          </div>
          <div class="col-md-8 modal_checkout">
            <div class="row marginT20">
              <div class="col-md-12">
                {{Form::open(array('url'=>action('AccountingController@ApprovePurchaseOrder'),'id'=>'approve_order_form'))}}
                {{ Form::hidden('order_id', null, array('id' => 'order_id')) }}
                <h2 class="modal-title">Approve Purchase Order?</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer clearfix no-border">
        <div class="row">
           <div class="col-md-12">
              <div class="col-md-6">
              </div>
              <div class="col-md-3">
                {!! Form::submit('Ok',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn marginB5')) !!}
              </div>
              <div class="col-md-3">
                {!! Form::button('Cancel',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn','data-dismiss'=>'modal')) !!}
                {!!Form::close()!!}
              </div>
           </div>
        </div>
     </div>
    </div>
  </div>
</div>
<script>
$('#reject_order').on('click', function (e) {
  var order_id = $(this).attr('data-id');
  $('#reject_order_form').find('input[id="order_id"]').val(order_id);
  $('#reject_order_modal').modal('show');
});
$('#approve_order').on('click', function (e) {
  var order_id = $(this).attr('data-id');
  $('#approve_order_form').find('input[id="order_id"]').val(order_id);
  $('#approve_order_modal').modal('show');
});
</script>
@endsection
