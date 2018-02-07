<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered">
      <thead>
        <tr>
          <th width="4%">#</th>
          <th width="10%">Product Class</th>
          <th width="10%">Product Name</th>
          <th>Variant</th>
          <th width="8%">Tolerance %</th>
          <th width="8%">Price</th>
        </tr>
      </thead>
      <tbody>
          @if(count($assign_prods)>0)
            <?php $pc_count = 1; ?>
            @foreach($assign_prods as $assign_prod)
              <tr>
                <td>{{ $pc_count }}</td>
                <td>{{ $assign_prod->ProductClass->product_class or '' }}</td>
                <td>{{ $assign_prod->product->product_name or '' }}</td>
                <td>{{ utf8_decode(strtoupper(App\Library\Functions::get_variants($assign_prod->id))) }}</td>
                <td>
                  @if(!empty($assign_prod->tolerance))
                    <div class="col-md-1">
                      <i class="fa fa-plus  <?= ($assign_prod->tolerance_sign === 1)?'green':'' ?>" aria-hidden="true"></i>
                      <i class="fa fa-minus  <?= ($assign_prod->tolerance_sign === 0)?'red':'' ?>"  aria-hidden="true"></i>
                    </div>
                    <div class="col-md-5">
                      {{ $assign_prod->tolerance }}%
                    </div>
                  @else
                    <?php $product_class = $assign_prod->getProductClassTolerance(); ?>
                    @if($product_class->tolerance !== null && $product_class->tolerance_sign !== null)
                      <div class="col-md-1">
                        <i class="fa fa-plus  <?= ($product_class->tolerance_sign === 1)?'green':'' ?>" aria-hidden="true"></i>
                        <i class="fa fa-minus  <?= ($product_class->tolerance_sign === 0)?'red':'' ?>"  aria-hidden="true"></i>
                      </div>
                      <div class="col-md-5">
                        {{ $product_class->tolerance }}%
                      </div>
                    @endif
                  @endif
                </td>
                <td class = "td_align_center">
                  <div class="retail{{ $assign_prod->id }}">
                      {{ App\Library\Functions::cal_retail_price($assign_prod->id) }}
                  </div>
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
  var holder  = $(this).closest('.product_view_all_holder').attr('id');
  if(holder=='product_view_all_holder'){
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
        $('.product_view_all_holder').html('');
        $('.product_view_all_holder').html(data);
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