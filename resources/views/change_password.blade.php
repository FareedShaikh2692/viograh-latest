@extends('layouts.page')
@php $title = 'Change Password'; @endphp
@section('title', @$title)
@section('pageContent')
            <div class="my-profile-page sectionbox">
                <h1 class="main_title">My Account</h1>
                @include('include/profile_menu')
                @include('include.notification')
                <div class="my-profile-main">
                <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form">
                    {{ csrf_field() }}
                    @php
                        $password=@$results->password;
                        $google_id=@$results->$google_id;   
                    @endphp
                    @if($google_id == '' &&  $password != '')
                        <div class="row">
                            <div class="col-lg-5 col-md-7">
                                <div class="inner_form_area">
                            
                            
                                    <div class="form-group form_box">
                                        <p>Current Password</p>
                                        <input type="password" data-type = "password" name="current_password" id="password" class="form-control change-pass-input passwordwidth" value="">
                                        <button type="button"  class="right-icons right-icon-password change-pass pass"><i id="pass" class="fas fa-eye" aria-hidden="true"></i></button> 
                                    </div>
                            
                                    <div class="form-group form_box">
                                        <p>New Password</p>
                                        <input type="password" data-type = "password" name="new_password" id="new_password" class="form-control change-pass-input passwordwidth" value="">
                                        <button type="button"  class="right-icons right-icon-password change-pass newpass"><i id="newpass" class="fas fa-eye" aria-hidden="true"></i></button> 
                                        <!-- <span class="right-icons newpass">
                                            <i class="fa fa-eye" id="newpass"  aria-hidden="true"></i>   
                                    </span> -->
                                    </div>
                                    <div class="form-group form_box marginbottom">
                                        <p>Confirm New Password</p>
                                        <input type="password" data-type = "password" name="confirm_password" id="confirm_password" class="form-control change-pass-input passwordwidth" value="">
                                        <button type="button"  class="right-icons right-icon-password  change-pass cnfpass"><i id="cnfPass" class="fas fa-eye" aria-hidden="true"></i></button> 
                                            <!-- <span class="right-icons cnfpass">
                                            <i class="fa fa-eye" id="cnfPass"  aria-hidden="true"></i>   
                                    </span> -->
                                    </div>
                                    <div class="btn_submit">
                                        <button type="submit" class="form_btn">Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="cls-no-record"> You can't change your account password, because you have  logged in with Google account. Please use  forget password option for set account password.</p>
                        </div>
                       
                    @endif
                    </form>
                </div>
            </div>
       @stop