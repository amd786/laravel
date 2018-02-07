@extends('layouts.app')

@section('content')
@section('title', 'Edit Attribute')
@include('product.sub_head')
<div class="row padB20">
  <!-- <div class="col-sm-6 text-right address-text">Edit Attribute Values</div> -->
  <div class="col-sm-12">
    <a href="javascript:void(0)" class="add_attr_value btn-default small_btn border1Black widthAuto round_btn padT10 padB10" data-url="{{ action('ProductController@ModalAddAttributeValue',['id'=>$attribute->id]) }}">Add Attribute Value
      <!--<div class="text-center">
        <img src="{{ url('/img/plus.png') }}">
      </div>-->
    </a>
  </div>
  <div class="add_user_txt"></div>
  <div class="clearfix"></div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Attribute Value</th>
              <th>Attribute Codes</th>
              <th>Notes</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
              @if(count($edit_attributes_values)>0)
                <?php $pc_count = 1; ?>
                @foreach($edit_attributes_values as $edit_attribute)
                  <tr>
                    <td>{{ $pc_count }}</td>
                    <td>{{ $edit_attribute->attribute_value }}</td>
                    <td>{{ $edit_attribute->attribute_code }}</td>
                    <td>{{ $edit_attribute->notes }}</td>
                    <td><a href="javascript:void(0)" class="add_attr_value" data-url="{{ action('ProductController@ModalAddAttributeValue',['id'=>$attribute->id,'value'=>$edit_attribute->id]) }}"><i class="fa fa-pencil-square fa-lg fs30"></i></a>&nbsp;<a href="{{ action('ProductController@DeleteAttrValue',['id'=>$edit_attribute->id]) }}" class="" onclick="return confirm('Do you want to delete this attribute?')"><i class="fa fa-trash-o fa-2x fs30"></i></a></td>
                  </tr>
                <?php $pc_count++; ?>
                @endforeach
            @else
              <tr>
                <td colspan="5" class="text-center">No record found.</td>
              </tr>
            @endif
          </tbody>
        </table>
    </div>
    <div class="col-md-12 text-right no_padding">
      @if(count($edit_attributes_values)>0)
        {{  $edit_attributes_values->links() }}
      @endif
    </div>
  </div>
</div>

<!-- modal -->
<div id="attr_value" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

    </div>
  </div>
</div>
@endsection
