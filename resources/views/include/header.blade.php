<div class="website-bodytop  sectionbottom30">
    <div class="website-bodytoprow row d-flex justify-content-between">
        <div class="col-lg-4 col-md-5 col-sm-6 col-6">
            <div class="searchbtn">
                <form  id="search_form" role="search" action="{{url('/search')}}">
                    <span class="iconbox">
                        <img src="{{ asset('images/icons/searchicon.svg')}}" class="img-fluid" alt="search"/>
                    </span>
                    <label for="search">Search</label>
                    <input id="search"  type="search" placeholder="Search" name="search" value="{{ @$search }}" data-id="{{@$search}}" required="" title="Please enter text here.">                    
                    <button type="submit" id="search_button">Go</button>
                </form>
            </div>
        </div>
        <div class="col-lg-4 col-md-5  col-sm-6 col-6">
            <ul class="notification-John">
                <li class="feedbacks" data-hover="{{asset('images/feedback-white.svg')}}" data-src="{{asset('images/feedback.svg')}}">
                    <a href="javascript:;" class="btn feedbackbtn" id="feedbackbtn"><img src="{{asset('images/feedback.svg')}}" class="feedbackmini feedback-pink"  /><img src="{{asset('images/feedback-white.svg')}}" class="feedbackmini feedback-white"  />Feedback</a>
                </li>
                <li class="notifications">
                    <!-- <button type="button" class="notification-btn">
                        <img src="images/icons/notificationsicon.svg" alt="Notifications"
                            class="img-fluid" />
                    </button> -->
                    @php 
                        @$notificationdata=Common_function::notification();
                        $notificationCount=Common_function::notificationCount();                   
                    @endphp   
                    @if(Auth::check())
                        <div class="notifications_dropdown dropdown">
                            <button type="button" class="dropdown-toggle notify-dropdown pull-right {{ $notificationCount > 0 ? 'notification-btn' : 'notification-btn-new' }}" data-toggle="dropdown" aria-expanded="false">
                                <img src="{{asset('images/icons/notificationsicon.svg')}}" alt="Notifications"
                                class="img-fluid" />
                            </button>
                          
                        <div class="dropdown-menu notification-menu">
                            @if($notificationdata->count() > 0)
                                @foreach($notificationdata as $key=>$row)
                                    @php
                                        if(@$row->type_id == 23){
                                            $type_info=Common_function::feed_info_home($row->type_id,@$row->UserFeedNotification->family_feed->type);
                                        } else if(@$row->type_id == 0){
                                            $type_info_nominee='/nominee'; 
                                        } else{
                                            $type_info=Common_function::feed_info_home($row->type_id,$row->type);
                                          
                                        }
                                    @endphp
                                    <div class="notification_row">
                                        @php
                                        if(@$row->type_id == 39){
                                            @endphp
                                                <a href="javascript:;" class="notification_box">
                                            @php
                                        }else{
                                            @endphp
                                            <a href="{{(@$row->type_id != 0) ? $type_info.@$row->unique_id : $type_info_nominee}}" class="notification_box">
                                        @php
                                        }
                                        @endphp
                                            <div class="notification_img">
                                                <img src="{{@$row->UserInfo->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),@$row->UserInfo->profile_image) : url('images/userdefault-1.svg')}}" class="img-fluid" alt="Notification"/>
                                            </div>
                                            <div class="notification_text">
                                                <p><b class="public-info" data-link="{{route('public-profile.index',@$row->UserInfo->id)}}">{{@$row->UserInfo->first_name}} {{@$row->UserInfo->last_name}}  </b>{{@$row->message}} </p>
                                                <i>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}} </i>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach 
                                <div class="view_all_notification">
                                    <a href="{{route('notification.index')}}" class="view_notification_btn">View All</a>
                                </div>
                            @else
                                <div class="notification_row no-noification">
                                    <div class="notification_text">
                                        <p class="centertext">No Notification Available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </li>
                <li class="dropdownbox dropdown">
                    <button class="john-doe-btn dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mainicon">
                            <img src="{{  @Auth::user()->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),  @Auth::user()->profile_image) : url('images/userdefault-1.svg') }}" alt="Notifications" class="img-fluid" />
                        </span>

                        <span class="username">{{ @Auth::user()->first_name }}</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu menu2 dropdown-header-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item {{Request::is('profile') ? 'active' : '' }}" href="{{route('profile.index')}}">Profile</a>
                        <a class="dropdown-item {{Request::is('family-tree') ? 'active' : '' }}"  href="{{route('familytree.index')}}">Family Tree</a>
                        <a class="dropdown-item {{Request::is('family-list') ? 'active' : '' }}" href="{{route('familylist.index')}}">Family List</a>
                        <a class="dropdown-item {{Request::is('notification') ? 'active' : '' }}" href="{{route('notification.index')}}">Notifications</a>
                        <a class="dropdown-item logout js-logout" href="javascript:;">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>    
</div>
