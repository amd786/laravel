@extends('layouts.app')

@section('content')
@section('title', 'Attributes')
@include('product.sub_head')

@if(count($p_classes)>0)
  <div class="panel-group" id="accordion">
    <?php $loop_count=1; ?>
    @foreach($p_classes as $p_class)
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$p_class->id}}" class="bold">- {{$p_class->product_class}}</a>
        </h4>
      </div>
      <div id="collapse{{$p_class->id}}" class="panel-collapse collapse <?= ($loop_count==1)?'in':''; ?>">
        <?php $loop_count++;  $attributes = App\Models\Attribute::where([['status',1],['fk_product_class_id',$p_class->id]])->orderBy('sort_order')->Paginate(20); ?>
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th width="3%">#</th>
                        <th width="14%">Attribute Name</th>
                        <th>Variables</th>
                        <th width="10%">Sort Order</th>
                        <th width="13%">Edit , Delete , Copy</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(count($attributes)>0)
                          <?php $pc_count = 1; ?>
                          @foreach($attributes as $attribute)
                            <tr>
                              <td>{{ $pc_count }}</td>
                              <td>{{ $attribute->attribute_name }}</td>
                              <td>
                                <?php
                                  $att_value = [];
                                  foreach($attribute->AttributeValues as $value){
                                    $att_value[] = $value->attribute_value." ";
                                  }
                                  echo $all_attr_value = implode($attribute->separator,$att_value);
                                ?>
                              </td>
                              <td><input class="form-control sort_order input-sm" name="sort_order" type="text" data-id="{{$attribute->id}}" value="{{$attribute->sort_order}}"></td>
                              <td><a href="{{ action('ProductController@EditAttribute',['id'=>$attribute->id]) }}"><i class="fa fa-pencil-square fa-lg fs30"></i></a>&nbsp;&nbsp;<a href="{{ action('ProductController@DeleteAttribute',['id'=>$attribute->id]) }}" class="" onclick="return confirm('Do you want to delete this attribute?')"><i class="fa fa-trash-o fa-2x fs30"></i></a>&nbsp;&nbsp;<a href="javascript:;" class="copy_attr" data-url="{{ action('ProductController@CopyAttribute',['id'=>$attribute->id]) }}"><i class="fa fa-copy fa-2x"></i></a></td>

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
                @if(count($attributes)>0)
                  {{  $attributes->links() }}
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-1 col-sm-offset-11">
              <a href="javascript:void(0)" class="add_attribute" data-url="{{ action('ProductController@ModalAddAttribute',['class_id'=>$p_class->id]) }}">
                <div class="text-center">
                  <img src="{{ url('/img/grey-plus.png') }}">
                </div>
                <div class="add_user_txt"></div>
              </a>
            </div>
            <div class="clearfix"></div>
          </div>
        </div> <!-- end panel body -->
      </div>
    </div>
    @endforeach
  </div>
@else
  No product class found
@endif


<!-- modal -->
<div id="attributes" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

    </div>
  </div>
</div>

<!-- copy modal -->
<div id="copy_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

    </div>
  </div>
</div>
@include('product.sub_foot')
@endsection
