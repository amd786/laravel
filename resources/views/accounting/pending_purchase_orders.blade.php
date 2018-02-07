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
      @if(count($pending_orders)>0)
        @foreach($pending_orders as $pending_order)
          <tr>
            <td class="pad8 text-left">
              {{$pending_order->purchase_order}}
            </td>
            <td class="pad8 text-left">
              {{App\Library\Functions::customDateFormat($pending_order->created_at)}}
            </td>
            <td class="pad8 text-left">
              @if($pending_order->cost>0)${{round($pending_order->cost,2)}}@endif
            </td>
            <td class="pad8 text-left">
              Pending
            </td>
            <td class="pad8">
              <div class="col-md-12 no_padding">
                <div class="col-md-1 no_padding">
                </div>
                <div class="col-md-2">
                  <a href="{{ action('AccountingController@PurchaseOrderDetails',['id'=>$pending_order->id]) }}"><img src="{{ url('/img/action-icon.png') }}" alt=""></a>
                </div>
                <div class="col-md-2">
                  <a class="approve_order" data-id="{{$pending_order->id}}"><img src="{{ url('/img/tick.png') }}" alt=""></a>
                </div>
                <div class="col-md-2">
                  <a class="reject_order" data-id="{{$pending_order->id}}"><img src="{{ url('/img/cross-action.png') }}" alt=""></a>
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
<div class="col-md-12 text-right no_padding">
  @if(count($pending_orders)>0)
    {{  $pending_orders->render() }}
  @endif
</div>
<script>
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.pending_table_holder').attr('id');
    if(holder=='pending_table_holder'){
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
          $('.pending_table_holder').html('');
          $('.pending_table_holder').html(data);
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
