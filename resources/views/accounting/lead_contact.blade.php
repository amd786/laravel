<div class="row">
  <div class="col-sm-12 no_padding">
    <h4 class="marginT0">Account: {{$account->company_name or $account->supplier_name}}</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped no-table-bg fs16 width100">
        <thead>
          <tr>
            <th width="25%" class="text-left">
              Main Contact
            </th>
            <th width="20%" class="text-left">
              Title
            </th>
            <th width="25%" class="text-left">
              Phone Number
            </th>
            <th width="30%" class="text-left">
              Email
            </th>
          </tr>
        </thead>
        <tbody>
          @if(count($lead)>0)
          <tr>
            <td class="pad8 text-left">
              {{$lead->first_name}}&nbsp;{{$lead->last_name}}
            </td>
            <td class="pad8 text-left">
              {{$lead->position}}
            </td>
            <td class="pad8 text-left">
              {{$lead->phone}}
            </td>
            <td class="pad8 text-left">
              {{$lead->email}}
            </td>
          </tr>
          @else
          <tr>
            <td colspan="4" class="text-center pad5">No lead contact found.</td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
    <h4>Amount: ${{($db_transaction==1) ? round($order_detail->cost,2) : round($order_detail->final_amount,2)}}</h4>
    <h4>Payment Terms: {{$payment_terms->incoterm}}</h4>
  </div>
</div>
<div class="row padT10">
  <div class="col-md-3 no_padding">
    <a class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10 record_payment" {{ (count($payment_record)>0) ? 'disabled' : '' }} >Record Payment</a>
  </div>
  <div class="col-md-9 fs18" id="payment_recorded_on">
    @if(count($payment_record)>0)
    Payment Recorded on: {{date('M d, Y',strtotime($payment_record->updated_at))}}
    <a class='update_record colorBlack'><i class="fa fa-refresh" aria-hidden="true"></i></a>
    @endif
  </div>
</div>
<div class="modal fade" id="record_payment" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content col-md-12 no_padding">
      {{Form::open(array('url'=>action('AccountingController@SavePaymentRecord'),'id'=>'record_payment_form'))}}
      <div class="modal-body main_div clearfix">
        <div class="col-md-12 fs20">
          <div class="col-md-3 text-center fs80 no_padding">
            <img src="{{ url('/img/threshold-icon.png') }}" alt="">
          </div>
          <div class="col-md-9 modal_approval">
            <div class="row marginT20">
              <div class="col-md-12 no_padding padL20">
                {{ Form::hidden('db_transaction',$db_transaction, array('id' => 'db_transaction')) }}
                @if($db_transaction==0)
                {{ Form::hidden('fk_company_id',$account->company_id, array('id' => 'fk_company_id')) }}
                {{ Form::hidden('fk_quote_id',$order_detail->id, array('id' => 'fk_quote_id')) }}
                @else($db_transaction==1)
                {{ Form::hidden('fk_supplier_id',$account->id, array('id' => 'fk_supplier_id')) }}
                {{ Form::hidden('fk_purchase_order_id',$order_detail->id, array('id' => 'fk_purchase_order_id')) }}
                @endif
                {{ Form::hidden('amount',($db_transaction==1) ? round($order_detail->cost,2) : round($order_detail->final_amount,2), array('id' => 'amount')) }}
                <h2 class="modal-title">Record Payment?</h2>
                <div class="form-group padT10">
                  <div class="col-sm-5 text-left no_padding">
                    {{ Form::label('transaction_date','Enter the date payed:',['class'=>'fs18']) }}
                  </div>
                  <div class="col-sm-7 no_padding">
                    <div class="input-group">
                      {{ Form::text('transaction_date',null,['class'=>'form-control dateformat_datepicker border1Black','data-date-format'=>'yyyy-mm-dd','id'=>'transaction_date']) }}
                      <div class="input-group-addon border1Black noLBorder">
                          <span class="icon-calendar"></span>
                      </div>
                    </div>
                  </div>
                </div>
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
              {{ Form::submit('Ok',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn marginB5','id'=>'save_payment_record')) }}
            </div>
            <div class="col-md-3">
              {{ Form::button('Cancel',array('class'=>'btn-default small_btn pull-right border1Black width100 round_btn marginB5','data-dismiss'=>'modal')) }}
              {{Form::close()}}
            </div>
    			</div>
        </div>
  		</div>
    </div>
  </div>
</div>
<script>
$(function(){
  $(document).on("click", ".record_payment, .update_record", function (e) {
    e.preventDefault();
    $('#record_payment').modal('show');
  });
  $('#record_payment_form').submit(function(e){
    e.preventDefault();
    var payment_date = "";
    payment_date = $('#transaction_date').val();
    if(payment_date){
      $('#record_payment').modal('hide');
      var url = $(this).attr('action'); // the script where you handle the form input.
      $.ajax({
        url:url,
        type:'POST',
        data:new FormData(this),
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        dataType:'json',
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success:function(result){
          $("body").removeClass("loading");
          if(result.status=="success"){
            $.growl.notice({title:"Success", message:"Payment recorded successfully",size:'large',duration:2000});
            $('#payment_recorded_on').html("Payment Recorded on: "+result.updated_date+"&nbsp;<a class='update_record colorBlack'><i class='fa fa-refresh' aria-hidden='true'></i></a>");
            $('.record_payment').attr("disabled","disabled");
          }else if(result.status=="failure"){
            $.growl.error({title:"Error", message:result.message,size:'large',duration:2000});
          }

        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          //alert('Something went wrong.')
          //console.log(textStatus, errorThrown);
        }
      });
    }else{
      $.growl.error({title:"Error", message: "Please enter payment date", size:'large',duration:2000});
    }
  });
});

/*$("#save_payment_record").click(function(e) {
  var payment_date = "";
  payment_date = $('#payment_date').val();
  if(payment_date){
    $('#record_payment').modal('hide');
    var url = $('#record_payment_form').attr('action'); // the script where you handle the form input.
    //var data = $("#record_payment_form").serialize();
    var data = new FormData($('#record_payment_form'));
    console.log(data);
    $.ajax({
      url:url,
      method:'POST',
      data:{'data':data},
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(result){
        $("body").removeClass("loading");

      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  }else{
    $.growl.error({title:"Error", message: "Please enter payment date", size:'large',duration:2000});
  }

   // avoid to execute the actual submit of the form.
});*/
</script>
