@extends('layouts.page')
@section('title', @$title)
@section('metasection')

@stop
@section('content')
    <div class="web_area">
        <div class="web_header">
            <div class="container">
                <div class="logo_backbtn">
                    @if ($sessiondata != '')
                        <a href="{{url('/home')}}" title="" class="logo_web">
                            <img src="{{asset('images/logo.png')}}" class="img-fluid" alt=""/>
                        </a>
                        <a href="{{url('/home')}}" title="" class="back_btn">
                            <span><img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt=""/> </span> Back to Home
                        </a>  
                    @else
                        <a href="{{url('/')}}" title="" class="logo_web">
                            <img src="{{asset('images/logo.png')}}" class="img-fluid" alt=""/>
                        </a>
                        <a href="{{url('/')}}" title="" class="back_btn">
                            <span><img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt=""/> </span> Back to Login
                        </a>  
                    @endif
                </div>
            </div>
        </div>
        <div class="web_body">
            <div class="container">
                <div class="ckeditor_box sectionbox">
                    <h1 style="text-align:center;">{{@$title}}</h1>
                    <h4 class="text-center signup-head mb-5">Leave your message here and we will reply to you shortly</h4>
                    <form method="post" id="{{$frn_id}}" action="{{$action}}" method="post" class="cntForm">
                    @include('include.notification')
                    <br>
                        {{ csrf_field() }}
                        <div class="text_area row">
                            <div class="form-group commonform col-md-6">
                                <label class="placeholdertext">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group commonform col-md-6">
                                <label class="placeholdertext">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" data-type = "email" value="{{ old('email') }}">
                            </div>
                            <div class="form-group commonform col-md-6">
                                <label class="placeholdertext">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control"  value="">
                            </div>
                            <div class="form-group commonform col-md-6">
                                <label class="placeholdertext">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control"  value="{{ old('subject') }}">
                            </div>
                            <div class="form-group commonform col-md-12">
                                <label class="placeholdertext">Message</label>
                                <textarea class="form-control form_textarea" name="message" id="message" >{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="detailpagearea">
                            <div class="login_btnarea">
                                <button type="submit" class="btn  cnctbtn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="web_footer">
            <div class="container">
                <div class="copyright_menu">
                    <div class="copyright"><p>© 2021 Viograf All rights reserved</p></div>
                    <div class="bottom_menu">
                        <ul class="">
                            <li class = "{{@$title == 'About Us'?'active':''}}"><a href="{{url('/p/about-us')}}" title="About Us">About Us</a></li>
                            <li class = "{{@$title == 'Terms & Conditions'?'active':''}}"><a href="{{url('/p/terms-and-conditions')}}" title="Terms &amp; Conditions">Terms &amp; Conditions</a></li>
                            <li class = "{{@$title == 'Privacy Policy'?'active':''}}"><a href="{{url('/p/privacy-policy')}}" title="Privacy Policy">Privacy Policy</a></li>
                            <li class="{{@$title == 'Contact Us'?'active':''}}"><a href="{{url('/contact-us')}}" title="Contact Us">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop