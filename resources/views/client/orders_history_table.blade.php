<div class="table-responsive">
  <table class="table-bordered no-table-bg fs16 width100">
    <thead>
      <tr>
        <th width="2%" class="text-center">
          #
        </th>
        <th width="10%" class="text-center">
          Order #
        </th>
        <th width="10%" class="text-center">
          Order By
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
        <th width="5%" class="text-center">
          Holds
        </th>
        <th width="10%" class="text-center">
          Pin to the top
        </th>
        <th width="10%" class="text-center">
          Status
        </th>
        <th width="20%" class="text-center">
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
              {{$order->getOrderCreator->first_name or ''}} {{$order->getOrderCreator->last_name or ''}}
            </td>
            <td class="pad8 text-center">
              {{App\Library\Functions::date_format2($order->created_at)}}
            </td>
            <td class="pad8 text-center">
              {{round($order->completion_percentage,2)}}%
            </td>
            <td class="pad8 text-center">
              @if(!empty($order->estimated_completion_date))
              {{App\Library\Functions::date_format2($order->estimated_completion_date)}}
              @endif
            </td>
            <td class="pad8 text-center">
              {{($order->getHoldCount($order->id)>0) ?$order->getHoldCount($order->id) :'None'}}
            </td>
            <td class="pad8 text-center">
              @if($order->pin_to_top==1)
              <a href="{{ action('ClientController@PinToTop',['id'=>$order->order_no]) }}"><i class="fa fa-star colorYellow" aria-hidden="true"></i></a>
              @else
              <a href="{{ action('ClientController@PinToTop',['id'=>$order->order_no]) }}" class="colorBlack"><i class="fa fa-star-o" aria-hidden="true"></i></a>
              @endif
            </td>
            <td class="pad8 text-center">
              @if($order->completion_percentage==100)
              Completed
              @else
              In Progress
              @endif
            </td>
            <td class="pad8 text-center">
              <a href="{{ action('ClientController@OrderProgressDetail',['id'=>$order->order_no]) }}" class="colorBlack">View Order</a>&nbsp;
              {{--*/
              $current_time = strtotime(date('Y-m-d h:i:s'));
              $added_time = strtotime(date("Y-m-d h:i:s", strtotime('+30 minutes', strtotime($order->created_at))));
              $interval  = abs($added_time - $current_time);
              $minutes   = round($interval / 60);
              /*--}}
              @if($minutes<=30)
              /&nbsp;<a href="{{ action('ClientController@DeleteOrder',['id'=>$order->order_no]) }}" onclick="return confirm('Are you sure to delete this order?')" class="red">Delete Order</a>
              @endif
            </td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="10" class="text-center pad5">No record found.</td>
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
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.orders_table_holder').attr('id');
    if(holder=='orders_table_holder'){
      var url = $(this).attr('href');
      var search_text = $('#search_orders').val();
      var date_filter = $('#quick_date').val();
      var status = $('.order_status').val();
      if(search_text){
        url = url+'&search='+search_text;
      }
      if(date_filter){
        url = url+'&date_filter='+date_filter;
      }
      if(status){
        url = url+'&status='+status;
      }
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('.orders_table_holder').html('');
          $('.orders_table_holder').html(data);
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
