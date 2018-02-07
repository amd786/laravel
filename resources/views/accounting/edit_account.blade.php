@if(count($account_detail)>0)
<div class="row">
  <div class="col-md-12 fs18">
    <div id="full_form">
      <input type="hidden" name="account_detail_id" value="{{$account_detail->id}}">
      <div class="form-group padT10">
        <div class="col-sm-12">
          <div class="col-sm-4 text-left">
            {{ Form::label('name','Name') }}
          </div>
          <div class="col-sm-8">
            {{ Form::text('name',!empty($account_detail->name) ? $account_detail->name : '',['class'=>'form-control']) }}
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="form-group padT10">
        <div class="col-sm-12">
          <div class="col-sm-4 text-left">
            {{ Form::label('street_name','Street Name') }}
          </div>
          <div class="col-sm-8">
            {{ Form::text('street_name',!empty($account_detail->street_name) ? $account_detail->street_name : '',['class'=>'form-control']) }}
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="form-group padT10">
        <div class="col-sm-12">
          <div class="col-sm-4 text-left">
            {{ Form::label('city','City') }}
          </div>
          <div class="col-sm-8">
            {{ Form::text('city',!empty($account_detail->city) ? $account_detail->city : '',['class'=>'form-control']) }}
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="form-group padT10">
        <div class="col-sm-12">
          <div class="col-sm-4 text-left">
            {{ Form::label('country','Country') }}
          </div>
          <div class="col-sm-8">
            {{ Form::text('country',!empty($account_detail->country) ? $account_detail->country : '',['class'=>'form-control']) }}
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="form-group padT10">
        <div class="col-sm-12">
          <div class="col-sm-4 text-left">
            {{ Form::label('postal_code','Postal Code') }}
          </div>
          <div class="col-sm-8">
            {{ Form::text('postal_code',!empty($account_detail->postal_code) ? $account_detail->postal_code : '',['class'=>'form-control']) }}
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endif
