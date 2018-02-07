@extends('layouts.app')

@section('content')
@section('title', 'Product Without Price')
  @include('product.sub_head')
  <div class="col-sm-7 col-sm-offset-2 padB30 text-center address-text hide">Product Without Price</div>

  <form action="{{ action('ProductController@importExcel') }}" method="post" enctype="multipart/form-data">
  <div class="row">
    <div class="col-sm-12" style="padding:15px 0 5px 0;">
      <div class="col-md-2">
        <label class="bold">Product Class</label><br>
        <div class="col-sm-12 no_padding">
          {!! Form::select('product_class',$prod_class->getProductClassInDropdown(), Input::old('product_class'), ['class'=>'form-control product_class attr_dd']) !!}
        </div>
      </div>
      <div class="col-md-2">
        <label class="bold">Product Name</label><br>
        <div class="col-sm-12 no_padding">
          {!! Form::select('product_name',$product->getProductInDropdown(), Input::old('product_name'), ['class'=>'form-control product_name attr_dd','placeholder'=>'Please Select']) !!}
        </div>
      </div>
      <div class="filter_holder">

      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 padB8 no_padding">

          <div class="col-md-2">
      			<input type="file" name="import_file" style="display:inline"/>
          </div>
          <div class="col-md-1">
            <button class="btn btn-primary">Import Prices</button>
          </div>

    </div>
  </div>
  </form>

  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered" id="prod-unapp-table">
            <thead>
                <tr>
                  <th width="4%" data-orderable="false" class="text-center"><input type='checkbox' id='selectAll'></th>
                  <th  width="4%">#</th>
                  <th  width="10%">Product Name</th>
                  <th>Variant</th>
                  <th width="7%">Price</th>
                  <th width="12%">Individual Tolerance %</th>
                  <th width="7%">Retail Price</th>
                  <th width="12%">Edit & Approve / Disapprove</th>
                </tr>
            </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- modal -->
  <div id="modal_without_approval" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">

      </div>
    </div>
  </div>

  <script>
    //turn to popup mode
    $.fn.editable.defaults.mode = 'popup';

    //apply editable methods to all anchor tags
    $(document).ready(function() {
      var product_class = 0;
      $('.editable').editable();
      getProductFilters(product_class);
    });
    $('.product_class').on('change', function(e){
      var product_class = $('.product_class option:selected').val();
      getProductFilters(product_class);
    });
    function getProductFilters(product_class) {
      var url = '<?= action('ProductController@ProductFilter') ?>';
      $.ajax({
        url: url,
        method: 'POST',
        data: {'product_class':product_class},
        beforeSend: function() {
          $("body").addClass("loading");
        },
        success: function(result) {
          $("body").removeClass("loading");
          $('.filter_holder').html(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $("body").removeClass("loading");
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  </script>
  @include('product.sub_foot')

@endsection
