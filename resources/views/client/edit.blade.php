@extends('layouts.app')

@section('content')
@section('title', 'User Details')
@include('user.sub_head')
<div class="panel panel-default" style="border: 0px solid #000">
    <div class="panel-body">
      <div class="col-md-12">
        <div class="col-md-1 no_padding">
          <span class="fs18"><img src="{{ url('/img/dots.png') }}" class="padR5">Actions&nbsp;</span>
        </div>
        <div class="col-md-2 no_padding">
          <a href="{{ action('ClientController@EditDetails',['id'=>$model->id]) }}" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10">Edit Details
          </a>
        </div>
        <div class="col-md-2 no_padding">
          <a href="{{ action('ClientController@DisableUser',['id'=>$model->id]) }}" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" onclick="return confirm('Are you sure to {{$model->status==1 ? 'disable' : 'enable'}} this user?')"> @if($model->status==1) {{'Disable Account'}} @else {{'Enable Account'}}  @endif
          </a>
        </div>
        <div class="col-md-2 no_padding">
          <a href="{{ action('ClientController@DeleteUser',['id'=>$model->id]) }}" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" onclick="return confirm('Are you sure to delete this user?')">Delete Account
          </a>
        </div>
      </div>
      <div class="col-md-12">
        <hr class="group_by"></hr>
      </div>
      <div class="col-md-12">
        <div class="col-md-6">
          <div class="col-md-12 fs22 marginB10">
            <div class="col-md-4">
              <p class="no_margin">Role:</p>
            </div>
            <div class="col-md-8 text-right">
              <p class="no_margin">{{$model->getRoleDetail->role_name}}</p>
            </div>
          </div>
          <div class="col-md-12 fs22 marginB10">
            <div class="col-md-4">
              <p class="no_margin">First Name:</p>
            </div>
            <div class="col-md-8 text-right">
              <p class="no_margin">{{$model->first_name}}</p>
            </div>
          </div>
          <div class="col-md-12 fs22 marginB10">
            <div class="col-md-4">
              <p class="no_margin">Last Name:</p>
            </div>
            <div class="col-md-8 text-right">
              <p class="no_margin">{{$model->last_name}}</p>
            </div>
          </div>
          <div class="col-md-12 fs22">
            <div class="col-md-4">
              <p class="no_margin">Email:</p>
            </div>
            <div class="col-md-8 text-right">
              <p class="no_margin">{{$model->email}}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="col-md-12 fs22">
            <div class="col-md-4">
              <p class="no_margin">Password</p>
            </div>
            <div class="col-md-8 text-right">
              <p class="no_margin">***********</p>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="reset_password" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['url'=>action('UserController@ResetPassword',['id'=>$model->id])]) }}
      <div class="modal-header no-border">
        <h2 class="modal-title text-center">Reset Password</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 fs18">
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-4 text-left">
                  {{ Form::label('new_password','New Password') }}
                </div>
                <div class="col-sm-8">
                  {{ Form::password('new_password',['class'=>'form-control','placeholder'=>'New Password']) }}
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-4 text-left">
                  {{ Form::label('password_confirm','Confirm Password') }}
                </div>
                <div class="col-sm-8">
                  {{ Form::password('password_confirm',['class'=>'form-control','placeholder'=>'Confirm Password']) }}
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer no-border marginT20">
        <div class="col-md-12">
          <div class="col-md-6">
          </div>
          <div class="col-md-3">
            {!! Form::submit('Save',array('class'=>'btn-default small_btn border1Black width100 round_btn padT10 padB10','id'=>'save_password')) !!}
          </div>
          <div class="col-md-3">
            <button type="button" class="btn-default small_btn border1Black width100 round_btn padT10 padB10" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
@endsection
