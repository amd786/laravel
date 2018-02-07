@extends('layouts.app')

@section('content')
@section('title', 'View All')
@include('product.sub_head')
<div class="row hide">
  <div class="col-md-7 text-center col-md-offset-2 address-text padB30">View All</div>
  <div class="clearfix"></div>
</div>
<div class="row">
  <div class="col-md-12 block_container">
      <div class="col-md-12 text-center padB8 fs20">{{ strtoupper($assign_prod->code) }}</div>
      <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>Product</th>
                  <?php $all_attr_vals = explode(',',$assign_prod->sel_attr_val_id); ?>
                  @foreach($all_attr_vals as $all_attr_val)
                    <?php App\Library\Functions::get_prod_attr_disp($all_attr_val) ?>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ $assign_prod->product->product_name or '' }}</td>
                  <?php $all_attr_vals = explode(',',$assign_prod->sel_attr_val_id); ?>
                  @foreach($all_attr_vals as $all_attr_val)
                    <?php App\Library\Functions::get_prod_attr_val_disp($all_attr_val) ?>
                  @endforeach
                </tr>
              </tbody>
            </table>
        </div>
      </div>
  </div>
  <div class="col-md-12">
    <div class="col-md-4">
      <div class="row">
        <div class="col-sm-12">
          <div class="col-sm-3 no_padding">
            <div class="bold">Added By:</div>
          </div>
          <div class="col-sm-9 padL0">
            <div><?= (($assign_prod->AddedBy !== null)?$assign_prod->AddedBy->fullName():'No Name'); ?></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="col-sm-3 no_padding">
            <div class="bold">Added Date:</div>
          </div>
          <div class="col-sm-9 padL0">
            <div>{{ App\Library\Functions::date_format($assign_prod->created_at) }}</div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="col-sm-3 no_padding">
            <div class="bold">Notes:</div>
          </div>
          <div class="col-sm-9 padL0">
            <div>
              <textarea class="form-control" id="notes_assgn_p_val" data-assignid="{{ $assign_prod->id }}">{{ $assign_prod->notes }}</textarea>
              {{ csrf_field() }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 text-right marginT20">
        <button class="btn btn-danger delete_assign_val_view_all fs16 padL20 padR20" data-return="{{ action('ProductController@ViewAll') }}" data-assignid="{{ $assign_prod->id }}">Delete</button>
      </div>
    </div>
    <div class="col-md-8">
      <div class="col-sm-12 no_padding table-responsive" id="table_holder">
        <form method="POST" action="{{ action('ProductController@SaveRawMaterialList') }}">
          <div class="col-md-2 no_padding">
            <h4 class="text-left pull-left">Raw Material List</h4>
          </div>
          <div class="col-md-10 text-left marginT10 no_padding">
            <a href="javascript:;" class="raw_sup not_sp_form edit_anc_btn">Edit</a>
            <a href="javascript:;" class="raw_sup sp_form hide"><button class="btn-link no_padding" id="save_data" type="submit">Save</button></a>
            <a href="javascript:;" class="raw_sup sp_form hide cancel_anc_btn">Cancel</a>
          </div>
          <table class="table table-bordered table-striped no-table-bg fs16 width100" id="raw_material_list">
            <thead>
              <tr>
                <th width="30%">Part Name</th>
                <th width="20%">Part Number</th>
                <th width="20%">Material</th>
                <th width="15%">Quantity</th>
                <th width="15%">Unit</th>
              </tr>
            </thead>
            <tbody class="raw_add">
              @if(count($raw_materials)>0)
                @foreach($raw_materials as $raw_material)
                  <tr id="{{$raw_material->id}}">
                    <td class="pad5 text-left" width="30%">
                      {{$raw_material->part_name}}
                    </td>
                    <td class="pad5 text-left" width="20%">
                      {{$raw_material->part_number}}
                    </td>
                    <td class="pad5 text-left" width="20%">
                      {{$raw_material->material}}
                    </td>
                    <td class="pad5 text-left" width="15%">
                      {{$raw_material->qty}}
                    </td>
                    <td class="pad5 text-left" width="15%">
                      {{$raw_material->getUnitDetail->unit or ''}}
                      <input type="hidden" name="unit_id" id="unit_id" value="{{$raw_material->fk_unit_id}}"
                    </td>
                  </tr>
                @endforeach
              @else
              <tr>
                <td colspan="5" class="text-center no_record pad5">
                  No record found
                </td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-8 marginT20 sp_form hide">
              <div class="row">
                <div class="col-md-2 bold fs16 text-left no_padding">Part Name</div>
                <div class="col-md-5 no_padding">
                  {!! Form::text('part_name', Input::old('part_name'), ['id'=>'part_name','class'=>'form-control inside_element']) !!}
                </div>
                <div class="col-md-5"></div>
              </div>
              <div class="clear"></div>
              <div class="row marginT10">
                <div class="col-md-2 bold fs16 text-left padT5 no_padding">Part Number</div>
                <div class="col-md-5 no_padding">
                  {!! Form::text('part_number',Input::old('part_number'), ['id'=>'part_number','class'=>'form-control inside_element']) !!}
                </div>
                <div class="col-md-5"></div>
              </div>
              <div class="clear"></div>
              <div class="row marginT10">
                <div class="col-md-2 bold fs16 text-left padT5 no_padding">Material</div>
                <div class="col-md-5 no_padding">
                  {!! Form::text('material',Input::old('material'), ['id'=>'material','class'=>'form-control inside_element']) !!}
                </div>
                <div class="col-md-5"></div>
              </div>
              <div class="clear"></div>
              <div class="row marginT10">
                <div class="col-md-2 bold fs16 text-left padT5 no_padding">Quantity</div>
                <div class="col-md-5 no_padding">
                  {!! Form::number('quantity',Input::old('quantity'), ['id'=>'quantity','class'=>'form-control inside_element','min'=>'0']) !!}
                </div>
                <div class="col-md-5"></div>
              </div>
              <div class="clear"></div>
              <div class="row marginT10">
                <div class="col-md-2 bold fs16 text-left padT5 no_padding">Unit</div>
                <div class="col-md-5 no_padding">
                  {!! Form::select('unit', $inv_units->getUnitsInDropdown(), Input::old('unit'), ['id'=>'unit','class'=>'form-control inside_element']) !!}
                </div>
                <div class="col-md-5"></div>
              </div>
              <div class="row marginT10 pull-left">
                <div class="col-md-12 no_padding">
                  <button type="button" class="btn btn-default btn_add_raw inside_element">Add</button>
                  <button type="button" class="btn btn-default btn_update_raw disabled inside_element">Update</button>
                  <button type="button" class="btn btn-danger btn_delete_raw disabled inside_element">Delete</button>
                </div>
              </div>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>
        <input type="hidden" name="data[]" id="data" value="">
        <input type="hidden" name="sku" value="{{$assign_prod->code}}">
      </form>
    </div>
  </div>
</div>
<script>
var update_field = [];
function reset(){
  $('#part_name').val('');
  $('#part_number').val('');
  $('#material').val('');
  $('#quantity').val('');
  $('#unit').val('');
}
function validate(part_name,part_number,material,quantity,unit){
  if(part_name==''){
    $.growl.error({title:"Error", message: "Please enter part name",size:'large',duration:3000});
    return false;
  }else if(part_number==''){
    $.growl.error({title:"Error", message: "Please enter part number",size:'large',duration:3000});
    return false;
  }else if(material==''){
    $.growl.error({title:"Error", message: "Please enter material",size:'large',duration:3000});
    return false;
  }else if(quantity==''){
    $.growl.error({title:"Error", message: "Please enter quantity",size:'large',duration:3000});
    return false;
  }else if($('#unit :selected').val() == ''){
    $.growl.error({title:"Error", message: "Please select unit",size:'large',duration:3000});
    return false;
  }else{
    return true;
  }
}
$('.edit_anc_btn').click(function(){
  $('.sp_form').show();
  $('.not_sp_form').hide();
  // scroll to form
  $("html, body").animate({ scrollTop: $(document).height() }, "slow");
});
$('.cancel_anc_btn').click(function(){
  //$(this).hide();
  $('.sp_form').hide();
  $('.not_sp_form').show();
});

// when click on add
$('.btn_add_raw').click(function(){
  var already_added = 0;
  // if Alredy added
  /*$('.raw_add tr').each(function() {
    if($(this).find('td:eq(1) input').val()==$('#part_number').val()){
      already_added = 1;
    }
  });
  if(already_added==1){
    $.growl.error({title:"Error", message: "Material already added",size:'large',duration:3000});
    return false;
  }*/

  // get the values
  var part_name = $('#part_name').val();
  var part_number = $('#part_number').val();
  var material = $('#material').val();
  var quantity = $('#quantity').val();
  var unit = $('#unit :selected').text();
  var unit_id = $('#unit :selected').val();
  // validation
  var validated = validate(part_name,part_number,material,quantity,unit);
  if(validated==true){
    var new_add = `
      <tr class='new_add'>
        <td class='pad5 text-left' width='30%'>`+part_name+`</td>
        <td class='pad5 text-left' width='20%'>`+part_number+`</td>
        <td class='pad5 text-left' width='20%'>`+material+`</td>
        <td class='pad5 text-left' width='15%'>`+quantity+`</td>
        <td class='pad5 text-left' width='15%'> `+unit+`<input type="hidden" name="unit_id" id="unit_id" value="`+unit_id+`"></td>
      </tr>
    `;
    if($('tbody.raw_add tr:has(td.no_record)')){
      $('tbody.raw_add tr:has(td.no_record)').remove();
    }
    $('.raw_add').append(new_add);
    // reset
    reset();
    // scroll to bottom
    /*var rowpos = $('#raw_material_list tr:last').position();
    $('#raw_material_list').scrollTop(rowpos.top);
    $('#raw_material_list').scrollTop($("#raw_material_list")[0].scrollHeight);*/

  }
});

// update button
$('.btn_update_raw').click(function(){
  // get the values
  var part_name = $('#part_name').val();
  var part_number = $('#part_number').val();
  var material = $('#material').val();
  var quantity = $('#quantity').val();
  var unit = $('#unit :selected').text();
  var unit_id = $('#unit :selected').val();
  // validation
  var validated = validate(part_name,part_number,material,quantity,unit);
  if(validated==true){
    var new_add = `
        <td class='pad5 text-left' width='30%'>`+part_name+`</td>
        <td class='pad5 text-left' width='20%'>`+part_number+`</td>
        <td class='pad5 text-left' width='20%'>`+material+`</td>
        <td class='pad5 text-left' width='15%'>`+quantity+`</td>
        <td class='pad5 text-left' width='15%'> `+unit+`<input type="hidden" name="unit_id" id="unit_id" value="`+unit_id+`"></td>
    `;

    $('.raw_add .tr_bg_selected').html(new_add);
    var id = $('.raw_add .tr_bg_selected').attr('id');
    if(id>0){
      update_field.push({'action':'update','id':id,'part_name':part_name,'part_number':part_number,'material':material,'quantity':quantity,'unit':$('#unit :selected').val()});
    }
    $('.btn_update_raw').addClass('disabled');
    $('.btn_delete_raw').addClass('disabled');
    $('.btn_add_raw ').removeClass('disabled');
    // reset
    reset();
  }
});

// add selected class
$(document).on('click','body .raw_add tr', function(){
  $('body .raw_add tr').removeClass('tr_bg_selected');
  if (!$(this).find('td').hasClass("no_record")) {
    $(this).addClass('tr_bg_selected');
    //alert($("tr.tr_bg_selected td:nth-child(2)").val());
    $('#part_name').val($(this).children().eq(0).text().trim());
    $('#part_number').val($(this).children().eq(1).text().trim());
    $('#material').val($(this).children().eq(2).text().trim());
    $('#quantity').val($(this).children().eq(3).text().trim());
    $('#unit').val($(this).children().find('#unit_id').val());
    if($('body .raw_add tr').hasClass('tr_bg_selected')){
      $('.btn_update_raw').removeClass('disabled');
      $('.btn_delete_raw').removeClass('disabled');
      $('.btn_add_raw').addClass('disabled');
    }
  }
});

//remove selected class on outside click
$(document).click(function(e){
  var selected = 0;
  $('.raw_add tr').each(function() {
    if($(this).hasClass('tr_bg_selected')){
      selected = 1;
    }
  });
  if(selected==1){
    if($(e.target).closest('body .raw_add tr').length==0 && $(e.target).closest('body .inside_element').length==0){
       $('body .raw_add tr').removeClass('tr_bg_selected');
       $('.btn_update_raw').addClass('disabled');
       $('.btn_delete_raw').addClass('disabled');
       $('.btn_add_raw').removeClass('disabled');
       reset();
    }
  }
});

// delete button click
$('.btn_delete_raw').click(function(){
  var id = $('.raw_add .tr_bg_selected').attr('id');
  $('.tr_bg_selected').remove();
  $('.btn_update_raw').addClass('disabled');
  $('.btn_delete_raw').addClass('disabled');
  $('.btn_add_raw ').removeClass('disabled');
  if(id>0){
    update_field.push({'action':'delete','id':id});
  }
  reset();
  var rowCount = $('tbody.raw_add tr').length;
  if(rowCount==0){
    $('tbody.raw_add').html("<tr><td colspan='5' class='text-center no_record'>No record found </td></tr>");
    $('.btn_update_raw').addClass('disabled');
    $('.btn_delete_raw').addClass('disabled');
    $('.btn_add_raw ').removeClass('disabled');
  }
});
$('#save_data').click(function(){
  $('.raw_add tr.new_add').each(function() {
    var part_name = $(this).children().eq(0).text().trim();
    var part_number = $(this).children().eq(1).text().trim();
    var material = $(this).children().eq(2).text().trim();
    var quantity = $(this).children().eq(3).text().trim();
    var unit_id = $(this).children().find('#unit_id').val();
    update_field.push({'action':'add','id':'0','part_name':part_name,'part_number':part_number,'material':material,'quantity':quantity,'unit':unit_id});
  });
  $("input[name='data[]']").val(JSON.stringify(update_field));
});
</script>
@endsection
