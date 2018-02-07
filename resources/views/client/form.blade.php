<div class="panel-body fs16">
  {{ Form::open(['url'=>action('ClientController@SaveClient')]) }}
  <input type="hidden" name="user_id" value="{{isset($data->id) ? $data->id : 0}}">
  <div class="col-md-12">
    <hr class="group_by"></hr>
  </div>
  <div class="row fs20">
    <div class="col-md-12">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-4 no_padding">
              {!! Form::label('fk_role_id','Role:') !!}
            </div>
            <div class="col-md-8 no_padding">
              {!! Form::select('fk_role_id', $user_model->getChildClientRolesInDropdown(), isset($data->fk_role_id) ? $data->fk_role_id : Input::old('fk_role_id'), ['placeholder' => 'Select option...','class'=>'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="row padT20">
          <div class="col-md-12">
            <div class="col-md-4 no_padding">
              {!! Form::label('first_name','First Name:') !!}
            </div>
            <div class="col-md-8 no_padding">
              {!! Form::text('first_name',isset($data->first_name) ? $data->first_name : Input::old('first_name'),['class'=>'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="row padT20">
          <div class="col-md-12">
            <div class="col-md-4 no_padding">
              {!! Form::label('last_name','Last Name:') !!}
            </div>
            <div class="col-md-8 no_padding">
              {!! Form::text('last_name',isset($data->last_name) ? $data->last_name : Input::old('last_name'),['class'=>'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="row padT20">
          <div class="col-md-12">
            <div class="col-md-4 no_padding">
              {!! Form::label('email','Email:') !!}
            </div>
            <div class="col-md-8 no_padding">
              {!! Form::text('email',isset($data->email) ? $data->email : Input::old('email'),['class'=>'form-control']) !!}
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-4 no_padding">
              {!! Form::label('new_password','New Password') !!}
            </div>
            <div class="col-md-8 no_padding">
              {!! Form::password('new_password',['class'=>'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="row padT20">
          <div class="col-md-12">
            <div class="col-md-4 no_padding">
              {!! Form::label('password_confirm','Confirm Password') !!}
            </div>
            <div class="col-md-8 no_padding">
              {!! Form::password('password_confirm',['class'=>'form-control']) !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <hr class="group_by"></hr>
  </div>
  <div class="col-sm-12 text-center padT20">
    @if(isset($data->id) && $data->id>0)
    <div class="col-md-6 text-right">
      <input type="submit" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" value="Confirm Changes"/>
    </div>
    <div class="col-md-6 text-left">
      <a href="{{ action('ClientController@EditUser',['id'=>$data->id]) }}" class=""><button type="button" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10">Cancel</button></a>
    </div>
    @else
    <button type="submit" class="save_btn"><img src="{{ url('/img/save_img.png')}}" class="cursor"></button>
    @endif
  </div>
  <div class="clearfix"></div>
  {{ Form::close() }}
</div>
