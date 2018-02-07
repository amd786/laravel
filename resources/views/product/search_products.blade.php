
    @if(count($assign_prods)>0)
    @foreach($assign_prods as $assign_prod)
      <div class="col-md-12 text-center padB8 fs20">{{ strtoupper($assign_prod->code) }} <a href="{{ action('ProductController@ViewAllDetails',['id'=>$assign_prod->id]) }}"><i class="fa fa-angle-double-right fs23" aria-hidden="true"></i></a></div>
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
    @endforeach
    <div class="col-md-12">
      <div class="col-md-2 no_padding">
        <div class="col-sm-12 no_padding">
          <div class="col-sm-5 no_padding">
            <input type="number" name="page_no" id="page_no" class="form-control" min="1" placeholder="Page No.">
          </div>
          <div class="col-sm-5">
            <button class="btn page_goto" name="page_goto" id="page_goto">Go To Page</button>
          </div>
          <div class="col-sm-2">
          </div>
        </div>
      </div>
      <div class="col-md-10">
        <div class="pull-right">{{ $assign_prods->render() }}</div>
      </div>
    </div>
    @else
      <div class="col-sm-11 text-center bold fs30">No Product Available.</div>
    @endif

  <script>
  function getData(url,page_no=0){
    var class_id = $('.fk_product_class').val();
    var p_id = $('.product').val();
    var show_order = $('.show_order').val();
    var has_bom = $('.has_bom').val();
    var search_text = $('#search_text').val();
    var url = url+'&p_id='+p_id+'&class_id='+class_id+'&show_order='+show_order+'&has_bom='+has_bom+'&search='+search_text;
    $.ajax({
      url :url,
      beforeSend:function(){
        $("body").addClass("loading");
      },
      success:function(data){
        $("body").removeClass("loading");
        $('.block_container').html('');
        $('.block_container').html(data);
        if(page_no>0){
          $('#page_no').val(page_no);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $("body").removeClass("loading");
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  }
  $('#page_goto').click(function(){
    var page_no = $('#page_no').val();
    var url = '<?= action('AjaxController@SearchProducts')?>'+'?page='+page_no;
    getData(url,page_no);
  });
  $('.pagination a').on('click', function(e){
      e.preventDefault();
      var url = $(this).attr('href');
      getData(url);
  });
  </script>
