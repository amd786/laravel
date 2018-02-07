@extends('layouts.app')

@section('content')
@section('title', 'Payment Schedule')
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <div id="calendar">
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  var obj = '<?= $transactions ?>';
  var transactions = JSON.parse(obj);
  var calendar = $('#calendar').fullCalendar({
    eventLimit: true,
    /*buttonHtml: {
      //prev: '<i class="ace-icon fa fa-chevron-left"></i>',
      //next: '<i class="ace-icon fa fa-chevron-right"></i>'
    },*/
    header: {
        left: 'prev title next',
        right:'month listMonth'
    },
    views: {
        month: {
            buttonText: 'Calendar View',
            eventLimit: 3
        },
        listMonth: {
            buttonText: 'List View'
        }
    },
    events: transactions,
    viewRender: function (view, element) {
     if (view.name == 'listMonth') {
      if(!$( "#calendar .fc-view-container" ).parent().hasClass('holder')){
        $( "#calendar .fc-view-container" ).wrap( "<div class='holder col-md-12 no_padding'></div>" );
      }
      $('#calendar .fc-view-container').addClass('col-md-4 padL0 list_view');
      if($('#calendar .table_holder').length == 0) {
        $('#calendar .holder').append("<div class='table_holder col-md-8'></div>");
      }
    }else if(view.name == 'month'){
      $('#calendar .fc-view-container').removeClass('col-md-4 list_view padL0');
      $('#calendar div.table_holder').remove();
    }
  },
    eventClick: function(calEvent, jsEvent, view) {
      if (view.name == 'listMonth') {
        if(calEvent.transaction=='sales'){
          get_lead_contact(calEvent.transaction,calEvent.company_id,calEvent.cost,calEvent.term_id,calEvent.id);
        }else if(calEvent.transaction=='expense'){
          get_lead_contact(calEvent.transaction,calEvent.supplier_id,calEvent.cost,calEvent.term_id,calEvent.id);
        }
      }else if(view.name == 'month'){
        $('#calendar').fullCalendar('changeView','listMonth');
        if(calEvent.transaction=='sales'){
          get_lead_contact(calEvent.transaction,calEvent.company_id,calEvent.cost,calEvent.term_id,calEvent.id);
        }else if(calEvent.transaction=='expense'){
          get_lead_contact(calEvent.transaction,calEvent.supplier_id,calEvent.cost,calEvent.term_id,calEvent.id);
        }
      }
    },
    eventRender: function(event, element, view) {
      $(element).find("td.fc-list-item-marker").remove();
      //$(element).find('.fc-title').addClass(event.payment_status);
      $(element).find('.fc-list-item-title a').addClass(event.payment_status);
      $(element).find('.fc-title').html("<span class='cost fs16'>"+event.cost+"</span><span class='marginL5'>"+event.name+"</span>");
      $(element).find('.fc-list-item-title a').html("<span class='cost fs16 "+event.transaction+"'>"+event.cost+"</span><br><span>"+event.name+"</span>");
    },
    editable: false,
    selectable: false,
    displayEventTime: false
  });
});
function get_lead_contact(transaction,id,cost,term_id,order_id){
  var url = '<?= action('AccountingController@GetLeadContact')?>';
  $.ajax({
    url:url,
    method:'POST',
    data:{'id':id,'transaction':transaction,'cost':cost,'term_id':term_id,'order_id':order_id},
    beforeSend: function() {
      $("body").addClass("loading");
    },
    success:function(result){
      $("body").removeClass("loading");
      if(result){
        $('#calendar .table_holder').html(result);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.')
      console.log(textStatus, errorThrown);
    }
  });
}
</script>
@endsection
