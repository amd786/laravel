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
      @if(count($rejected_orders)>0)
        @foreach($rejected_orders as $rejected_order)
          <tr>
            <td class="pad8 text-left">
              {{$rejected_order->purchase_order}}
            </td>
            <td class="pad8 text-left">
              {{App\Library\Functions::customDateFormat($rejected_order->created_at)}}
            </td>
            <td class="pad8 text-left">
              @if($rejected_order->cost>0)${{round($rejected_order->cost,2)}}@endif
            </td>
            <td class="pad8 text-left">
              Rejected
            </td>
            <td class="pad8 text-left">
              <div class="col-md-12 no_padding">
                <div class="col-md-1 no_padding">
                </div>
                <div class="col-md-2">
                  <a href="{{ action('AccountingController@PurchaseOrderDetails',['id'=>$rejected_order->id]) }}"><img src="{{ url('/img/action-icon.png') }}" alt=""></a>
                </div>
                <div class="col-md-9">
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
  @if(count($rejected_orders)>0)
    {{  $rejected_orders->render() }}
  @endif
</div>
<script>
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.rejected_table_holder').attr('id');
    if(holder=='rejected_table_holder'){
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
          $('.rejected_table_holder').html('');
          $('.rejected_table_holder').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
});
$('.reject_order').on('click', function (e) {
  var order_id = $(this).attr('data-id');
  $('#reject_order_form').find('input[id="order_id"]').val(order_id);
  $('#reject_order').modal('show');
});
$('.approve_order').on('click', function (e) {
  var order_id = $(this).attr('data-id');
  $('#approve_order_form').find('input[id="order_id"]').val(order_id);
  $('#approve_order').modal('show');
});
</script>
