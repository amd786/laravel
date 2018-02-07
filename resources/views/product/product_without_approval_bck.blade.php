@extends('layouts.app')

@section('content')
@section('title', 'Product Without Price')
  @include('product.sub_head')
  <div class="col-sm-7 col-sm-offset-2 padB30 text-center address-text hide">Product Without Price</div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered">
            <thead>
              <tr>
                <th width="6%">
                  <div class="checkbox abc-checkbox abc-checkbox-success abc-checkbox-circle" style="margin-left: 42px;margin-bottom:0px !important;">
                    <input type="checkbox" name="products_all[]" id="checkbox_all" value="1" onClick="select_all(this,'checkboxes')"> 
                    <label for="checkbox_all"></label>
                  </div>
                  <!-- Select All -->
                </th>
                <th width="4%">#</th>
                <th  width="10%">Product Name</th>
                <th>Variant</th>
                <th width="7%">Price</th>
                <th width="8%">Individual Tolerance %</th>
                <th width="7%">Retail Price</th>
                <th width="12%">Edit & Approve / Disapprove</th>
              </tr>
            </thead>
            <tbody>
                @if(count($assign_prods)>0)
                  <?php $pc_count = 1; ?>
                  @foreach($assign_prods as $assign_prod)
                    <tr>
                      <td>
                        <div class="checkbox abc-checkbox abc-checkbox-success abc-checkbox-circle" style="margin-left: 42px;">
                          <input type="checkbox" name="products[]" class="checkboxes" id="checkbox{{$pc_count}}" value="1"> 
                          <label for="checkbox{{$pc_count}}" class=""></label>
                        </div>
                      </td>
                      <td>{{ $pc_count }}</td>
                      <td>{{ $assign_prod->product->product_name or '' }}</td>
                      <td>{{ strtoupper(App\Library\Functions::get_variants($assign_prod->id)) }}</td>
                      <td class = "td_align_center">
                        @if(!empty($assign_prod->price))
                          $<a href="#" class="editable" id="price" data-type="text" data-pk="{{$assign_prod->id}}" data-url="{{ url('/')}}/edit-price" >{{ $assign_prod->price }}</a>
                        @else 
                          <!-- <img src="{{ url('/img/grey-plus.png') }}" class="add_price_modal"  data-id="{{ $assign_prod->id }}" data-url="{{ action('ProductController@ModalWithoutApproval',['id'=>$assign_prod->id]) }}"> -->
                          <a href="#" class="editable" id="price" data-type="text" data-pk="{{$assign_prod->id}}" data-url="{{ url('/')}}/edit-price" ></a>
                        @endif 
                      </td>
                      <td>
                        @if(!empty($assign_prod->tolerance))
                          <div class="col-md-1">
                            <i class="fa fa-plus change_tol_sign cursor <?= ($assign_prod->tolerance_sign === 1)?'green':'' ?>" data-value="1" data-id="{{ $assign_prod->id }}" aria-hidden="true"></i>
                            <i class="fa fa-minus change_tol_sign cursor <?= ($assign_prod->tolerance_sign === 0)?'red':'' ?>" data-value="0" data-id="{{ $assign_prod->id }}"  aria-hidden="true"></i>
                          </div>
                          <div class="col-md-5">
                            <a href="#" class="editable" id="tolerance" data-type="text" data-pk="{{$assign_prod->id}}" data-url="{{ url('/')}}/edit-price" >{{ $assign_prod->tolerance }}</a>%
                          </div>
                        @else 
                        <a href="#" class="editable" id="tolerance" data-type="text" data-pk="{{$assign_prod->id}}" data-url="{{ url('/')}}/edit-price" ></a>
                          <!-- <img src="{{ url('/img/grey-plus.png') }}" class="add_price_modal cursor" data-id="{{ $assign_prod->id }}" data-url="{{ action('ProductController@ModalWithoutApproval',['id'=>$assign_prod->id]) }}"> -->
                        @endif 
                      </td>
                      <td class = "td_align_center">
                        <div class="retail{{ $assign_prod->id }}">
                          @if($assign_prod->price !=null)
                            {{ App\Library\Functions::cal_retail_price($assign_prod->id) }}
                          @endif
                      </div>
                      </td>
                      <td>
                        <button title="Edit" class="btn btn-default add_price_modal cursor" data-id="{{ $assign_prod->id }}" data-url="{{ action('ProductController@ModalWithoutApproval',['id'=>$assign_prod->id]) }}"><i class="fa fa-pencil-square-o cursor" aria-hidden="true"></i></button>
                        <button class="btn btn-default approval_price" data-id="{{ $assign_prod->id }}" data-value="1" title="Approve"><i class="fa fa-check" aria-hidden="true"></i></button>
                        <button class="btn approval_price <?= ($assign_prod->approved_status === 0)?'btn-danger':'btn-default' ?>" data-id="{{ $assign_prod->id }}" data-value="0" title="Dis Approve"><i class="fa fa-times" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  <?php $pc_count++; ?>
                  @endforeach
              @else
                <tr>
                  <td colspan="7" class="text-center">No record found.</td>
                </tr>
              @endif
            </tbody>
          </table>
      </div>
      <div class="col-md-12 text-right no_padding">
        @if(count($assign_prods)>0)
          {{  $assign_prods->links() }}
        @endif
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
        $('.editable').editable();
    });
  </script>
@endsection