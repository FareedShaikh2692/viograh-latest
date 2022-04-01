@extends('layouts.page')
@php $title = 'Privacy Settings'; @endphp
@section('title', @$title)
@section('pageContent')
            <div class="my-profile-page sectionbox">
                <h1 class="main_title">My Account</h1>
                @include('include/profile_menu')
                @include('include.notification')
                <div class="my-profile-main">
                <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-5 col-md-7">
                            <div class="inner_form_area">
                                <div class="form-group commonform">
                                <div class="form-group form_box privacy-setting-box">
                                        <p class="profile-setting-p">Keep my Profile as</p>
                                            <div class="cont profile-setting-div">
                                                <div class="form-check-inline privacy-radio">
                                                    <input type="radio" class="form-check-input gender_signup" id="radio1" name="profile" value="public" {{ (@$results->profile_privacy=="public")? "checked" : "" }} > 
                                                    <label class="form-check-label " for="radio1">Public</label>
                                                    <span class="checkmark"></span>
                                                </div>
                                                <div class="form-check-inline privacy-radio">
                                                    <input type="radio" class="form-check-input gender_signup" id="radio2" name="profile" value="family" {{ (@$results->profile_privacy=="family")? "checked" : "" }}>
                                                    <label class="form-check-label" for="radio2">Only Family</label>
                                                    <span class="checkmark"></span>
                                                </div>
                                                <div class="form-check-inline privacy-radio">
                                                    <input type="radio" class="form-check-input gender_signup " id="radio3" name="profile" value="private" {{ (@$results->profile_privacy=="private")? "checked" : "" }} >
                                                    <label class="form-check-label" for="radio3">Only Me</label>
                                                    <span class="checkmark"></span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            <div class="form-group commonform">
                                <div class="form-group form_box privacy-setting-box">
                                     <p class="profile-setting-p">Email Notifications</p>
                                        <div class="cont profile-setting-div">
                                            <div class="form-check-inline privacy-radio">
                                                <input type="radio" class="form-check-input gender_signup" id="on" name="notification" value="1" {{ (@$results->email_notification==1)? "checked" : "" }}>
                                                <label class="form-check-label" for="on">On</label>
                                            </div>
                                            <div class="form-check-inline privacy-radio">
                                                <input type="radio" class="form-check-input gender_signup" id="off" name="notification" value="0" {{ (@$results->email_notification==0)? "checked" : "" }}>
                                                <label class="form-check-label" for="off">Off</label>
                                            </div>
                                            
                                        </div>
                                </div>
                            </div>
                            <!-- <div class="form-group commonform col-md-12">
                                <div class="form-group form_box privacy-setting-box">
                                    <p>Nominee Detail</p>
                                    <div class="cont nominee">
                                        
                                    <input type="text" name="profession" maxlength="200" minlength="2"
                                    class="form-control nominee" value="{{ old('profession',@$result->profession) }}">

                                    <input type="text" name="profession" maxlength="200" minlength="2"
                                    class="form-control nominee" value="{{ old('profession',@$result->profession) }}">
                                </div>
                                <div class="cont nominee">
                                    <input type="text" name="profession" maxlength="200" minlength="2"
                                    class="form-control nominee" value="{{ old('profession',@$result->profession) }}">
                                
                                    <input type="text" name="profession" maxlength="200" minlength="2"
                                    class="form-control nominee" value="{{ old('profession',@$result->profession) }}">
                                    </div>
                                </div>
                            </div> -->
                                
                                <div class="btn_submit btn_privacy">
                                    <button type="submit" class="form_btn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
           
            </div>
       @stop