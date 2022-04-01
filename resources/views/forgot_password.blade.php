@extends('layouts.page')

@section('title', @$title)
@section('content')

@include("include.auth_header")
<div class="container_400">
    <a href="javascript:;" title="VIOGRAF" class="logo">
        <img src="{{asset('images/logo.svg')}}" alt="VIOGRAF" class="img-fluid">
    </a>
    <h4 class="text-center signup-head">
    Please enter your registered email address to get password reset link
    </h4>
    @include('include.notification')
    </br>
    <form method="post" id="frm_user_forgot_pass" action="{{url('/password/sendEmail')}}" method="post">
    {{ csrf_field() }}
        <div class="text_area">
            <div class="form-group commonform">
                <label class="placeholdertext">Email Address</label>
                <input type="email" name="forgot_email" id="forgot_email" data-type="email" class="form-control" value="{{old('forgot_email')}}">
            </div>
            <!-- <div class="form-group commonform">
                <input type="text" name="txtfname" id="txtfname" maxlength="200" minlength="1"
                    class="form-control lettersonly" required="" value="">
                <button class="show-password"><i class="fa fa-eye" aria-hidden="true"></i></button> 
                <label class="placeholdertext">New Password</label>
            </div> -->
        </div>
        <div class="detailpagearea">
            <div class="login_btnarea">
                <button type="submit" class="btn_main">Send reset link</button>
            </div>
            <div class="acc_create">
                <p><a href="{{url('/')}}">Back to login</a></p>
            </div>
        </div>
    </form>
</div>
@include("include.auth_footer")
    
@stop