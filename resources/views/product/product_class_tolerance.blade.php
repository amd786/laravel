@extends('layouts.app')

@section('content')
@section('title', 'Product Class Tolerance')
  @include('product.sub_head')
  <br>
  <div class="col-sm-7 col-sm-offset-2 padB30 text-center address-text hide">Product Class Tolerance</div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered">
            <thead>
              <tr>
                <th width="4%">#</th>
                <th width="10%">Product Class</th>
                <th width="10%">Price Tolerance</th>
                <th>Product Affected</th>
                <th width="23%">Notes</th>
                <th width="12%">Approval / Delete</th>
              </tr>
            </thead>
            <tbody>
                @if(count($prod_class)>0)
                  <?php $pc_count = 1; ?>
                  @foreach($prod_class as $prod)
                    <tr>
                      <td>{{ $pc_count }}</td>
                      <td>{{ $prod->product_class or '' }}</td>
                      <td>
                        @if($prod->tolerance !== null)
                          <div class="col-md-1">
                            <i class="fa fa-plus  <?= ($prod->tolerance_sign===1)?'green':'' ?>" aria-hidden="true"></i>
                            <i class="fa fa-minus <?= ($prod->tolerance_sign===0)?'red':'' ?>"  aria-hidden="true"></i>
                          </div>
                          ${{ $prod->tolerance or '' }}%
                        @endif
                      </td>
                      <td>
                        {{ App\Library\Functions::product_affected($prod->id) }}
                      </td>
                      <td> 
                        {{ $prod->short_description or '' }}
                      </td>
                      <td>
                        <button title="Edit" class="btn btn-default add_class_tole cursor" data-id="{{ $prod->id }}" data-url="{{ action('ProductController@ModalClassTolerance',['id'=>$prod->id]) }}"><i class="fa fa-pencil-square-o cursor" aria-hidden="true"></i></button>
                        <button class="btn approval_class_tole <?= ($prod->approved_status === 1)?'btn-success':'btn-default' ?>" data-id="{{ $prod->id }}" data-value="1" title="Approve"><i class="fa fa-check" aria-hidden="true"></i></button>
                        <a href="{{ action('ProductController@DeleteProductClass',['id'=>$prod->id]) }}" onclick="return confirm('Do you want to delete this product class?')"><button class="btn btn-default"  title="Delete"><i class="fa fa-times" aria-hidden="true"></i></button></a>
                      </td>
                    </tr>
                  <?php $pc_count++; ?>
                  @endforeach
              @else
                <tr>
                  <td colspan="6" class="text-center">No record found.</td>
                </tr>
              @endif
            </tbody>
          </table>
      </div>
      <div class="col-md-12 text-right no_padding">
        @if(count($prod_class)>0)
          {{  $prod_class->links() }}
        @endif
      </div>
    </div>
  </div>
  
  <!-- modal -->
  <div id="modal_class_tolerance" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        
      </div>
    </div>
  </div>
@endsection