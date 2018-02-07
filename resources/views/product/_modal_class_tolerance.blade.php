<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">Add Tolerance</h4>
</div>
<div class="modal-body">
  <div class="group-form">
      {!! Form::model($prod_class,['url'=>action('ProductController@SaveClassTolerance',['id'=>$prod_class->id])]) !!}
      <div class="col-sm-12">
        {!! Form::label('tolerance','Individual Tolerance') !!}
        {!! Form::text('tolerance',null,['class'=>'form-control','placeholder'=>'Individual Tolerance']) !!}
      </div>
      <div class="clearfix"></div><br>
      <div class="col-sm-12">
        <div class="col-sm-3 no_padding">
          <div class="radio abc-radio abc-radio-success">
            <input type="radio" id="tolerance1" class="" name="tolerance_sign" value="1" <?php echo ($prod_class->tolerance_sign === 1)?'checked':'' ?>>
            <label for="tolerance1">Mark up %</label>
          </div>
        </div>
        <div class="col-sm-3 no_padding">
          <div class="radio abc-radio abc-radio-danger">
            <input type="radio" id="tolerance2" class="" name="tolerance_sign" value="0" <?php echo ($prod_class->tolerance_sign === 0)?'checked':'' ?>>
            <label for="tolerance2">Discount %</label>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <br>
      <div class="col-sm-12">
        {!! Form::label('short_description','Notes') !!}
        {!! Form::text('short_description',null,['class'=>'form-control','placeholder'=>'Notes']) !!}
      </div>
      <div class="clearfix"></div>
      <br>
      <div class="col-sm-12 text-center">
        <button type="submit" class="save_btn"><img src="{{ url('/img/save_img.png')}}" class="cursor"></button>
      </div>
      <div class="clearfix"></div>
      {!! Form::close() !!}
  </div>
</div>
