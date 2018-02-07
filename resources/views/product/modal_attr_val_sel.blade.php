<?php
if($tmp_assign_model !=null){
  $ids = $tmp_assign_model->attributes;
  $ids_array = explode(",",$ids);
}
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">{{ ucfirst($attr->attribute_name) }} Attribute</h4>
</div>
<div class="modal-body">
  <div class="group-form">
    <div class="col-md-12">
      <div class="col-md-12 no_padding attr_box">
        @if(count($attr_val)>0)
            <div class="col-sm-6 attr_box_text">
              <div class="col-sm-9">Attribute</div>
              <!--<div class="col-sm-1">All</div>-->
            </div>
            <div class="col-sm-6 attr_box_text">
              <div class="col-sm-9">Attribute</div>
              <!--<div class="col-sm-1">All</div>-->
            </div>
            <div class="clearfix"></div><hr style="margin:0">
            @foreach($attr_val as $attr_val)
              <div class="col-sm-6 attr_box_text">
                <div class="col-sm-9">{{ $attr_val->attribute_value }}</div>
                <div class="col-sm-1">
                  <div class="checkbox abc-checkbox abc-checkbox-success">
                    <input type="checkbox" class="attr_checkbox" id="{{ $attr_val->id }}" data-attrid="{{ $attr->id }}" value="{{ $attr_val->id }}" <?php echo (in_array($attr_val->id,$ids_array)?'checked':'') ?>>
                    <label for="{{ $attr_val->id }}"></label>
                  </div>
                </div>
              </div>
            @endforeach
        @else
          <div class="col-sm-12 attr_box_text padB8">No attributes values found.</div>
        @endif
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div><br>
      <div class="col-md-12 text-center">
        <input type="hidden" id="pp_attr_id" value="{{ $attr->id }}">
        <button class="btn btn-default btn-lg assign_modal_btn" data-dismiss="modal">Done</button>
      </div>
      <div class="clearfix"></div><br>
    </div>
    <div class="clearfix"></div>
  </div>
</div>

<script>
  // save all checked items in db
  $('.assign_modal_btn').on('click',function(){
    var attr_id = $('#pp_attr_id').val();
    //var attr_id = $('.attr_checkbox:checked').attr('data-attrid');
    var ids = [];
    var count_checked=0;
    $('.attr_checkbox:checked').each(function(){
      //console.log($(this).attr('id'));
      count_checked++;
      ids.push($(this).val());
    });
    // this is for add active class
    var count_check=0;
    $('.attr_checkbox').each(function(){
      count_check++;
    });
    if(count_checked > 0 && count_checked != count_check){
      $('#sel'+attr_id).addClass('sel_check_act');
      $('#'+attr_id).removeAttr('checked','checked');
    }
    if(count_checked == 0 && count_checked != count_check){
      $('#sel'+attr_id).removeClass('sel_check_act');
      $('#'+attr_id).removeAttr('checked','checked');
    }
    if(count_checked == count_check){
      $('#sel'+attr_id).removeClass('sel_check_act');
      $('#'+attr_id).attr('checked','checked');
    }
    // end here
    //send all selected values in ajax
    $.ajax({
      url:baseURL+'/attr-val-checkbox',
      data:{ids:ids},
      success:function(result){

      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Something went wrong.')
        console.log(textStatus, errorThrown);
      }
    });
  });

  // delete all unchecked items from db
  $('.attr_checkbox').on('click',function(){
    if($(this).is(":not(:checked)")){
      var id = $(this).val();
      $.ajax({
        url:baseURL+'/attr-val-uncheck',
        data:{id:id},
        success:function(result){

        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert('Something went wrong.')
          console.log(textStatus, errorThrown);
        }
      });
    }
  });
</script>
