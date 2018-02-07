@extends('layouts.app')

@section('content')
@section('title', 'BOM Import')
@include('product.sub_head')
<div class="row padB30 bom_page">
  <div class="col-md-8 col-md-offset-2 text-center">
    <!--<p class="bold fs20">You can import BOM for products from spreadsheets. Select the product family first, then click browse to select file. Then click upload and you will be asked to confirm.</p>-->
    {!! Form::open(['url' => action('ProductController@SaveBomImport'),'files'=>true,'id'=>'bom_import_form']) !!}
      <input type="hidden" value="{{--*/ echo phpversion(); /*--}}"/>
      <div class="col-md-4 col-md-offset-2 marginTB10 fs16 bold">Select product family</div>
      <div class="col-md-4 marginTB10">
        {!! Form::select('product_family', $model->getProductInDropdown(), Input::old('product_family'), ['placeholder' => 'Select option...','class'=>'form-control']) !!}
      </div>
      <div class="clearfix"></div>
      <div class="col-md-4 col-md-offset-2 marginTB10 fs16 bold">Select file</div>
      <div class="col-md-4 marginTB10">
        <div class="choose_file">
        {{ Form::file('bom_file',['class'=>'text-center fs14','id'=>'bom_file']) }}
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-1 col-md-offset-2 marginTB10 fs16 bold"></div>
      <div class="col-md-7 marginTB10">
        <input type="checkbox" name="update_bom" value="1">
        <b>Only import BOMs for SKUs that currently do not have BOMs (do not update existing BOMs)</b>
      </div>
      <div class="col-md-12 marginTB10">
        <div class="col-md-6">
          {{ Form::button('Preview',['class'=>'btn btn-default btn-warning pull-right padL30 padR30','id'=>'preview','data-action'=>action('ProductController@PreviewBom')]) }}
        </div>
        <div class="col-md-6">
          {{ Form::submit('Upload & Save',['class'=>'btn btn-default btn-success pull-left disabled','id'=>'upload_file']) }}
        </div>
      </div>
    {!! Form::close() !!}
  </div>
</div>
<div class="row">
  <div class="col-md-12 table-responsive" id="preview_holder">
  </div>
</div>
<script>
$(function(){
  $('#upload_file').click(function(){
    $('#bom_import_form').submit();
  });
  $('#bom_import_form').submit(function(e){
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
        success: function(data){   // A function to be called if request succeeds
          $("body").removeClass("loading");
          if(data.status == 'error'){
            $.growl.error({title:"Error", message: data.message,size:'large',duration:5000});
            return false;
          }
          if(data.status == 'success'){
            $('#upload_file').addClass('disabled');
            $.growl.notice({title:"Success", message: data.message,size:'large',duration:5000});
            $('#preview_holder').html('');
            $('.bom_page').html(data.html);
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
  $('#preview').click(function(){
    var action = $(this).attr('data-action');
    $.ajax({
      url: action, // Url to which the request is send
      type: "POST",             // Type of request to be send, called as method
      data: new FormData($("#bom_import_form")[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      dataType:'json',
      contentType: false,       // The content type used when sending data to the server.
      cache: false,             // To unable request pages to be cached
      processData:false,        // To send DOMDocument or non processed data file it is set to false
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success: function(data){   // A function to be called if request succeeds
        $("body").removeClass("loading");
        if(data.status == 'error'){
          $.growl.error({title:"Error", message: data.message,size:'large',duration:5000});
          return false;
        }
        else if(data.status == 'failure'){
          $.growl.error({title:"Error", message: data.html,size:'large',duration:5000});
          return false;
        }
        else if(data.status == 'success'){
          $('#preview_holder').html(data.html);
          $('#upload_file').removeClass('disabled');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });
});
</script>
@endsection
