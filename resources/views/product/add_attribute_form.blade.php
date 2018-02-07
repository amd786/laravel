<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Add Attribute</h4>
</div>
<div class="modal-body">
  <div class="group-form">  
    {{ Form::open(array('url' => action('ProductController@SaveNewAttribute'))) }}
      <div class="attr_form">
          <div class="col-sm-12">
            <label for="attribute_name">Attribute Name</label>
            <input class="form-control" placeholder="Attribute Name" name="attribute_name" type="text" id="attribute_name">
          </div>
          <div class="clearfix"></div><br>
          <div class="col-sm-12">
            <label for="notes">Variables</label>
            <textarea class="form-control" rows="4" id="variables" placeholder="Paste items here, separated by either a , or |"></textarea>
          </div>
          <div class="clearfix"></div><br>
          <input type="hidden" name="class_id" id="class_id" value="{{$class_id}}">
          <div class="col-sm-12">
            <button class="btn btn-default" id="add_attr_var">Done</button>
          </div>
          <div class="clearfix"></div><br>
      </div>
    {!! Form::close() !!}
  </div>
</div>
<script>
$('#add_attr_var').click(function(){
  var variables = $('#variables').val();
  var attr_name = $('#attribute_name').val();
  var class_id = $('#class_id').val();
  var count = 1;
  var data = '';
  if(attr_name==""){
    $.growl.error({title:"Error", message: "Attribute Name field is required.",size:'large',duration:5000});
    return false;
  }
  if(variables==""){
    $.growl.error({title:"Error", message: "Variable field is required.",size:'large',duration:5000});
    return false;
  }
  if(variables.indexOf('|') > -1)
  {
    separator = "|";
  }else{
    separator = ",";
  }
  data += "<div class='table-responsive'>";
    data +="<table class='table table-hover'>";
      data +="<thead><tr><th>Attribute Name</th><th>Attribute Code</th></tr></thead>";
        data +="<tbody>";
          variables.split(/[,\/|]/).forEach(function(myString) {
              data +="<tr><td>"+myString+"<input type='hidden' name='attr_values_split["+count+"][]' value='"+myString+"'></td><td><input type='text' class='form-control attr_values_split' name='attr_values_split["+count+"][]'></td></tr>";
              count++;
          });
      data +="</tbody>";
    data +="</table>";
  data += "</div>";
  data +="<input type='hidden' name='attribute_name' value='"+attr_name+"'>";
  data +="<input type='hidden' name='separator' value='"+separator+"'>";
  data +="<input type='hidden' name='fk_product_class_id' value='"+class_id+"'>";
  data +="<input type='submit' id='save_attr' class='btn btn-default' value='Save'>";
  $('#attributes .attr_form').html(data);
  bindSubmitBtn();
});

  // perform validation before submit 
  function bindSubmitBtn(){
    $('#save_attr').click(function(e){
      $('.attr_values_split').each(function(){
        if($(this).val()==""){
          $.growl.error({title:"Error", message: "Product Code is required for all Attributes.",size:'large',duration:5000});
          e.preventDefault();
          return false;
        }
      });  
    });
  }
</script>