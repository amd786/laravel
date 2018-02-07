<div class="table-responsive overflow_unset">
  <table class="table-bordered no-table-bg fs16 width100">
    <thead>
      <tr>
        <!-- <th width="5%" class="text-center fs12">
          Priority
        </th>
        <th width="5%" class="text-center fs12">
          WO# FACTORY
        </th>
        <th width="10%" class="text-center fs12">
          WO# FACTORY REFERENCE
        </th>
        <th width="5%" class="text-center fs12">
          WO# MEXICO
        </th> -->
        <th width="10%" class="text-center fs12">
          Shipping Priority
        </th>
        <th width="5%" class="text-center fs12">
          PO# CUSTOMER
        </th>
        <!-- <th width="5%" class="text-center fs12">
          Sales Person
        </th> -->
        <th width="10%" class="text-center fs12">
          Part Number
        </th>
        <th width="5%" class="text-center fs12">
          QTY
        </th>
        <th width="10%" class="text-center fs12">
          Date In(Factory)
        </th>
        <th width="10%" class="text-center fs12">
          Required Date
        </th>
        <th width="10%" class="text-center fs12">
          Delivery Date
        </th>
        <th width="5%" class="text-center fs12">
          STATUS
        </th>
        <th width="15%" class="text-center fs12">
          NOTES
        </th>
      </tr>
    </thead>
    <tbody>
    @if(count($order_details)>0)
      @foreach($order_details as $order_detail)
      {{--*/ $class="";
        if($order_detail->status==Config::get('constants.client_order_completed')){
          $class = "completed";
        }elseif($order_detail->status==Config::get('constants.client_order_onhold')){
          $class = "hold";
        }elseif($order_detail->status==Config::get('constants.client_order_1week')){
          $class = "oneweek";
        }elseif($order_detail->status==Config::get('constants.client_order_2week')){
          $class = "twoweek";
        }elseif($order_detail->status==Config::get('constants.client_order_inprogress')){
          $class = "progress";
        }
       /*--}}
      <tr class="{{$class}}">
        <!-- <td class="pad8 text-center">
          @if($order_detail->priority==Config::get('constants.order_priority_high'))
          High
          @elseif($order_detail->priority==Config::get('constants.order_priority_medium'))
          Medium
          @elseif($order_detail->priority==Config::get('constants.order_priority_low'))
          Low
          @endif
        </td> -->
        <!-- <td class="pad8 text-center">
          {{$order_detail->work_order}}
        </td>
        <td class="pad8 text-center">

        </td>
        <td class="pad8 text-center">

        </td> -->
        <td class="pad8 text-center">
          <select name="shipping_priority" class="shipping_priority form-control no_padding" data-id="{{$order_detail->id}}">
            <option value="">Select Priority</option>
            <option value="{{Config::get('constants.shipping_priority_high')}}" {{($order_detail->shipping_priority==Config::get('constants.shipping_priority_high')) ?'selected' : ''}}>High</option>
            <option value="{{Config::get('constants.shipping_priority_medium')}}" {{($order_detail->shipping_priority==Config::get('constants.shipping_priority_medium')) ?'selected' : ''}}>Medium</option>
            <option value="{{Config::get('constants.shipping_priority_low')}}" {{($order_detail->shipping_priority==Config::get('constants.shipping_priority_low')) ?'selected' : ''}}>Low</option>
          </select>
        </td>
        <td class="pad8 text-center">
          {{$order_detail->purchase_code}}
        </td>
        <!-- <td class="pad8 text-center">

        </td> -->
        <td class="pad8 text-center">
          {{$order_detail->product_code}}
        </td>
        <td class="pad8 text-center">
          {{$order_detail->quantity}}
        </td>
        <td class="pad8 text-center">
          {{$order_detail->manufacturing_date}}
        </td>
        <td class="pad8 text-center">
          {{$order_detail->received_date}}
        </td>
        <td class="pad8 text-center">
          {{$order_detail->expected_delivery_date}}
        </td>
        <td class="pad8 text-center">
          @if($order_detail->status==Config::get('constants.client_order_completed'))
          Completed
          @elseif($order_detail->status==Config::get('constants.client_order_onhold'))
          On Hold
          @elseif($order_detail->status==Config::get('constants.client_order_1week'))
          1 Week
          @elseif($order_detail->status==Config::get('constants.client_order_2week'))
          2 Week
          @elseif($order_detail->status==Config::get('constants.client_order_inprogress'))
          In Progress
          @endif
        </td>
        <td class="pad8 text-center">
          {{$order_detail->notes}}
        </td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="13" class="text-center pad5">No record found.</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
<script>
$('.shipping_priority').change(function(){
  var shipping_priority = $(this).val();
  var url = '<?= action('ClientController@SaveOrderDetailsData')?>';
  var id = $(this).attr('data-id');
  $.ajax({
    url :url,
    method:"POST",
    data:{'shipping_priority':shipping_priority,'id':id},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(data){
      $("body").removeClass("loading");
      var data = $.parseJSON(data);
      if(data.status=='success'){
        $.growl.notice({title:"Success", message: "Data Updated Successfully.",size:'large',duration:3000});
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
    var holder  = $(this).closest('.order_detail_holder').attr('id');
    if(holder=='order_detail_holder'){
      var order_no = '<?= $order_no ?>';
      var url = $(this).attr('href')+'&order_no='+order_no;
      $.ajax({
        url :url,
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(data){
          $("body").removeClass("loading");
          $('.order_detail_holder').html(data);
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
