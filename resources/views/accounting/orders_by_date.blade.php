@if(count($orders)>0)
  @foreach($orders as $order)
    <h3 class="text-left">{{App\Library\Functions::customDateFormat($order->created_at)}}</h3>
    <div class="table-responsive">
      <table class="table-bordered no-table-bg fs16 width100">
        <thead>
          <tr>
            <th width="20%">
              Purchase Order
            </th>
            <th width="20%">
              Date
            </th>
            <th width="15%">
              Amount
            </th>
            <th width="20%">
              Status
            </th>
            <th width="25%">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          @if(count($order->getOrdersByDate($order->created_at,0,$date_filter,"","",$search))>0)
            @foreach($order->getOrdersByDate($order->created_at,0,$date_filter,"","",$search) as $date_order)
              <tr>
                <td class="pad8 text-left">
                  {{$date_order->purchase_order}}
                </td>
                <td class="pad8 text-left">
                  {{App\Library\Functions::customDateFormat($date_order->created_at)}}
                </td>
                <td class="pad8 text-left">
                  {{$date_order->cost}}
                </td>
                <td class="pad8 text-left">
                  @if($date_order->accounting_approval==0) Pending @elseif($date_order->accounting_approval==1) Approved @else Rejected @endif
                </td>
                <td class="pad8 text-left">
                  <div class="col-md-12 no_padding">
                    <div class="col-md-1 no_padding">
                    </div>
                    <div class="col-md-2">
                      <a href="{{ action('AccountingController@PurchaseOrderDetails',['id'=>$date_order->id]) }}"><img src="{{ url('/img/action-icon.png') }}" alt=""></a>
                    </div>
                    <div class="col-md-2">
                      <a class="approve_order" data-id="{{$date_order->id}}"><img src="{{ url('/img/tick.png') }}" alt=""></a>
                    </div>
                    <div class="col-md-2">
                      <a class="reject_order" data-id="{{$date_order->id}}"><img src="{{ url('/img/cross-action.png') }}" alt=""></a>
                    </div>
                    <div class="col-md-5">
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
            @else
            <tr>
              <td colspan="6" class="text-center pad5">No record found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  @endforeach
  @else
  <div class="table-responsive">
    <table class="table-bordered no-table-bg fs16 width100">
      <thead>
        <tr>
          <th width="20%">
            Purchase Order
          </th>
          <th width="20%">
            Date
          </th>
          <th width="15%">
            Amount
          </th>
          <th width="20%">
            Status
          </th>
          <th width="25%">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="6" class="text-center pad5">No record found.</td>
        </tr>
      </tbody>
    </table>
  </div>
@endif
<div class="col-md-12 text-right no_padding">
  @if(count($orders)>0)
    {{  $orders->render() }}
  @endif
</div>
<script>
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.date_orders_holder').attr('id');
    if(holder=='date_orders_holder'){
      var url = $(this).attr('href');
      var search_text = $('#search_orders').val();
      var date_filter = $('#quick_date').val();
      if(search_text){
        url = url+'&search='+search_text;
      }
      if(date_filter){
        url = url+'&date_filter='+date_filter;
      }
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('#date_orders_holder').html(data);
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
