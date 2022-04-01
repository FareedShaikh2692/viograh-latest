
@extends('layouts.page')

@section('title', @$title)

@section('content')

@include("include.auth_header")
<div class="container_400">
    <a href="javascript:;" title="VIOGRAF" class="logo">
        <img src="{{asset('images/logo.svg')}}" alt="VIOGRAF" class="img-fluid">
    </a>
    <h4 class="text-center signup-head">
        Recover your password
    </h4>
    @include('include.notification')
    </br>
    <form method="post" id="frm_user_reset_pass" action="{{$action}}" method="post">
    {{ csrf_field() }}
        <div class="text_area row">
            
            <div class="form-group commonform col-md-12">
                <label class="placeholdertext">New Password</label>
                <input type="password" name="password" id="password" class="form-control" value="">
                <button type="button" class="show-password pass"><i id="pass" class="fa fa-eye" aria-hidden="true"></i></button> 
            </div>
            <div class="form-group commonform col-md-12">
                <label class="placeholdertext">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="">
                <button type="button" class="show-password cnfpass"><i id="cnfPass" class="fa fa-eye" aria-hidden="true"></i></button> 
            </div>
        </div>
        <div class="detailpagearea">
            <div class="login_btnarea">
                <button type="submit" class="btn_main">Reset password</button>
            </div>
        </div>
    </form>
</div>
@include("include.auth_footer")
@stop