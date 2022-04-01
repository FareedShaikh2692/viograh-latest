@extends('layouts.page')
@php $title = 'Profile'; @endphp
@section('title', @$title)
@section('pageContent')
    <div class="my-profile-page sectionbox ">
        <h1 class="main_title profile_title">My Account</h1>
        @include('include.profile_menu')
         @include('include.notification')
            <div class="add-item_area text-right">
                <a href="{{url('/profile/edit/')}}" class="pink_btn edit-icon-btn"><img src="images/icons/edit_white.svg" alt="edit-profile" class="img-fluid"> Edit Profile</a>
            </div>
           
        <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form" enctype="multipart/form-data" >
                    {{ csrf_field() }}
            <div class="my-profile-main">
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="profile-img-box">
                            <div class="profile-img">
                            <input type="file" name="file" class="profileimage" id="profileimage" accept="image/jpeg,image/png,image/jpg">
                            <img class="profile-user-img  img-responsive img-circle" src="{{@$results->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),@$results->profile_image) : url('images/userdefault-1.svg')}}">
                            </div>
                            <!-- <p class="profile-Change" id="profile-Change">Change Photo</p> -->
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="profile_detailmain">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                        <h3>First Name</h3>
                                        @if(!empty(@$results->first_name))
                                        <h4><span>{{ $results->first_name}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                        <h3>Last Name</h3>
                                        @if(!empty(@$results->last_name))
                                        <h4><span>{{ $results->last_name}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                        <h3>Gender</h3>
                                    @if(!empty(@$results->gender))
                                        <h4><span>{{ucfirst(@$results->gender)}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                        <h3>Blood Group</h3>
                                    @if(!empty(@$results->blood_group))
                                        <h4><span>{{@$results->blood_group}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                        <h3>Email Address</h3>
                                        @if(!empty(@$results->email))
                                        <h4> <span>{{@$results->email}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                        <h3>Phone Number</h3>
                                        @if(!empty(@$results->phone_number))
                                        <h4><span>{{@$results->dial_code}} {{@$results->phone_number}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox " >
                                        <h3>Date of Birth</h3>
                                        @if(!empty(@$results->birth_date))
                                        <h4><span>{{Common_function::date_with_weekday(@$results->birth_date)}}</span></h4>
                                        @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox address" >
                                    <h3>Places Lived</h3>
                                    @if(!empty(@$results->places_lived))
                                        <h4><span>{{@$results->places_lived}}</span></h4>
                                    @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                    <h3>Profession</h3>
                                    @if(!empty(@$results->profession))
                                        <h4><span>{{nl2br(@$results->profession)}}</span></h4>
                                    @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="profile_detailmainbox" >
                                    <h3>Currency</h3>
                                    @if(!empty(@$results->UserCurrency->currency_code))
                                        <h4><span>{{nl2br(@$results->UserCurrency->currency_code)}}</span></h4>
                                    @else
                                        <h4>-</h4>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="profile_detailmainbox address" >
                                    <h3>Address</h3>
                                    @if(!empty(@$results->address))
                                        <h4><span>{!!nl2br(@$results->address)!!}</span></h4>
                                    @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div>
                               <!--  <div class="col-md-11">
                                    <div class="profile_detailmainbox address" >
                                    <h3>About Me</h3>
                                    @if(!empty(@$results->about_me))
                                        <h4><span>{{nl2br(@$results->about_me)}}</span></h4>
                                    @else
                                        <span>-</span>
                                    @endif
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop