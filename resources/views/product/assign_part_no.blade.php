@extends('layouts.app')

@section('content')
@section('title', 'Assign Part Numbers')
@include('product.sub_head')
<br><br><br><br>
<div class="col-md-7 text-center padB30 col-md-offset-2 hide"><h3>Assign Part Numbers</h3></div>
<div class="portlet-body">
  <div class="row">
    <div class="col-md-4 col-md-offset-2">
      <div class="users-panel" style="margin:0">
        <a href="{{ count($assign_parts)>0 ? action('ProductController@AssignPart',['id'=>$assign_parts->fk_product_class_id]) : action('ProductController@AssignPart') }}" class="user-panel-click">
          <img src="{{ url('/img/assign-number.png') }}" alt="">
          <span class="user_title">Assign Part Numbers</span>
        </a>
        <!--<a href="{{ action('ProductController@AssignPart')}}" class="user-panel-click">
          <img src="{{ url('/img/assign-number.png') }}" alt="">
          <span class="user_title">Assign Part Numbers</span>
        </a>-->
      </div>
    </div>
    <div class="col-md-6">
      <div class="users-panel" style="margin-left:100px">
        <a href="{{ action('ProductController@ViewAll') }}" class="user-panel-click">
          <img src="{{ url('/img/view-all2.png') }}" alt="">
          <span class="user_title">View All</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
