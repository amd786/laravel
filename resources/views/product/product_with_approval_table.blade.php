<div class="table-responsive">
  <table class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th width="4%">#</th>
        <th width="10%">Product Name</th>
        <th>Variant</th>
        <th width="8%">Price</th>
        <th width="12%">Individual Tolerance %</th>
        <th width="8%">Retail Price</th>
        <th width="15%">Approved</th>
        <th width="5%" class="hide">Delete</th>
      </tr>
    </thead>
    <tbody>
        @if(count($assign_prods)>0)
          <?php $pc_count = 1; ?>
          @foreach($assign_prods as $assign_prod)
            <tr>
              <td>{{ $pc_count }}</td>
              <td>{{ $assign_prod->product->product_name or '' }}</td>
              <td>
                {{ utf8_decode(strtoupper(App\Library\Functions::get_variants($assign_prod->id))) }}
              </td>
              <td class = "td_align_center">
                @if(!empty($assign_prod->price))
                  ${{ $assign_prod->price }}
                @else
                  <!-- <img src="{{ url('/img/grey-plus.png') }}" class="add_price_modal"  data-id="{{ $assign_prod->id }}" data-url="{{ action('ProductController@ModalWithoutApproval',['id'=>$assign_prod->id]) }}"> -->
                @endif
              </td>
              <td>
                @if(!empty($assign_prod->tolerance))
                  <div class="col-md-1">
                    <i class="fa fa-plus  <?= ($assign_prod->tolerance_sign === 1)?'green':'' ?>" data-value="1" data-id="{{ $assign_prod->id }}" aria-hidden="true"></i>
                    <i class="fa fa-minus  <?= ($assign_prod->tolerance_sign === 0)?'red':'' ?>" data-value="0" data-id="{{ $assign_prod->id }}"  aria-hidden="true"></i>
                  </div>
                  <div class="col-md-5">
                    {{ $assign_prod->tolerance }}%
                  </div>
                @else
                  <!-- <img src="{{ url('/img/grey-plus.png') }}" class="add_price_modal cursor" data-id="{{ $assign_prod->id }}" data-url="{{ action('ProductController@ModalWithoutApproval',['id'=>$assign_prod->id]) }}"> -->
                @endif
              </td>
              <td class = "td_align_center">
                <div class="retail{{ $assign_prod->id }}">
                  @if($assign_prod->price !=null && $assign_prod->tolerance !=null && $assign_prod->tolerance_sign !== null)
                    {{ App\Library\Functions::cal_retail_price($assign_prod->id) }}
                  @endif
              </div>
              </td>
              <td>
                <span>Approved on {{ App\Library\Functions::date_format($assign_prod->approved_date) }}</span>
                <span>by <b><?= (($assign_prod->user !== null)?$assign_prod->user->fullName():'No Name'); ?></b></span>
              </td>
              <td class="hide">
                <a href="javascript:;" class="delete_assign_val cursor" data-return="{{ action('ProductController@ProductsWithApproval') }}" data-assignid="{{ $assign_prod->id }}"><img src="{{ url('/img/cross.png') }}" alt="Delete"></a>
              </td>
            </tr>
          <?php $pc_count++; ?>
          @endforeach
      @else
        <tr>
          <td colspan="8" class="text-center">No record found.</td>
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
<script>
$('.pagination a').on('click', function(e){
  e.preventDefault();
  var holder  = $(this).closest('.products_with_approval_holder').attr('id');
  if(holder=='products_with_approval_holder'){
    var url = $(this).attr('href');
    var product_class = $('.product_class option:selected').val();
    var product_name = $('.product_name option:selected').val();
    if(product_class==''){
      product_class = 0;
    }
    if(product_name==''){
      product_name = 0;
    }
    if(product_name>0 || product_class>0){
      if(product_class>0 && product_name==0){
        url = url+'&class='+product_class;
      }else if(product_class==00 && product_name>0){
        url = url+'&product='+product_name;
      }else{
        url = url+'&class='+product_class+'&product='+product_name;
      }
    }
    $.ajax({
      url :url,
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(data){
        $("body").removeClass("loading");
        $('.products_with_approval_holder').html('');
        $('.products_with_approval_holder').html(data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  }
});
</script>