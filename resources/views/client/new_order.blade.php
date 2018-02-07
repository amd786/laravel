@extends('layouts.app')

@section('content')
@section('title','File Import')
@include('client.sub_head')
<div class="row padB30 bom_page">
  <div class="col-md-8 col-md-offset-2 text-center">
    <!--<p class="bold fs20">You can import a purchase list for products from a spreadsheet. After uploading, you'll be asked to confirm the order.</p>-->
    {!! Form::open(['url' => action('ClientController@SaveClientOrder'),'files'=>true,'id'=>'order_order_form']) !!}
      <div class="col-md-4 col-md-offset-2 marginTB10 fs16 bold">Select file</div>
      <div class="col-md-4 marginTB10">
        {{ Form::file('order_file',['class'=>'text-center','id'=>'order_file']) }}
      </div>
      <div class="clearfix"></div>
      <div class="col-md-1 col-md-offset-2 marginTB10 fs16 bold"></div>
      <div class="col-md-12 marginTB10 text-center">
        {{ Form::button('Preview Excel file',['class'=>'btn btn-default','id'=>'preview_order_form','data-action'=>action('ClientController@PreviewOrder')]) }}
        {{ Form::button('Import and Confirm',['class'=>'btn btn-primary','id'=>'upload_order_file']) }}
      </div>
    {!! Form::close() !!}
  </div>
</div>

<div class="row">
  <div class="col-md-12 table-responsive no_padding" id="preview_holder">
  </div>
</div>

<script>
$(function(){
  $('#upload_order_file').click(function(){
    $('#order_order_form').submit();
  });
  $('#order_order_form').submit(function(e){
    e.preventDefault();
    if (confirm('Are you sure to upload?')) {
      var action = $(this).attr('action');
      $.ajax({
        url: action, // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        dataType:'json',
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success: function(data){
           // A function to be called if request succeeds
          $("body").removeClass("loading");
          if(data.status == 'error'){
            $.growl.error({title:"Error", message: data.message,size:'large',duration:5000});
            return false;
          }
          if(data.status == 'success'){
            window.location.href = data.url;
            //$.growl.notice({title:"Success", message: data.message,size:'large',duration:5000});
            //return false;
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          //alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  });


  $('#preview_order_form').click(function(e){
    e.preventDefault();
    if (confirm('Are you sure to preview this file?')) {
      var action = $(this).attr('data-action');
      $.ajax({
        url: action, // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: new FormData($("#order_order_form")[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        dataType:'json',
        beforeSend:function(){
          $("body").addClass("loading");
        },
        success: function(data){   // A function to be called if request succeeds
          $("body").removeClass("loading");
          if(data.status == 'error'){
            $('#preview_holder').html('');
            $.growl.error({title:"Error", message: data.message,size:'large',duration:5000});
            return false;
          }
          if(data.status == 'success'){
            $('#preview_holder').html(data.html);
            //$.growl.notice({title:"Success", message: data.message,size:'large',duration:5000});
            //return false;
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          //alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  });

});
</script>
@endsection
