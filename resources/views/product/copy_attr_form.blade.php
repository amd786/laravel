<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Copy Attribute</h4>
</div>
<div class="modal-body">
  <div class="group-form">  
    {{ Form::open(array('url' => action('ProductController@CopyAttributeSave',['id'=>$attribute->id]))) }}
      <div class="attr_form">
          <div class="col-sm-6">
            <label for="attribute_name">Copy "{{$attribute->attribute_name}}" to:</label>
          </div>
          <div class="col-sm-6">
            {!! Form::select('product_class', $attribute->getCategoryInDropdown(), Input::old('product_class'), ['placeholder' => 'Select Product Class...','class'=>'form-control']) !!}
          </div>
          <div class="clearfix"></div><br>
          <div class="col-sm-12">
            <!-- <input type="hidden" name="copy_attr" value="{{$attribute->id}}"> -->
            <button class="btn btn-default " id="add_attr_var">Save</button>
            <button class="btn btn-default" data-dismiss="modal" >Cancel</button>
          </div>
          <div class="clearfix"></div><br>
      </div>
    {!! Form::close() !!}
  </div>
</div>