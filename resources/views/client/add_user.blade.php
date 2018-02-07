@extends('layouts.app')

@section('content')
@section('title', 'Add User')
@include('client.sub_head')
<div class="panel panel-default" style="border: 0px solid #000">
  <div class="panel-heading text-center hide"><strong>Add new user</strong></div>
    @include('client.form')
</div>
@endsection
