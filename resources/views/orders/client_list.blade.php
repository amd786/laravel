<div class="table-responsive">
  <table class="table-bordered no-table-bg fs16 width100 table-striped">
    <thead>
      <tr>
        <th width="33%" class="text-center">
          Client
        </th>
        <th width="33%" class="text-center">
          Active Orders
        </th>
        <th width="34%" class="text-center">
          Open Client File
        </th>
      </tr>
    </thead>
    <tbody>
      @if(count($clients)>0)
      @foreach($clients as $client)
      <a>
      <tr data-href="{{ action('OrdersController@OrderManagement',['id'=>$client->fk_user_id]) }}" class="cursor client_order_detail">
        <td class="pad8 text-center">
          {{$client->UserDetails->first_name or ''}}&nbsp;{{$client->UserDetails->last_name or ''}}
        </td>
        <td class="pad8 text-center">
          {{$client->getClientActiveOrders($client->fk_user_id)}}
        </td>
        <td class="pad8 text-center">
          <a class="colorDarkGrey" href="{{ action('OrdersController@OrderManagement',['id'=>$client->fk_user_id]) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
        </td>
      </tr>
    </a>
      @endforeach
      @else
      <tr>
        <td colspan="3" class="text-center pad5">No record found.</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
<div class="col-md-12 text-right no_padding">
  @if(count($clients)>0)
    {{  $clients->render() }}
  @endif
</div>
<script>
$('.client_order_detail').click(function(){
  window.location = $(this).attr("data-href");
});
$('.pagination a').on('click', function(e){
    e.preventDefault();
    var holder  = $(this).closest('.client_list_holder').attr('id');
    if(holder=='client_list_holder'){
      var search_text = $('#search_client').val();
      var url = $(this).attr('href')+'&search_text='+search_text;
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('.client_list_holder').html(data);
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
