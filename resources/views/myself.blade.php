@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="my-profile-page sectionbox">
        <h1 class="main_title">Myself</h1>
        @include('include.myself_menu')       
        @include('include.notification')
        <div class="add-item_area text-right">               
            <a href="{{route('myself.edit')}}" class="pink_btn edit-icon-btn"><img src="images/icons/edit_white.svg" class="img-fluid"> Edit Details</a>
        </div>           
        
        <div class="my-profile-main">
            <div class="row">                    
                <div class="col-md-12">
                    <div class="profile_detailmain">
                        <div class="row">
                            @if(!empty(@$results->banner_image))
                                <div class="col-sm-12">
                                    <div class="banner-image-detail" >
                                        <img class="banner-imgtag-detail" src="{{@$results->banner_image ? Common_function::get_s3_file(config('custom_config.s3_banner_big'),@$results->banner_image) : ''}}">
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-12">
                                <div class="myself_detail" >
                                    <h3>Essence Of Life</h3>
                                    @if(!empty(@$results->essence_of_life))
                                    <h4><span>{!! nl2br(@$results->essence_of_life) !!}</span></h4>
                                    @else
                                    <span>-</span>
                                     @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="myself_detail" >
                                    <h3>Something About Me</h3>
                                    @if(!empty(@$results->biography))
                                    <h4><span>{!! nl2br(@$results->biography) !!}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                    <h3>Place of Birth</h3>
                                    @if(!empty(@$results->place_of_birth))
                                    <h4><span>{{@$results->place_of_birth}}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                    <h3>Favorite Movies</h3>
                                @if(!empty(@$results->favourite_movie))
                                    <h4><span>{{@$results->favourite_movie}}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                    <h3>Favorite Songs</h3>
                                @if(!empty(@$results->favourite_song))
                                    <h4><span>{{@$results->favourite_song}}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                    <h3>Favorite Books</h3>
                                    @if(!empty(@$results->favourite_book))
                                    <h4> <span>{{@$results->favourite_book}}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                    <h3>Favorite Eating Joints</h3>
                                    @if(!empty(@$results->favourite_eating_joints))
                                    <h4><span>{{@$results->favourite_eating_joints}}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                    <h3>Hobbies</h3>
                                    @if(!empty(@$results->hobbies))
                                    <h4><span>{{@$results->hobbies}}</span></h4>
                                    @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail address" >
                                <h3>Food</h3>
                                @if(!empty(@$results->food))
                                    <h4><span>{{@$results->food}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="myself_detail" >
                                <h3>Role Model</h3>
                                @if(!empty(@$results->role_model))
                                    <h4><span>{{@$results->role_model}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail address" >
                                <h3>Car</h3>
                                @if(!empty(@$results->car))
                                    <h4><span>{{@$results->car}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail address" >
                                <h3>Brand</h3>
                                @if(!empty(@$results->brand))
                                    <h4><span>{{@$results->brand}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail address" >
                                <h3>TV Shows</h3>
                                @if(!empty(@$results->tv_shows))
                                    <h4><span>{{@$results->tv_shows}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail address" >
                                <h3>Actors</h3>
                                @if(!empty(@$results->actors))
                                    <h4><span>{{@$results->actors}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail address" >
                                <h3>Sports Person</h3>
                                @if(!empty(@$results->sports_person))
                                    <h4><span>{{@$results->sports_person}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail address" >
                                <h3>Politician</h3>
                                @if(!empty(@$results->politician))
                                    <h4><span>{{@$results->politician}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail_new address" >
                                <h3>Diet</h3>
                                @if(!empty(@$results->diet))
                                    <h4><span>{{@$results->diet}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="myself_detail_new address" >
                                <h3>Zodiac Sign</h3>
                                @if(!empty(@$results->zodiac_sign))
                                    <h4><span>{{@$results->zodiac_sign}}</span></h4>
                                @else
                                    <span>-</span>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@stop