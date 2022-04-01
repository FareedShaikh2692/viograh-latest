@extends('layouts.master')
@section('title', @$title)
@section('content')

@include("include.auth_header")
<div class="container_400">
    <a href="{{route('registers')}}" title="VIOGRAF" class="logo">
        <img src="{{asset('images/logo.svg')}}" alt="VIOGRAF" class="img-fluid">
    </a>
        <h4 class="text-center signup-head">
            <!-- <span style="color:#FF0000">Start</span>
            <span style="color:#66CC66">your</span>
            <span style="color:#FF9966">journey</span>
            <span style="color:#FFCCCC">with</span>
            <span style="color:#FF0066">Viograf</span> -->
            Start your journey with VioGraf
        </h4>
    
    @include('include.notification')
    <div class="google-btn">
        <a id="google-button" href="{{route('google-login')}}" title="Login with Google"
            class="btn_google">
            <span>
                <img src="{{asset('images/google.png')}}" class="img-fluid" alt="login with google"/>
            </span>
            Sign up with Google
        </a>
    </div>
    <span class="line">or</span>
    <form method="post" id="{{$frn_id}}" action="{{$action}}" method="post" >
    {{ csrf_field() }}    
    <div class="text_area row">
        <div class="form-group commonform col-md-6">
            <label class="placeholdertext">First name</label>
            <input type="text" name="first_name" id="first_name" class="form-control text-capitalize" value="{{ old('first_name') }}" data-type = "text">
        </div>
        <div class="form-group commonform col-md-6">
            <label class="placeholdertext">Last name</label>
            <input type="text" name="last_name" id="last_name" class="form-control text-capitalize" value="{{ old('last_name') }}" data-type = "text">
        </div>
        <div class="form-group commonform col-md-6">
            <label class="placeholdertext">Mobile Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control"  value="{{ old('phone_number') }}" data-type = "text">
        </div>
        <div class="form-group commonform col-md-6">
            <label class="placeholdertext">Date of Birth</label>
            <div class="date_of_birth_box">
                <span class="calImage">
                    <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                </span>
                <input type="text" name="birth_date" class="datepicker" value="{{ old('birth_date') }}" id="dobpicker" autocomplete="off" readonly="readonly" style="background-color:#f4f4f4" data-type = "text">
            </div>
        </div>
        <div class="form-group commonform col-md-12">
            <label class="placeholdertext">Gender </label><br>
            <div class="cont gender-signup-cont">
                <div class="form-check-inline">
                    <input type="radio" class="form-check-input gender_signup" id="radio1" name="gender" value="male">
                    <label class="form-check-label" for="radio1">Male</label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" class="form-check-input gender_signup" id="radio2" name="gender" value="female">
                    <label class="form-check-label" for="radio2">Female</label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" class="form-check-input gender_signup" id="radio3" name="gender" value="other" >
                    <label class="form-check-label" for="radio3">Other</label>
                </div>
            </div>
        </div>
        <div class="form-group commonform col-md-12">
            <label class="placeholdertext">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" data-type = "email">
        </div>
        <div class="form-group commonform col-md-12">
            <label class="placeholdertext">Password</label>
            <input type="password" name="password" id="password" class="form-control"  value="" data-type = "password">
            <button type="button"  class="show-password pass"><i id="pass" class="fas fa-eye" aria-hidden="true"></i></button> 
        </div>
    </div>
    <div class="detailpagearea">
        <div class="checkbox_Forgot form-group">
            <div class="form_check">
                <input type="checkbox" class="form-check-input" name="accept" id="accept">
                <label for="accept" class="mt-1 checktitle">I accept all 
                    <a href="{{url('/p/terms-and-conditions')}}" target="_blank" class="mt-1 forgot_pass">Terms & Conditions </a>and
                        <a href="{{url('/p/privacy-policy')}}" target="_blank" class="mt-1 forgot_pass">Privacy Policy </a>
                </label>
            </div>
        </div>
        <div class="login_btnarea">
            <button type="submit" class="btn_main">Sign Up</button>
        </div>
        <div class="acc_create">
            <p>Already have an account? 
                <a href="{{url('/')}}"> Login</a>
            </p>
        </div>
    </div>    
    </form>
</div>
@include("include.auth_footer")
@stop