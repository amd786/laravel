@extends('layouts.app')

@section('content')
@section('title', $title)
@include('accounting.sub_head')
<div class="container">
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <a href="" class="btn-default small_btn border1Black widthAuto round_btn padT10 padB10" data-toggle="modal" data-target="#add_category">Add New Category
        </a>
        @if(count($categories)>0)
          @foreach($categories as $category)
          <h3 class="padT20">{{ucwords($category->category)}}
            <span class="pull-right"> <img src="{{ url('/img/grey-plus.png') }}" class="cursor open_add_account" data-toggle="modal" data-target="#add_account" data-id="{{$category->id}}"></span></h3>
          <div class="table-responsive">
            <table class="table-bordered no-table-bg fs16 width100">
              <thead>
                <tr>
                  <th width="20%">
                    Name
                  </th>
                  <th width="25%">
                    Address
                  </th>
                  <th width="15%">
                    City
                  </th>
                  <th width="15%">
                    Country
                  </th>
                  <th width="15%">
                    Postal Code
                  </th>
                  <th width="10%">
                    Actions
                  </th>
                </tr>
              </thead>
            <tbody>
            @if(count($category->AccAccounts)>0)
              @foreach($category->AccAccounts as $account)
              <tr>
                <td class="pad8 text-left">
                  @if($account->fk_supplier_id>0)
                  {{ucwords($account->getSupplierDetail->supplier_name)}}
                  @elseif($account->fk_company_id>0)
                  {{ucwords($account->getCompanyDetail->company_name)}}
                  @else
                  {{ucwords($account->getAccountDetail->name)}}
                  @endif
                </td>
                <td class="pad8 text-left">
                  @if($account->fk_supplier_id>0)
                  {{ucwords($account->getSupplierDetail->street_name)}}
                  @elseif($account->fk_company_id>0)
                  {{ucwords($account->getCompanyDetail->street_name)}}
                  @else
                  {{ucwords($account->getAccountDetail->street_name)}}
                  @endif
                </td>
                <td class="pad8 text-left">
                  @if($account->fk_supplier_id>0)
                  {{ucwords($account->getSupplierDetail->city)}}
                  @elseif($account->fk_company_id>0)
                  {{ucwords($account->getCompanyDetail->city)}}
                  @else
                  {{ucwords($account->getAccountDetail->city)}}
                  @endif
                </td>
                <td class="pad8 text-left">
                  @if($account->fk_supplier_id>0)
                  {{ucwords($account->getSupplierDetail->country)}}
                  @elseif($account->fk_company_id>0)
                  {{ucwords($account->getCompanyDetail->country)}}
                  @else
                  {{ucwords($account->getAccountDetail->country)}}
                  @endif
                </td>
                <td class="pad8 text-left">
                  @if($account->fk_supplier_id>0)
                  {{ucwords($account->getSupplierDetail->postal_code)}}
                  @elseif($account->fk_company_id>0)
                  {{ucwords($account->getCompanyDetail->postal_code)}}
                  @else
                  {{ucwords($account->getAccountDetail->postal_code)}}
                  @endif
                </td>
                <td>
                  <div class="col-sm-12">
                    @if($account->fk_supplier_id==NULL && $account->fk_company_id==NULL)
                    <a href="" class="colorDarkGrey edit_account" data-id="{{$account->id}}" title="Edit Account"><i class="fa fa-edit" aria-hidden="true"></i></a>
                    @endif
                    <a href="{{action('AccountingController@DeleteAccount',['id'=>$account->id])}}" class="colorDarkGrey" title="Delete Account"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  </div>
                </td>
              </tr>
              @endforeach
            @else
            <tr>
              <td colspan="6" class="text-center pad5">No record found.</td>
            </tr>
            @endif
            </tbody>
          </table>
        </div>
        @endforeach
        @else
        <div class="table-responsive padT30">
          <table class="table-bordered no-table-bg fs16 width100">
            <thead>
              <tr>
                <th width="20%">
                  Name
                </th>
                <th width="20%">
                  Address
                </th>
                <th width="15%">
                  City
                </th>
                <th width="20%">
                  Country
                </th>
                <th width="25%">
                  Postal Code
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="5" class="text-center pad5">No record found.</td>
              </tr>
            </tbody>
          </table>
        </div>
        @endif
        <div class="col-md-12 text-right no_padding">
          @if(count($categories)>0)
            {{  $categories->render() }}
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
<!-- add category modal-->
<div class="modal fade" id="add_category" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['url'=>action('AccountingController@SaveAccountCategory')]) }}
      <input type="hidden" name="type" value="{{$type}}">
      <div class="modal-header no-border">
        <h2 class="modal-title text-center">Add Category</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 fs18">
            <div class="form-group padT10">
              <div class="col-sm-12">
                <div class="col-sm-4 text-left">
                  {{ Form::label('category','Category Name') }}
                </div>
                <div class="col-sm-8">
                  {{ Form::text('category',null,['class'=>'form-control']) }}
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
            {!! Form::submit('Save',array('class'=>'btn-default small_btn border1Black width100 round_btn padT10 padB10')) !!}
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
<!-- add account modal-->
<div class="modal fade" id="add_account" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['url'=>action('AccountingController@SaveAccount'),'id'=>'add_account_form']) }}
      <input type="hidden" name="type" value="{{$type}}">
      <input type="hidden" name="fk_account_category_id" id= "fk_account_category_id" value="">
      <input type="hidden" name="form_type" id= "form_type" value="">
      <div class="modal-header no-border">
        <h2 class="modal-title text-center">Add Account</h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 fs18">
            @if($type==1)
            <div class="form-group padT10" id="select_supplier_form">
              <div class="col-sm-12">
                <div class="col-sm-4 text-left">
                  {{ Form::label('fk_supplier_id','Select Supplier') }}
                </div>
                <div class="col-sm-8">
                  {{ Form::select('fk_supplier_id', $accounts_model->getSuppliersInDropdown(), Input::old('fk_supplier_id'), ['class'=>'form-control','id'=>'fk_supplier_id','placeholder'=>'Select option...']) }}
                </div>
              </div>
            </div>
            @elseif($type==2)
            <div class="form-group padT10" id="select_company_form">
              <div class="col-sm-12">
                <div class="col-sm-4 text-left">
                  {{ Form::label('fk_company_id','Select Company') }}
                </div>
                <div class="col-sm-8">
                  {{ Form::select('fk_company_id', $accounts_model->getCompaniesInDropdown(), Input::old('fk_company_id'), ['class'=>'form-control','id'=>'fk_company_id','placeholder'=>'Select option...']) }}
                </div>
              </div>
            </div>
            @endif
            <div class="clearfix"></div>
            <div class="col-md-12 padT20 padB20">
              <div class="col-md-5">
                <hr class="group_by"></hr>
              </div>
              <div class="col-md-2 text-center">Or
              </div>
              <div class="col-md-5">
                <hr class="group_by"></hr>
              </div>
            </div>
            <div id="full_form">
              <div class="form-group padT10">
                <div class="col-sm-12">
                  <div class="col-sm-4 text-left">
                    {{ Form::label('name','Name') }}
                  </div>
                  <div class="col-sm-8">
                    {{ Form::text('name',null,['class'=>'form-control']) }}
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
                    {{ Form::text('street_name',null,['class'=>'form-control']) }}
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
                    {{ Form::text('city',null,['class'=>'form-control']) }}
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
                    {{ Form::text('country',null,['class'=>'form-control']) }}
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
                    {{ Form::text('postal_code',null,['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer no-border marginT20">
        <div class="col-md-12">
          <div class="col-md-6">
          </div>
          <div class="col-md-3">
            {!! Form::submit('Save',array('class'=>'btn-default small_btn border1Black width100 round_btn padT10 padB10')) !!}
          </div>
          <div class="col-md-3">
            <button type="button" class="btn-default small_btn border1Black width100 round_btn padT10 padB10" data-dismiss="modal" id="cancel_add_account">Cancel</button>
          </div>
        </div>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
<!-- edit account modal-->
<div class="modal fade" id="edit_account_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      {{ Form::open(['url'=>action('AccountingController@SaveEditAccount')]) }}
      <div class="modal-header no-border">
        <h2 class="modal-title text-center">Edit Account</h2>
      </div>
      <div class="modal-body" id="edit_account_body">

      </div>
      <div class="modal-footer no-border marginT20">
        <div class="col-md-12">
          <div class="col-md-6">
          </div>
          <div class="col-md-3">
            {!! Form::submit('Save',array('class'=>'btn-default small_btn border1Black width100 round_btn padT10 padB10')) !!}
          </div>
          <div class="col-md-3">
            <button type="button" class="btn-default small_btn border1Black width100 round_btn padT10 padB10" data-dismiss="modal" >Cancel</button>
          </div>
        </div>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
<script>
$('.open_add_account').on('click', function(e) {
  $('#add_account_form #fk_account_category_id').val($(this).attr('data-id'));
});
$('#add_account').on('hidden.bs.modal', function(e) {
  $('#add_account_form #fk_account_category_id').val('');
  $("#full_form input[type='text']").removeClass('disabled');
  $('#select_supplier_form #fk_supplier_id, #select_company_form #fk_company_id').removeClass('disabled');
  $('#full_form').removeClass('opaque');
  $('#select_supplier_form, #select_company_form').removeClass('opaque');
  $('#form_type').val('');
  $("#fk_supplier_id, #fk_company_id").val('');
});

$('#fk_supplier_id, #fk_company_id').on("focus", function(){
  $('#form_type').val(1);
  $('#select_supplier_form #fk_supplier_id, #select_company_form #fk_company_id').removeClass('disabled');
  $('#select_supplier_form, #fk_company_id').removeClass('opaque');
  $('#full_form').addClass('opaque');
  $("#full_form input[type='text']").addClass('disabled');
});

$("#full_form input[type='text']").on("focus", function(){
  $('#form_type').val(2);
  $("#full_form input[type='text']").removeClass('disabled');
  $('#full_form').removeClass('opaque');
  $('#select_supplier_form, #select_company_form').addClass('opaque');
  $('#select_supplier_form #fk_supplier_id, #select_company_form #fk_company_id').addClass('disabled');
});

// edit account
$('.edit_account').click(function(e){
  e.preventDefault();
  var account_id = $(this).attr('data-id');
  var url = '<?= action('AccountingController@GetAccountDetail')?>';
  $.ajax({
    url:url,
    method:'POST',
    data:{account_id:account_id},
    success:function(result){
      $("body").removeClass("loading");
      $('#edit_account_body').html(result);
      $('#edit_account_modal').modal('show');
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $("body").removeClass("loading");
      alert('Something went wrong.');
      console.log(textStatus, errorThrown);
    }
  });
});
</script>
@endsection
