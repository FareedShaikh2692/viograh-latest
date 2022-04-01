@extends('layouts.page')
@php $title = 'Login'; @endphp
@section('title', @$title)
@section('content')

@include("include.auth_header")
<div class="container_400">
    <a href="{{url('/')}}" title="VIOGRAF" class="logo mb-5">
        <img src="{{asset('images/logo.svg')}}" alt="VIOGRAF" class="img-fluid">
    </a>

    @include('include.notification')
    <div class="google-btn">
        <a id="google-button" href="{{route('google-login')}}" title="Login with Google"
            class="btn_google">
            <span>
                <img src="{{asset('images/google.png')}}" class="img-fluid" alt="login with google"/>
            </span>
            Login with Google
        </a>
    </div>
    <span class="line">or</span>
    <form method="post" id="frm_user_login" action="{{route('weblogin')}}" method="post">
    {{ csrf_field() }}
    <div class="text_area row">
        <div class="form-group commonform col-md-12">
            <label class="placeholdertext">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}" data-type = "email">
        </div>
        <div class="form-group commonform col-md-12">
            <label class="placeholdertext">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" data-type = "password">
            <button type="button" class="show-password pass" ><i id="pass" class="fa fa-eye" aria-hidden="true"></i></button>    
        </div>
    </div>
    <div class="detailpagearea">
        <div class="checkbox_Forgot">
            <div class="form_check">
                <input type="checkbox" class="form-check-input" name="remember_me" id="remember_me">
                <label for="remember_me" class="mt-1 checktitle">Remember me</label>
            </div>
            <a href="{{url('forgot-password')}}" class="mt-1 forgot_pass">Forgot Password?</a>
        </div>
        <div class="login_btnarea">
            <button type="submit" class="btn_main">Login</button>
        </div>
        <div class="acc_create">
            <p>Don't have an account?<a href="{{url('signup')}}">&nbsp;Sign Up</a></p>
        </div>
    </div>
    </form>
</div>
@include("include.auth_footer")
@stop