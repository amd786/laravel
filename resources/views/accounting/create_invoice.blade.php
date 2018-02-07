@extends('layouts.app')

@section('content')
@section('title', 'New Invoice')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    {{ Form::open(['url'=>action('AccountingController@SaveInvoice')]) }}
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-4">
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('invoice_date','Date',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding">
              <div class="input-group">
                {{ Form::text('invoice_date',null,['class'=>'form-control dateformat_datepicker border1Black','data-date-format'=>'yyyy-mm-dd']) }}
                <div class="input-group-addon border1Black noLBorder">
                    <span class="icon-calendar"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('invoice_due_date','Due Date',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding">
              <div class="input-group">
                {{ Form::text('invoice_due_date',null,['class'=>'form-control dateformat_datepicker border1Black','data-date-format'=>'yyyy-mm-dd']) }}
                <div class="input-group-addon border1Black noLBorder">
                    <span class="icon-calendar"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('fk_account_category_id','Type/Category',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding">
              {{ Form::select('fk_account_category_id', $invoices->getAccountsCategoriesDropdown(), Input::old('fk_account_category_id'), ['class'=>'form-control border1Black','data-action'=>action('AccountingController@AccountsByCategory')]) }}
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('fk_account_id','Account',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding pos_relative">
              {{ Form::select('fk_account_id',[''=>'Select option...'], Input::old('fk_account_id'), ['class'=>'form-control border1Black']) }}
              <span class="icon-spinner hide"><i class="fa fa-spinner"></i></span>
            </div>
          </div>
          <div class="clearfix"></div>
          <hr class="group_by"></hr>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('sku','Item',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding">
              {{ Form::text('sku',null,['class'=>'form-control autosku border1Black','data-value'=>action('InventoryController@InventorySkus'),'data-id'=>action('InventoryController@IsValidSku')]) }}
              <i class="fa fa-spinner fa-spin" id="autosku_loader" aria-hidden="true" style="display:none;"></i>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('description','Description',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding">
              {{ Form::textarea('description', null, ['class' => 'form-control border1Black','size' => '10x3']) }}
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('quantity','Quantity',['class'=>'fs18']) }}
            </div>
            <div class="col-sm-8 no_padding">
              {{ Form::number('quantity', null, ['class' => 'form-control border1Black','min'=>'1']) }}
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group padT10">
            <div class="col-sm-4 text-left no_padding">
              {{ Form::label('unit_cost','Cost<span style="font-size:12px">&nbsp;(per Quantity)</span>',['class'=>'fs18','min'=>'0'], false) }}
            </div>
            <div class="col-sm-8 no_padding">
              {{ Form::number('unit_cost', null, ['class' => 'form-control border1Black']) }}
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-sm-12 padT30 text-right">
              <button class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" id="add_to_invoice" disabled>Add to Invoice>></button>
            </div>
          </div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-7">
          <h3>Preview</h3>
          <div class="row">
            <div class="col-sm-12 fs18">
              <div class="col-sm-7 no_padding" id="account_detail">
                <p class="no_margin"><strong>Bill To:</strong></p>
              </div>
              <div class="col-sm-5 no_padding">
                <p class="no_margin" id="date_html"><strong>Invoice Date: </strong></p>
                <p class="no_margin" id="due_date_html"><strong>Invoice Due Date:</strong></p>
              </div>
            </div>
          </div>
          <div class="row padT30 fs18">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table no-border borderBottomNone no-table-bg fs16 width100" id="invoice_table">
                  <thead>
                    <tr>
                      <th width="30%" class="padL0 pad12">
                        Item
                      </th>
                      <th width="20%" class="pad12">
                        Quantity
                      </th>
                      <th width="20%" class="pad12">
                        Cost
                      </th>
                      <th width="25%" class="pad12">
                        Total Cost
                      </th>
                      <th width="5%" class="pad12">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="no_record">
                      <td colspan="5" class="text-center pad5">No Item Added.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row padT30 fs18">
            <div class="col-sm-5 col-sm-offset-7">
              <p class="no_margin"><strong>Total Due:</strong><span id="total_due"></span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row padT30">
      <div class="col-md-12 text-center">
        {!! Form::submit('Create Invoice',array('class'=>'btn-default small_btn border1Black widthAuto round_btn padT10 padB10','id'=>'create_invoice','disabled'=>'true')) !!}
        <a class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" id="invoice_cancel_btn" href="{{ action ('AccountingController@Invoices') }}">Cancel</a>
      </div>
    </div>
    {{ Form::close() }}
  </div>
</div>
<script>
function total_due_update(){
  var total_due = 0;
  $("#invoice_table tbody tr").each(function() {
    total_due += Number($(this).find('td.total_cost').attr('data-value'));
  });
  $('#total_due').html("&nbsp;$"+total_due);
}
$(function(){
  $('#fk_account_id, #invoice_date, #invoice_due_date, #fk_account_category_id').change(function(){
    var fk_account_id = $("#fk_account_id option:selected").val();
    var fk_account_category_id = $('#fk_account_category_id option:selected').val();
    var invoice_date = $('#invoice_date').val();
    var invoice_due_date = $('#invoice_due_date').val();
    if((fk_account_id>0) && (fk_account_category_id>0) && (invoice_date!=='') && (invoice_due_date!=='')){
      $("#add_to_invoice").prop('disabled', false);
      var url = '<?= action('AccountingController@AccountDetail')?>';
      $.ajax({
        url:url,
        method:'POST',
        dataType:'json',
        data: {'fk_account_id':fk_account_id},
        beforeSend: function() {
          $("body").addClass("loading");
        },
        success:function(result){
          $("body").removeClass("loading");
          if(result){
            if(result.status=='success'){
              $('#account_detail').html("<p class='no_margin'>"+result.name+"</p><p class='no_margin'>"+result.address+"</p>");
              $('#due_date_html').html("&nbsp;"+invoice_due_date);
              $('#date_html').html("&nbsp;"+invoice_date);
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }else{
      $("#add_to_invoice").prop('disabled', true);
    }
  });
  $('#add_to_invoice').click(function(e){
    e.preventDefault();
    var sku = $('#sku').val();
    var description = $('#description').val();
    var quantity = $('#quantity').val();
    var unit_cost = $('#unit_cost').val();
    var fk_account_id = $("#fk_account_id option:selected").val();
    if((sku!=='') && (sku!=='No result found.')){
      if(quantity>0){
        if(unit_cost>0){
          // remove no record found
          if($('#invoice_table > tbody > tr').hasClass("no_record")){
            $('#invoice_table > tbody > tr.no_record').remove();
          }
          // find if already added
          var already_added = 0;
          $("#invoice_table tbody tr.item").each(function() {
            var sku_added = $(this).find("td:first").find('span.item_sku').html();
            if(sku_added==sku){
              already_added = 1;
            }
          });
          if(already_added==0){
            $('#invoice_table > tbody').append("<tr class='item'><td class='pad12 padL0'><span class='item_sku'>"+sku+"</span><br><span style='font-size:12px'>"+description+"</span><input type='hidden' name='sku[]' value='"+sku+"'><input type='hidden' name='description[]' value='"+description+"'></td><td class='pad12'>"+quantity+"<input type='hidden' name='quantity[]' value='"+quantity+"'></td><td class='pad12'> $"+unit_cost+"<input type='hidden' name='unit_cost[]' value='"+unit_cost+"'></td><td class='pad12 total_cost' data-value="+quantity*unit_cost+"> $"+quantity*unit_cost+"</td><td><a class='delete_item'><img src='"+baseURL+"/img/close_img.png'></a></td></tr>");
            total_due_update();
            $('#sku, #description, #quantity, #unit_cost').val('');
            var rowCount = $('#invoice_table tr.item').length;
            if(rowCount>=1){
              $('#create_invoice').prop('disabled',false);
            }
            //return false;
          }else{
            $.growl.error({title:"Error", message:"Item already added", size:'large',duration:2000});
            return false;
          }
        }else{
          $.growl.error({title:"Error", message:"Please enter cost", size:'large',duration:2000});
          return false;
        }
      }else{
        $.growl.error({title:"Error", message:"Please enter quantity", size:'large',duration:2000});
        return false;
      }
    }else{
      $.growl.error({title:"Error", message:"Please enter item sku", size:'large',duration:2000});
      return false;
    }
  });
  $(document).on('click','.delete_item',function(e){
    $(this).closest('tr').remove();
    total_due_update();
    var rowCount = $('#invoice_table tr').length;
    if(rowCount==1){
      $('#total_due').html('');
      $("#invoice_table tbody").html("<tr class='no_record'><td colspan='5' class='text-center pad12'>No Item Added.</td></tr>");
      $('#create_invoice').prop('disabled',true);
    }
  });
  // account options
  $('#fk_account_category_id').change(function(){
    var fk_account_category_id = $(this).find("option:selected").val();
    if(fk_account_category_id>0){
      var url = $(this).attr('data-action');
      $.ajax({
        type: "POST",
        dataType: "json",
        url : url,
        data: {'fk_account_category_id':fk_account_category_id},
        beforeSend: function() {
          $(".icon-spinner").removeClass("hide");
        },
        success:function(data){
          if(data.status === 'success' ){
            $(".icon-spinner").addClass("hide");
            $("#fk_account_id option.item").remove();
            for (var i = 0; i < data.accounts.length; i++) {
              $('#fk_account_id').append("<option class='item' value='"+data.accounts[i].id+"'>"+data.accounts[i].account_name+"</option>")
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $(".icon-spinner").addClass("hide");
          $("body").removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  });
})
</script>
@endsection
