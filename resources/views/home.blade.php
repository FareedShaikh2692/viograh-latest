@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="welcome-familyarea sectionbottom30">
    <div class="msg_notifcation"> @include('include.notification')</div>
        <ul class="row">
            <li class="col-lg-6 col-md-5">
                <div class="welcome_userarea sectionbox sectionbox-home">
                    <h1 class="main_title welcomename">Welcome {{ @Auth::user()->first_name }}!</h1>
                    <button type="button" class="status_post_popup" data-toggle="modal"
                        data-target="#exampleModal">
                        <span class="iconimg">
                            <img src="{{  @Auth::user()->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),  @Auth::user()->profile_image) : url('images/userdefault-1.svg')}}" class="img-fluid" alt="Login icon" />
                        </span>
                        <span class="nameofuser">
                             Add a story to your biography
                        </span>
                    </button>
                </div>
            </li>
            <li class="col-lg-6 col-md-7">
                <div class="your_familyarea sectionbox ">
                    <h2 class="main_title">Your Family</h2>
                    <div class="your_familyrow">
                        <div class="add_family_col">
                        <a href="{{route('familytree.index')}}"> <button class="add_familybtn">
                                <span class="addiconimg">
                                    <img src="images/icons/addicon.svg" class="img-fluid" alt="Add icon" />
                                </span>
                                <span class="addicontext">Add New</span>
                            </button></a>
                        </div>
                        <div class="familyicons_col">
                            <ul class="family_members_row">
                            @foreach($result_family as $key=>$row)
                                <li class="family_members_col">
                                    <div class="family_members_box" >
                                        <div class="family_membersicon">
                                        @php
                                            if(@$row->gender == 'male'){
                                                $default_image = url('images/userdefault.jpg');
                                            } else{
                                                $default_image = url('images/female-user.jpg');
                                            }
                                        @endphp
                                            @if(!empty(@$row->getFamilyTreeUserEmail))
                                                <img src="{{ @$row->getFamilyTreeUserEmail->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$row->getFamilyTreeUserEmail->profile_image) : url('images/userdefault-1.svg') }}" alt="Family Feed" class="img-fluid familylist-img" />
                                            @else
                                                <img src="{{ (@$row->image) ? Common_function::get_s3_file(config('custom_config.s3_family_tree'),$row->image) : $default_image }}" class="img-fluid {{((@$row->image == '') ? 'default_image' : '')}}" alt="Family Members" />
                                            @endif
                                        </div>
                                        <p class="family_name_home">
                                            @if(!empty(@$row->getFamilyTreeUserEmail))
                                                {{@$row->getFamilyTreeUserEmail->first_name}} {{@$row->getFamilyTreeUserEmail->last_name}}
                                            </p>
                                            <p> <span class="family_name_home family_name_tree">&#40;{{@$row->name}} </span>  &#41;</p>
                                            @else
                                              <span class="center">{{@$row->name}}</span>
                                            @endif
                                </div>
                                </li>    
                            @endforeach
                            @if(@$result_family_count > 3)
                                <li class="family_members_col view_allfamily_col allfamily_col">
                                    <a href="{{route('familytree.index')}}" class="view_allfamilybox">
                                        <span class="view_allfamilyimg">
                                            <!-- <img src="images/icons/view_icon.svg" class="img-fluid" alt="View Icon" /> -->
                                            <i class="icon1"></i>
                                            <i class="icon2"></i>
                                            <i class="icon3"></i>
                                        </span>
                                        View All
                                    </a>
                                </li>
                            @endif
                        </div>
                     </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="your-viografarea tabarea sectionbottom30">
        <div class="sectionbox sectionboxhome">
            <h2 class="main_title">Your Biography</h2>
            <div class="your_viograftab tabbox">
                <ul id="tabs" class="nav nav-tabs" role="tablist">
                   
                    <li class="nav-item">
                        <a id="tab-C" href="#pane-C" class="nav-link active" data-toggle="tab" role="tab">Special Moments</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-D" href="#pane-D" class="nav-link" data-toggle="tab" role="tab">Diary</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-E" href="#pane-E" class="nav-link" data-toggle="tab"
                            role="tab">Experiences</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-F" href="#pane-F" class="nav-link" data-toggle="tab" role="tab">Wishlist</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-G" href="#pane-G" class="nav-link" data-toggle="tab" role="tab">Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-H" href="#pane-H" class="nav-link" data-toggle="tab" role="tab">Dreams</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-I" href="#pane-I" class="nav-link" data-toggle="tab" role="tab">Life
                            Lessons</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-J" href="#pane-J" class="nav-link" data-toggle="tab" role="tab">Firsts </a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-K" href="#pane-K" class="nav-link" data-toggle="tab" role="tab">
                            Lasts</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-L" href="#pane-L" class="nav-link" data-toggle="tab" role="tab">Spiritual
                            Journey</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-A" href="#pane-A" class="nav-link " data-toggle="tab" role="tab">Education</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab">Career</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-M" href="#pane-M" class="nav-link" data-toggle="tab" role="tab">Milestones</a>
                    </li>
                   
                </ul>
                <div id="content" class="tab-content" role="tablist">
                    <div id="pane-A" class="card tab-pane fade   inner_diary_page" role="tabpanel" aria-labelledby="tab-A">
                        <div class="card-header my-education-list_area" role="tab" id="heading-A">
                            <div class="row your_viografrow " style="list-style-type:none;">
                                <div class="col-md-3 col-sm-4 col-6 your_viografcol">
                                    <div class="add_wishlist">
                                    <a href="{{route('education.add')}}"> <button>
                                            <span class="addbtnimg">
                                                <img src="images/icons/addicon.svg" alt="add box"
                                                    class="img-fluid" />
                                            </span>
                                            <span class="addbtntext">Add Education</span>
                                        </button></a>

                                    </div>
                                </div>
                                  @include('include.education-block',['results' => @$result_education])
                            </div>
                            <div class="text-center">
                                @if(@$resultcount[config('custom_config.user_feed_type')['education']] >  3 )
                                    <div class="text-center">
                                        <a href="{{route('education.index')}}" class="main_btn">View All</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                        
                    <div id="pane-B" class="card tab-pane fade  inner_diary_page" role="tabpanel" aria-labelledby="tab-B">
                        <div class="card-header" role="tab" id="heading-B">
                            <div class="row your_viografrow " style="list-style-type:none;">
                                <div class="col-md-3 col-sm-4 col-6 your_viografcol">
                                    <div class="add_wishlist">
                                    <a href="{{route('career.add')}}"> <button>
                                            <span class="addbtnimg">
                                                <img src="images/icons/addicon.svg" alt="add box"
                                                    class="img-fluid" />
                                            </span>
                                            <span class="addbtntext">Add Career</span>
                                        </button></a>

                                    </div>
                                </div>
                          
                                @include('include.career-block',['results' => @$results_career])
                           
                            </div>
                            <div class="text-center">
                                @if(@$resultcount[config('custom_config.user_feed_type')['career']] >  3 )
                                    <div class="text-center">
                                        <a href="{{route('career.index')}}" class="main_btn">View All</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                   
                        <div id="pane-C" class="card tab-pane show active fade inner_moments_page " role="tabpanel"
                         aria-labelledby="tab-C">
                            <div class="card-header" role="tab" id="heading-C">
                                <div class="row your_viografrow moments_row">
                                    <div class="col-md-3 col-sm-4 col-6  col-sm-4 your_viografcol">
                                        <div class="add_wishlist add_Moments">
                                        <a href="{{route('moments.add')}}"> <button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext addmomenttext_font">Add Special Moment</span>
                                            </button>
                                        </div>
                                    </div>
                              
                                    @include('include.moment-block',['result' => @$result_moments])
                              
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['moments']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('moments.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-D" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-D">
                            <div class="card-header  " role="tab" id="heading-D">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col  your_viografcol">
                                        <div class="add_wishlist">
                                        <a href="{{route('diary.add')}}" ><button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Diary</span>
                                            </button></a>

                                        </div>
                                    </div>
                                    @include('include.diary-block',['results' => @$result_diary])
                                </div>
                                <div class="text-center">
                                    @if(@$result_diary_count->count() >  3 )
                                        <div class="text-center">
                                            <a href="{{route('diary.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-E" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-E">
                            <div class="card-header" role="tab" id="heading-E">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                
                                        <div class="add_wishlist">
                                    
                                        <a href="{{route('experience.add')}}" ><button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Experience</span>
                                            </button></a>

                                        </div>
                                    </div>
                                    @include('include.experience-block',['results' => @$result_experience])
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['experience']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('experience.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-F" class="card tab-pane fade inner_wishlist_page" role="tabpanel" aria-labelledby="tab-F">
                            <div class="card-header" role="tab" id="heading-D">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                        <div class="add_wishlist">
                                            <a href="{{route('wishlist.add')}}"><button>
                                                    <span class="addbtnimg">
                                                        <img src="images/icons/addicon.svg" alt="add box"
                                                            class="img-fluid" />
                                                    </span>
                                                    <span class="addbtntext">Add Wishlist</span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                    @include('include.wishlist-block',['results' => @$result_wishlist])
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['wish_list']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('wishlist.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-G" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-G">
                            <div class="card-header" role="tab" id="heading-G">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                
                                        <div class="add_wishlist">
                                    
                                        <a href="{{route('idea.add')}}"><button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Idea</span>
                                            </button></a>

                                        </div>
                                    </div>
                               
                                    @include('include.ideas-block',['results' => @$result_idea])
                              
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['idea']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('idea.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-H" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-H">
                            <div class="card-header" role="tab" id="heading-H">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                
                                        <div class="add_wishlist">
                                    
                                        <a href="{{route('dream.add')}}"> <button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Dream</span>
                                            </button></a>

                                        </div>
                                    </div>
                              
                                    @include('include.dream-block',['results' => @$result_dreams])
                                
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['dreams']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('dream.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-I" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-I">
                            <div class="card-header" role="tab" id="heading-I">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                        <div class="add_wishlist">
                                        <a href="{{route('lifelesson.add')}}" ><button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Life Lesson</span>
                                            </button></a>

                                        </div>
                                    </div>
                             
                                    @include('include.lifelesson-block',['results' => @$result_life])
                               
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['life_lessons']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('lifelesson.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-J" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-J">
                            <div class="card-header" role="tab" id="heading-J">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                        <div class="add_wishlist">
                                        <a href="{{route('first.add')}}"><button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add First</span>
                                            </button></a>

                                        </div>
                                    </div>
                             
                                    @include('include.first-block',['results' => @$result_first])
                                
                                </div>
                                <div class="text-center">
                                    @if(@$firstCount >  3 )
                                        <div class="text-center">
                                            <a href="{{route('first.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-K" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-k">
                            <div class="card-header" role="tab" id="heading-K">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                        <div class="add_wishlist">
                                        <a href="{{route('last.add')}}"><button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Last</span>
                                            </button></a>

                                        </div>
                                    </div>
                              
                                    @include('include.last-block',['results' => @$result_last])
                               
                                </div>
                                <div class="text-center">
                                    @if(@$lastCount >  3 )
                                        <div class="text-center">
                                            <a href="{{route('last.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-L" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-L">
                            <div class="card-header" role="tab" id="heading-L">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                        <div class="add_wishlist">
                                        <a href="{{route('spiritual-journey.add')}}"> <button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Spiritual Journey</span>
                                            </button></a>

                                        </div>
                                    </div>                              
                                    @include('include.spiritual-journey-block',['results' => @$result_spiritual])
                                </div>
                                <div class="text-center">
                                    @if(@$resultcount[config('custom_config.user_feed_type')['spiritual_journeys']] >  3 )
                                        <div class="text-center">
                                            <a href="{{route('spiritual-journey.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pane-M" class="card tab-pane fade inner_diary_page" role="tabpanel" aria-labelledby="tab-M">
                            <div class="card-header" role="tab" id="heading-M">
                                <div class="row your_viografrow viograf_row" style="list-style-type:none;">
                                    <div class="col-md-3 col-sm-4 col-6 viograf_col">
                                        <div class="add_wishlist">
                                        <a href="{{route('milestone.add')}}">
                                            <button>
                                                <span class="addbtnimg">
                                                    <img src="images/icons/addicon.svg" alt="add box"
                                                        class="img-fluid" />
                                                </span>
                                                <span class="addbtntext">Add Milestone</span>
                                            </button>
                                        </a>
                                        </div>
                                    </div>   
                                                        
                                    @include('include.milestone-feed-block',['results' => @$result_milestone])
                                </div>  
                                <div class="text-center">
                                    @if(@$result_milestone_count->count() >  3 )
                                        <div class="text-center">
                                            <a href="{{route('milestone.index')}}" class="main_btn">View All</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="family-feedsecarea sectionbottom30 ">
    
    <div class="sectionbox home-myself-cover" > 
            <div class="db-banner-image">
                @if(@$result_myelf->banner_image != '')
                    <img class="db-image" src="{{@$result_myelf->banner_image ? Common_function::get_s3_file(config('custom_config.s3_banner_big'),@$result_myelf->banner_image) : ''}}" alt="Vio Graf" /> 
                @else
                    <div class="btn_submit btn-edit-banner">
                        <a href="{{route('myself.edit')}}"><button class=" home-myself-button">Add Banner Image</button></a>
                    </div> 
                @endif
            </div>
            <div class="banner-cover">
                <img src="{{asset('images/banner_img.png')}}" class="banner-img" alt="img" />
               
                <div class="content-div right-content">
                    <div class="parent-content">
                        <!-- <span class="myself-title">About Myself</span> -->
                        @if(!empty(@$result_myelf->essence_of_life))
                            <p class="myself-para">
                                {!! nl2br(@$result_myelf->essence_of_life) !!}
                            </p>
                            @else
                            <p class="myself-para static-text">
                                Beauty begins the  moment you decide to be yourself.
                            </p>
                        @endif
                        <div class="btn_submit">
                            <a href="{{route('myself.edit')}}"><button class=" home-myself-button">About Me</button></a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="family-feedsecarea tionbottom30 ">
        <div class="sectionbox sectionboxhome">
            <h2 class="main_title"> Family Feed</h2>
            @if(count(@$result_family_feed) != 0)
                <ul class="family-feedsec-row viograf_row inner_diary_page">
                    @include('include.home-family-block',['results' => @$result_family_feed])
                </ul>
            @else
                <p class="cls-no-record">No Records Found</p>
            @endif
            @if(@$feedCounts > 4)
                <div class="text-center">
                    <a href="{{route('familyfeed.index')}}" class=" main-btn-view">View All</a>
                </div>
            @endif
        </div>
    </div>
    <!-- model pop up -->
    <div class="common_popup modal fade"  id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">What do you want to add to your biography today?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        
                    </button>
                </div>
                <div class="modal-body">
                    <div class="want-add">I want to add</div>
                    <ul class="addbox_row">
                        <li>
                            <a href="{{route('moments.add')}}" class="addbox_btn">Special Moments</a>
                        </li>
                        <li>
                            <a href="{{route('diary.add')}}" class="addbox_btn">Diary</a>
                        </li>
                        <li>
                            <a href="{{route('experience.add')}}" class="addbox_btn">Experiences</a>
                        </li>
                        <li>
                            <a href="{{route('wishlist.add')}}" class="addbox_btn">Wishlist</a>
                        </li>
                        <li>
                            <a href="{{route('idea.add')}}" class="addbox_btn">Ideas</a>
                        </li>
                        <li>
                            <a href="{{route('dream.add')}}" class="addbox_btn">Dreams</a>
                        </li>
                        <li>
                            <a href="{{route('lifelesson.add')}}" class="addbox_btn">Life Lessons</a>
                        </li>
                        <li>
                            <a href="{{route('first.add')}}" class="addbox_btn">Firsts</a>
                        </li>
                        <li>
                            <a href="{{route('last.add')}}" class="addbox_btn">Lasts</a>
                        </li>
                        <li>
                            <a href="{{route('spiritual-journey.add')}}" class="addbox_btn">Spiritual Journey</a>
                        </li>
                        <li>
                            <a href="{{route('education.add')}}" class="addbox_btn">Education</a>
                        </li>
                        <li>
                            <a href="{{route('career.add')}}" class="addbox_btn">Career</a>
                        </li>
                        <li>
                            <a href="{{route('milestone.add')}}" class="addbox_btn">Milestones</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
@section('font-style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
@stop