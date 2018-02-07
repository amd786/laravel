<div class="row padB30">
  <div class="col-md-12">
    <div class="col-md-2 no_padding">
      <div class="text-center bold fs16">Status</div>
      <div class="col-sm-12 no_padding">
        <select class="form-control no_padding" name="search_status" id="search_status">
          <option value="">Select Status</option>
          <option value="{{Config::get('constants.client_order_completed')}}">Completed</option>
          <option value="{{Config::get('constants.client_order_onhold')}}">On Hold</option>
          <option value="{{Config::get('constants.client_order_1week')}}">1 Week</option>
          <option value="{{Config::get('constants.client_order_2week')}}">2 Week</option>
          <option value="{{Config::get('constants.client_order_inprogress')}}">In Progress</option>
        </select>
      </div>
    </div>
    <div class="col-md-7">
    </div>
    <div class="col-md-3 no_padding padT20">
      <div class="col-sm-12 no_padding">
        <div class="col-sm-1 no_padding">
          <img src="{{ url('/img/search-icon.png') }}" alt="">
        </div>
        <div class="col-sm-11 no_padding padL8">
          {!! Form::text('search_text',null,['class'=>'form-control','id'=>'search_text','placeholder'=>'Filter by Part number, PO customer number..']) !!}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="order_detail_holder" id="order_detail_holder">
    </div>
  </div>
</div>
<script>
function orderDetailsTable(){
  var order_no = '<?= $order_no ?>';
  var status = $('#search_status').val();
  var search = $('#search_text').val();
  var url = '<?= action('ClientController@OrderDetailsTable')?>'+'?order_no='+order_no+'&status='+status+'&search='+search;
  $.ajax({
    url:url,
    method:"GET",
    beforeSend:function(){
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      $('#order_detail_holder').html(result);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
}
$(function() {
  orderDetailsTable();
});
$('#search_status').change(function(){
  orderDetailsTable();
});
$("#search_text").change(function(e) {
    orderDetailsTable();
});
</script>
