<div class="table-responsive overflow_unset">
  <table class="table-bordered no-table-bg fs16 width100">
    <thead>
      <tr>
        <th width="8%" class="text-center fs12">
          Manufacturing Priority
        </th>
        <th width="5%" class="text-center fs12">
          Shipping Priority
        </th>
        <th width="5%" class="text-center fs12">
          WO# FACTORY
        </th>
        <th width="5%" class="text-center fs12">
          WO# FACTORY REFERENCE
        </th>
        <th width="5%" class="text-center fs12">
          WO# MEXICO
        </th>
        <th width="5%" class="text-center fs12">
          PO# CUSTOMER
        </th>
        <th width="8%" class="text-center fs12">
          Part Number
        </th>
        <th width="4%" class="text-center fs12">
          QTY
        </th>
        <th width="8%" class="text-center fs12">
          Date In(Factory)
        </th>
        <th width="8%" class="text-center fs12">
          Required Date
        </th>
        <th width="9%" class="text-center fs12">
          Delivery Date
        </th>
        <th width="10%" class="text-center fs12">
          STATUS
        </th>
        <th width="25%" class="text-center fs12">
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
        <td class="pad4 text-center">
          <select name="priority" class="priority form-control no_padding" data-id="{{$order_detail->id}}">
            <option value="">Select...</option>
            <option value="{{Config::get('constants.order_priority_high')}}" {{($order_detail->priority==Config::get('constants.order_priority_high')) ?'selected' : ''}}>High</option>
            <option value="{{Config::get('constants.order_priority_medium')}}" {{($order_detail->priority==Config::get('constants.order_priority_medium')) ?'selected' : ''}}>Medium</option>
            <option value="{{Config::get('constants.order_priority_low')}}" {{($order_detail->priority==Config::get('constants.order_priority_low')) ?'selected' : ''}}>Low</option>
          </select>
        </td>
        <td class="pad4 text-center">
          @if($order_detail->shipping_priority==Config::get('constants.shipping_priority_high'))
          High
          @elseif($order_detail->shipping_priority==Config::get('constants.shipping_priority_medium'))
          Medium
          @elseif($order_detail->shipping_priority==Config::get('constants.shipping_priority_low'))
          Low
          @endif
        </td>
        <td class="pad4 text-center">
          <a href="#" id="work_order" data-url="{{ action('OrdersController@SaveOrderEditable') }}" data-pk="{{ $order_detail->id }}" class="editable">{{$order_detail->work_order}}</a>
        </td>
        <td class="pad4 text-center">
          <a href="#" id="wo_factory_ref" data-url="{{ action('OrdersController@SaveOrderEditable') }}" data-pk="{{ $order_detail->id }}" class="editable">{{$order_detail->wo_factory_ref}}</a>
        </td>
        <td class="pad4 text-center">
          <a href="#" id="wo_mexico" data-url="{{ action('OrdersController@SaveOrderEditable') }}" data-pk="{{ $order_detail->id }}" class="editable">{{$order_detail->wo_mexico}}</a>
        </td>
        <td class="pad4 text-center">
          <a href="#" id="purchase_code" data-url="{{ action('OrdersController@SaveOrderEditable') }}" data-pk="{{ $order_detail->id }}" class="editable">{{$order_detail->purchase_code}}</a>
        </td>
        <td class="pad4 text-center">
          <a href="#" id="product_code" data-url="{{ action('OrdersController@SaveOrderEditable') }}" data-pk="{{ $order_detail->id }}" class="editable">{{$order_detail->product_code}}</a>
        </td>
        <td class="pad4 text-center">
          <a href="#" id="quantity" data-url="{{ action('OrdersController@SaveOrderEditable') }}" data-pk="{{ $order_detail->id }}" class="editable">{{$order_detail->quantity}}</a>
        </td>
        <td class="pad4 text-center">
          {{$order_detail->manufacturing_date}}
        </td>
        <td class="pad4 text-center">
          {{$order_detail->received_date}}
        </td>
        <td class="pad4 text-center">
          <div class="input-group">
            {!! Form::text('expected_delivery_date',!empty($order_detail->expected_delivery_date) ?$order_detail->expected_delivery_date : '',['class'=>'form-control datepicker_dd expected_delivery_date padL8','placeholder'=>'Choose Date','data-date-format'=>'yyyy-mm-dd','data-id'=>$order_detail->id]) !!}
          </div>
        </td>
        <td class="pad4 text-center">
          <select class="form-control order_item_status no_padding" name="order_item_status" data-id="{{$order_detail->id}}">
            <option value="">Select Status</option>
            <option value="{{Config::get('constants.client_order_completed')}}" {{($order_detail->status==Config::get('constants.client_order_completed')) ?'selected' : ''}}>Completed</option>
            <option value="{{Config::get('constants.client_order_onhold')}}" {{($order_detail->status==Config::get('constants.client_order_onhold')) ?'selected' : ''}}>On Hold</option>
            <option value="{{Config::get('constants.client_order_1week')}}" {{($order_detail->status==Config::get('constants.client_order_1week')) ?'selected' : ''}}>1 Week</option>
            <option value="{{Config::get('constants.client_order_2week')}}" {{($order_detail->status==Config::get('constants.client_order_2week')) ?'selected' : ''}}>2 Week</option>
            <option value="{{Config::get('constants.client_order_inprogress')}}" {{($order_detail->status==Config::get('constants.client_order_inprogress')) ?'selected' : ''}}>In Progress</option>
          </select>
        </td>
        <td class="pad4 text-center">
          <textarea rows="3" name="notes" class="notes width100" data-id="{{$order_detail->id}}" data-value="{{$order_detail->notes}}">{{$order_detail->notes}}</textarea>
        </td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="12" class="text-center pad5">No record found.</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
<script>
function saveData(reference,item,id,status,notes,expected_delivery_date,priority){
  var ref = reference;
  var url = '<?= action('OrdersController@SaveOrderDetailsData')?>';
  var completed = '<?= Config::get('constants.client_order_completed') ?>';
  var on_hold = '<?= Config::get('constants.client_order_onhold') ?>';
  var progress = '<?= Config::get('constants.client_order_inprogress') ?>';
  var one_week = '<?= Config::get('constants.client_order_1week') ?>';
  var two_week = '<?= Config::get('constants.client_order_2week') ?>';
  $.ajax({
    url :url,
    method:"POST",
    data:{'item':item,'id':id,'status':status,'notes':notes,'expected_delivery_date':expected_delivery_date,'priority':priority},
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(data){
      $("body").removeClass("loading");
      var data = $.parseJSON(data);
      if(data.response=='success'){
        if(data.item=='order_item_status'){
          if(data.status==completed){
            ref.parent().next().find('textarea.notes').val('');
            ref.parent().parent('tr').removeClass();
            ref.parent().parent('tr').addClass('completed');
          }else if(data.status==on_hold){
            ref.parent().parent('tr').removeClass();
            ref.parent().parent('tr').addClass('hold');
          }else if(data.status==progress){
            ref.parent().parent('tr').removeClass();
            ref.parent().parent('tr').addClass('progress');
          }else if(data.status==one_week){
            ref.parent().parent('tr').removeClass();
            ref.parent().parent('tr').addClass('oneweek');
          }else if(data.status==two_week){
            ref.parent().parent('tr').removeClass();
            ref.parent().parent('tr').addClass('twoweek');
          }else{
            ref.parent().parent('tr').removeClass();
          }
        }else if(data.item=='notes'){
          if(data.notes!==""){
            ref.parent().siblings().find('select.order_item_status').val(on_hold).attr("selected", "selected");
          }
        }
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
}
$('.expected_delivery_date').change(function(){
  var reference = $(this);
  var item = $(this).attr('name');
  var id = $(this).attr('data-id');
  var status = $(this).parent().parent().next().find('.order_item_status option:selected').val();
  var notes = $(this).parent().parent().nextAll().find('.notes').val();
  var expected_delivery_date = $(this).val();
  var priority = $(this).parent().parent().siblings().find('.priority').val();
  saveData(reference,item,id,status,notes,expected_delivery_date,priority);
});
$(".notes").blur(function(e) {
  var reference = $(this);
  var item = $(this).attr('name');
  var id = $(this).attr('data-id');
  var expected_delivery_date = $(this).parent().siblings().find('.expected_delivery_date').val();
  var notes = $(this).val();
  var status = $(this).parent().siblings().find('.order_item_status option:selected').val();
  var priority = $(this).parent().siblings().find('.priority').val();
  var old_value = $(this).attr('data-value');
  if(notes!==old_value){
    saveData(reference,item,id,status,notes,expected_delivery_date,priority);
  }
});
$('.order_item_status').change(function(){
  var reference = $(this);
  var item = $(this).attr('name');
  var id = $(this).attr('data-id');
  var expected_delivery_date = $(this).parent().siblings().find('.expected_delivery_date').val();
  var notes = $(this).parent().siblings().find('.notes').val();
  var status = $(this).val();
  var priority = $(this).parent().siblings().find('.priority').val();
  saveData(reference,item,id,status,notes,expected_delivery_date,priority);
});
$('.priority').change(function(){
  var reference = $(this);
  var item = $(this).attr('name');
  var id = $(this).attr('data-id');
  var priority = $(this).val();
  var expected_delivery_date = $(this).parent().nextAll().find('.expected_delivery_date').val();
  var status = $(this).parent().nextAll().find('.order_item_status option:selected').val();
  var notes = $(this).parent().nextAll().find('.notes').val();
  saveData(reference,item,id,status,notes,expected_delivery_date,priority);
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
          //$('.order_detail_holder').html('');
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

$.fn.editable.defaults.mode = 'popup';
//apply editable methods to all anchor tags
$(document).ready(function() {
    $('.editable').editable();
});
</script>
