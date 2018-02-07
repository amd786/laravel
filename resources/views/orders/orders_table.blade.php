<div class="table-responsive">
  <table class="table-bordered no-table-bg fs16 width100">
    <thead>
      <tr>
        <th width="5%" class="text-center">
          #
        </th>
        <th width="10%" class="text-center">
          Order #
        </th>
        <th width="10%" class="text-center">
          Date Received
        </th>
        <th width="10%" class="text-center">
          % Completion
        </th>
        <th width="15%" class="text-center">
          Est. Completion Date
        </th>
        <th width="10%" class="text-center">
          Holds
        </th>
        <th width="10%" class="text-center">
          Pin to the top
        </th>
        <th width="20%" class="text-center">
          $ Value
        </th>
        <th width="10%" class="text-center">
          Actions
        </th>
      </tr>
    </thead>
    <tbody>
      @if(count($orders)>0)
        @foreach($orders as $key=>$order)
          <tr>
            <td class="pad8 text-center">
              {{++$key}}
            </td>
            <td class="pad8 text-center">
              {{$order->order_no}}
            </td>
            <td class="pad8 text-center">
              {{App\Library\Functions::date_format2($order->created_at)}}
            </td>
            <td class="pad8 text-center">
              {{round($order->completion_percentage,2)}}%
            </td>
            <td class="pad8 text-center">
              <div class="input-group">
                {!! Form::text('estimated_completion_date',!empty($order->estimated_completion_date) ?App\Library\Functions::date_format2($order->estimated_completion_date) : '',['class'=>'form-control dateformat_datepicker estimated_completion_date','placeholder'=>'Choose Date','data-date-format'=>'yyyy-mm-dd','data-id'=>$order->order_no,'id'=>'estimated_completion_date']) !!}
                <div class="input-group-addon noLBorder">
                    <span class="icon-calendar"></span>
                </div>
              </div>
            </td>
            <td class="pad8 text-center">
              {{($order->getHoldCount($order->order_no)>0) ?$order->getHoldCount($order->order_no) :'None'}}
            </td>
            <td class="pad8 text-center">
              @if($order->pin_to_top==1)
              <a href="{{ action('OrdersController@PinToTop',['id'=>$order->order_no]) }}"><i class="fa fa-star colorYellow" aria-hidden="true"></i></a>
              @else
              <a href="{{ action('OrdersController@PinToTop',['id'=>$order->order_no]) }}" class="colorBlack"><i class="fa fa-star-o" aria-hidden="true"></i></a>
              @endif
            </td>
            <td class="pad8 text-center">
                USD {{ number_format($order->getTotalInUSD($order->order_no), 2) }}
            </td>
            <td class="pad8 text-center">
              <div class="col-sm-12">
                <div class="col-sm-6 no_padding">
                  <a href="{{ action('OrdersController@OrderDetail',['id'=>$order->order_no]) }}" class="colorDarkGrey" title="View Order"><i class="fa fa-eye" aria-hidden="true"></i></a>
                </div>
                <div class="col-sm-6 no_padding">
                  <a href="{{ action('OrdersController@DeleteOrder',['id'=>$order->order_no]) }}" class="colorDarkGrey" title="Delete Order"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="9" class="text-center pad5">No record found.</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
<div class="col-md-12 text-right no_padding">
  @if(count($orders)>0)
    {{  $orders->render() }}
  @endif
</div>
<script>
$('.estimated_completion_date').change(function(){
  var estimated_completion_date = $(this).val();
  var order_no = $(this).attr('data-id');
  var url = '<?= action('OrdersController@SaveOrderData')?>';
  $.ajax({
    url :url,
    method:"POST",
    data:{'estimated_completion_date':estimated_completion_date,'order_no':order_no},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(data){
      $("body").removeClass("loading");
      var data = $.parseJSON(data);
      if(data.status=='success'){
        $.growl.notice({title:"Success", message: "Order Updated Successfully.",size:'large',duration:3000});
      }else{
        $.growl.error({title:"Success", message: "Something went wrong.",size:'large',duration:3000});
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
});
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.order_management_holder').attr('id');
    if(holder=='order_management_holder'){
      var order_status = $('.order_status').val();
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      var quick_date = $('#quick_date').val();
      var url = $(this).attr('href')+'&order_status='+order_status+'&start_date'+start_date+'&end_date='+end_date+'&quick_date='+quick_date;
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('.order_management_holder').html('');
          $('.order_management_holder').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
});
</script>
