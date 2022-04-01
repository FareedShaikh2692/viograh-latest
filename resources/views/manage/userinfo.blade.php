  @extends('adminlte::page')
@section('title', $title)

@section('content_header')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-6">
            <h3>{{ $title }}</h3>
            <ol class="breadcrumb">
                <li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/manage/users') }}">{{$main_module}}</a></li>
                <li class="active">{{ $title }}</li>
            </ol>
        </div>
        <div class="col-md-9 col-sm-12 col-xs-12">
            <a href="{{ url('/manage/users') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
@stop
@section('content')
    <div class="row">       
        <div class="col-md-12">
             <!-- BASIC INFORMATION CARD-->
            <div class="card card-primary">
                <div class="card-header with-border">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">                            
                                    <div class="col-md-12">
                                        <ul class="banner-profile row">
                                            @if (@$user->banner_image)
                                                <li class="prod-images">
                                                    <a href="{{Common_function::get_s3_file(config('custom_config.s3_banner_big'), @$user->banner_image)}}" data-fancybox = "gallery" >
                                                        <div class="profile-banner-div-admin" style="background-image:url('{{@$user->banner_image ? Common_function::get_s3_file(config('custom_config.s3_banner_big'),@$user->banner_image) : ''}}')">
                                                        <img class="banner-imgtag-admin" src="{{@$user->banner_image ? Common_function::get_s3_file(config('custom_config.s3_banner_big'),@$user->banner_image) : ''}}">
                                                        <span class="file-input-browse-banner-iconbtn"> Banner Image</span>
                                                        <!-- <h3 class="profile-username profile-username-banner" >Banner Image</h3> -->
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif    
                                            <li class="prod-images profile profile-div-admin">
                                                <a href="{{@$user->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$user->profile_image) : url('images/userdefault-1.svg')}}" data-fancybox = "gallery" >
                                                    <div class="{{(@$user->profile_image) ? 'profile-banner-div-admin' : 'profile-banner-div-default'}} " style="background-image:url('{{@$user->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$user->profile_image) : url('images/userdefault-1.svg')}}')">
                                                        <img class="banner-imgtag-admin" src="{{@$user->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),@$user->profile_image) : url('images/userdefault-1.svg')}}">    
                                                        <span class="file-input-browse-banner-iconbtn"> Profile Image</span>            
                                                    <!-- <h3 class="profile-username" >{{ $user->first_name }}</h3>  -->
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>      
                                   
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class='col-md-4'>
                                            <strong> First Name </strong>
                                            <p class="text-muted">{{ $user->first_name }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Last Name </strong>
                                            <p class="text-muted">{{ $user->last_name }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Gender</strong>
                                            <p>{{ $user->gender ? ucfirst($user->gender) : '-' }}</a></p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Blood Group</strong>
                                            <p class="text-muted">{{ $user->blood_group ? $user->blood_group :'-' }}</p>
                                        </div>                                    
                                        <div class="col-md-4">
                                            <strong> Email Address</strong>
                                            <p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Phone Number </strong>
                                            <p class="text-muted">{{$user->dial_code}} {{ $user->phone_number ? $user->phone_number :'-' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Birth Date </strong>
                                            <p class="text-muted">{{ $user->birth_date ? date('d M,Y', strtotime($user->birth_date)) :'-' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Profession </strong>
                                            <p class="text-muted">{{ $user->profession ? $user->profession :'-' }}</p>
                                        </div>                                    
                                        <div class="col-md-4">
                                            <strong> Created Date </strong>
                                            <p class="text-muted">{{ $user->created_at ? Common_function::show_date_time($user->created_at) :'-' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Updated Date </strong>
                                            <p class="text-muted">{{ $user->updated_at ? Common_function::show_date_time($user->updated_at) :'-' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Created Ip </strong>
                                            <p class="text-muted">{{ $user->created_ip ? $user->created_ip :'-' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <strong> Updated Ip </strong>
                                            <p class="text-muted">{{ $user->updated_ip ? $user->updated_ip :'-' }}</p>
                                        </div>                                    
                                        <div class="col-md-12">
                                            <strong> Places Lived </strong>
                                            <p class="text-muted">{{ $user->places_lived ? $user->places_lived :'-' }}</p>
                                        </div>                                    
                                        <div class="col-md-12">
                                            <strong> Address </strong>
                                            <p class="text-muted">{{ $user->address ? $user->address :'-' }}</p>
                                        </div>
                                       <!--  <div class="col-md-12">
                                            <strong> About Me </strong>
                                            <p class="text-muted">{{ $user->about_me ? $user->about_me :'-' }}</p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- END BASIC INFORMATION CARD -->

            <!-- MYSELF ABOUT DETAIL CARD -->
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header with-border">
                                    <h3 class="card-title">MySelf</h3>
                                </div>
                            </div>                       
                            <div class="col-md-12">
                                <div class="row">                                    
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Place Of Birth </strong>
                                        <p class="text-muted">{{ $user->place_of_birth ? $user->place_of_birth :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Favourite  Movie </strong>
                                        <p class="text-muted">{{ $user->favourite_movie ? $user->favourite_movie :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Favourite  Song </strong>
                                        <p class="text-muted">{{ $user->favourite_song ? $user->favourite_song :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Favourite  Books </strong>
                                        <p class="text-muted">{{ $user->favourite_book ? $user->favourite_book :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Favourite  Eating Joints </strong>
                                        <p class="text-muted">{{ $user->favourite_eating_joints ? $user->favourite_eating_joints :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Hobbies </strong>
                                        <p class="text-muted">{{ $user->hobbies ? $user->hobbies :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Role Model </strong>
                                        <p class="text-muted">{{ $user->role_model ? $user->role_model :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Food </strong>
                                        <p class="text-muted">{{ $user->food ? $user->food :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Car </strong>
                                        <p class="text-muted">{{ $user->car ? $user->car :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Brand </strong>
                                        <p class="text-muted">{{ $user->brand ? $user->brand :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Tv Shows </strong>
                                        <p class="text-muted">{{ $user->tv_shows ? $user->tv_shows :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong>Actors </strong>
                                        <p class="text-muted">{{ $user->actors ? $user->actors :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Sports Person </strong>
                                        <p class="text-muted">{{ $user->sports_person ? $user->sports_person :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Politician </strong>
                                        <p class="text-muted">{{ $user->politician ? $user->politician :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong>Diet </strong>
                                        <p class="text-muted">{{ $user->diet ? $user->diet :'-' }}</p>
                                    </div>
                                    <div class="col-md-4 wordbreakprop">
                                        <strong> Zodiac Sign </strong>
                                        <p class="text-muted">{{ $user->zodiac_sign ? $user->zodiac_sign :'-' }}</p>
                                    </div>                                        
                                    <div class="col-md-12">
                                        <strong>Something About Me</strong>
                                        <p class="desc-info">{!! Common_function::string_read_more(@$user->biography) ? Common_function::string_read_more(@$user->biography) : '-' !!}</p>
                                    </div>  
                                    <div class="col-md-12">
                                            <strong> Essence Of Life </strong>
                                            <p class="text-muted">{{ $user->essence_of_life ? $user->essence_of_life :'-' }}</p>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MYSELF ABOUT DETAIL CARD -->
            <!-- FAMILY LIST DETAIL CARD -->
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header with-border">
                                    <h3 class="card-title">Family List</h3>
                                </div>
                            </div>                       
                            <div class="col-md-12">                                   
                                <table id="example1" class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th width=1%>#</th>
                                            <th class="roundimg-col">Profile</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Relationship</th>
                                            <th>Contact Number</th>
                                            <th>Added On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($family_list->count() == 0)
                                            <tr class="data">
                                                <td colspan="8" align="center">No Records Found</td>
                                            </tr>
                                        @else
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach($family_list as $key=>$familyrow)
                                                <tr class="data module-list" id="data-{{ $familyrow->id }}">
                                                    <td>{{ $i++ }}</td>
                                                    <td class="no-sort roundimg-col">
                                                        <div class="bg-image">
                                                            <a href="{{@$familyrow->image ? Common_function::get_s3_file(config('custom_config.s3_family_tree'),$familyrow->image) : url('images/userdefault-1.svg')}}" data-fancybox = "gallery" >
                                                            <img src="{{ @$familyrow->image ? Common_function::get_s3_file(config('custom_config.s3_family_tree'),@$familyrow->image) : url('images/userdefault-1.svg') }}" class="list-image-prof"></a>
                                                        </div>
                                                    </td>
                                                    <td class="img-td">{{ $familyrow->name}}</td>
                                                    <td class="img-td"><a class="permlink" href="mailto:{{ $familyrow->email }}">{{ $familyrow->email }}</a></td>
                                                    <td class="img-td">{{config('custom_config.fmailylist_relation_type')[@$familyrow->relationship]}}</td>
                                                    <td class="img-td">{{ $familyrow->phone_number ?  $familyrow->phone_number : '-' }}</td>
                                                    <td class="img-td">{{date("jS F, Y", strtotime(@$familyrow->created_at))}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- OTHER MODULES DATA(LAST CARD) -->
            <div class="card card-primary card-outline card-outline-tabs">
                <!-- OTHER MODULE NAVIGATION -->
                <div class="card-header p-0 border-bottom-0">                
                    <ul class="nav nav-tabs user-tabs" id="custom-tabs-four-tab" role="tablist">                       
                        <li class="nav-item">
                            <a class="nav-link {{ (@$section=='Education' || @$section=='')? 'active' : ''}}" id="custom-tabs-four-education-tab" href="{{url('manage/users/information/'.$user->id.'?section=Education#custom-tabs-four-tab')}}" role="tab">Education</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{  (@$section=='Career' )? 'active' : '' }}" id="custom-tabs-four-career-tab" href="{{url('manage/users/information/'.$user->id.'?section=Career#custom-tabs-four-tab')}}" role="tab" >Career</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (@$section=='WishList' )? 'active' : ''  }}" id="custom-tabs-four-wishlist-tab" href="{{ url('manage/users/information/'.$user->id.'?section=WishList#custom-tabs-four-tab') }}" role="tab" >Wish List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (@$section=='Ideas' )? 'active' : ''}}" id="custom-tabs-four-idea-tab"  href="{{ url('manage/users/information/'.$user->id.'?section=Ideas#custom-tabs-four-tab') }}" role="tab" >Ideas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (@$section=='Diary' )? 'active' : '' }}" id="custom-tabs-four-diary-tab"  href="{{  url('manage/users/information/'.$user->id.'?section=Diary#custom-tabs-four-tab') }}" role="tab" >Diary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{(@$section=='LifeLessons' )? 'active' : '' }}" id="custom-tabs-four-lifelesson-tab"  href="{{ url('manage/users/information/'.$user->id.'?section=LifeLessons#custom-tabs-four-tab')}}" role="tab" >Life Lesson</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Dreams' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Dreams#custom-tabs-four-tab')}}" role="tab" >Dreams</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Experiences' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Experiences#custom-tabs-four-tab')}}" role="tab" >Experiences</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Moments' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Moments#custom-tabs-four-tab')}}" role="tab" >Special Moments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Firsts' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Firsts#custom-tabs-four-tab')}}" role="tab" >Firsts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Lasts' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Lasts#custom-tabs-four-tab')}}" role="tab" >Lasts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Spiritual' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Spiritual#custom-tabs-four-tab')}}" role="tab" >Spiritual Journey</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  {{ (@$section=='Milestone' )? 'active' : '' }}" id="custom-tabs-four-dream-tab" href="{{ url('manage/users/information/'.$user->id.'?section=Milestone#custom-tabs-four-tab')}}" role="tab" >Miletones</a>
                        </li>
                    </ul>
                </div>
                <!-- END OTHER MODULE NAVIGATION -->
                <!-- MODULE DATA BODY -->
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        @if(@$section == 'Career')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Career' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-career-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Organisation</th>
                                                <th>Role</th>
                                                <th width="13%">Start Date</th>
                                                <th width="15%">End Date</th>
                                                <th width="15%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; @endphp
                                            @if (count($user->UserCareerFeed) == 0)
                                                <tr>
                                                <td colspan='8' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($user->UserCareerFeed as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserCareer->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserCareer->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_career_video'), @$row->getUserCareer->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserCareer->file ? Common_function::get_s3_file(config('custom_config.s3_career_video'),@$row->getUserCareer->file) : asset('images/default/career_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_career_big'), @$row->getUserCareer->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserCareer->file ? Common_function::get_s3_file(config('custom_config.s3_career_thumb'),@$row->getUserCareer->file) : asset('images/default/career_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{ url(asset('images/default/career_default.jpg'))}}" data-fancybox = "gallery" >
                                                            <img src="{{ url(asset('images/default/career_default.jpg'))}}" class="img-fluid  education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif  
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserCareer->name  != '') ? @$row->getUserCareer->name  : '-'}}</td>
                                                    <td class="edu-des">{{ (@$row->getUserCareer->role != '') ? @$row->getUserCareer->role : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserCareer->start_date != '') ?  Common_function::date_with_weekday(@$row->getUserCareer->start_date) : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserCareer->end_date != '') ? Common_function::date_with_weekday(@$row->getUserCareer->end_date) : "-" }}</td>
                                                    <td class="edu-des">{{ @$row->getUserCareer->created_at ? Common_function::show_date_time($row->getUserCareer->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info "></i></td>
                                                </tr>
                                                
                                                <tr class="expandable-body-new display-none  hideTr">
                                                    <td colspan="8">
                                                        <div class="col-md-12 edu-des-space">
                                                            <span  class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserCareer->description)? Common_function::string_read_more(@$row->getUserCareer->description):'-'  !!}</p>
                                                        </div>
                                                        
                                                        <div class="col-md-12 edu-des-space">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Documents</span>                                                    
                                                                    <div class="">
                                                                    @forelse ($row->getdocuments as $dockey => $docval)
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a>
                                                                        </div>
                                                                    @empty
                                                                    <span>-</span>
                                                                    @endforelse
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>  
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">My Buddies</span>
                                                                    <p class="edu-des"> {{ @$row->getUserCareer->buddies  }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span  class="user-span-bold">My Achievements</span>
                                                                    <p class="edu-des">{{  @$row->getUserCareer->achievements }}</p>
                                                                </div>                                                            
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count) }}</p>
                                                                </div> 
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time(@$row->created_at) : '-'  }} </p>                                                                
                                                                </div> 
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time(@$row->updated_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip ?  @$row->created_ip :'-'}} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip ? @$row->updated_ip :'-' }} </p>
                                                                </div>
                                                            </div>
                                                        </div>                                                    
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif(@$section == 'WishList')
                            <div class="tab-pane fade show  slider {{ app('request')->input('section')=='WishList' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-career-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th width="18%">What is the objective?</th>
                                                <th>What is your Wish?</th>
                                                <th width="14%">By When</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if (count($user->UserWishList) == 0)
                                                <tr>
                                                    <td colspan='7' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach(@$user->UserWishList as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserWishlist->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserWishlist->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_wishlist_video'), @$row->getUserWishlist->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserWishlist->file ? Common_function::get_s3_file(config('custom_config.s3_wishlist_video'),@$row->getUserWishlist->file) : asset('images/default/wishlist_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_wishlist_big'), @$row->getUserWishlist->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserWishlist->file ? Common_function::get_s3_file(config('custom_config.s3_wishlist_thumb'),@$row->getUserWishlist->file) : asset('images/default/wishlist_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{url(asset('images/default/wishlist_default.jpg'))}}" data-fancybox = "gallery" >     
                                                            <img src="{{ url(asset('images/default/wishlist_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />
                                                        </a>   
                                                        @endif      
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserWishlist->objective != '') ?  @$row->getUserWishlist->objective : '-' }}</td>
                                                    <td class="edu-des">{{  (@$row->getUserWishlist->wish != '') ? @$row->getUserWishlist->wish : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserWishlist->by_when != '') ?  Common_function::date_with_weekday(@$row->getUserWishlist->by_when) : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserWishlist->created_at ? Common_function::show_date_time($row->getUserWishlist->created_at) : '-'  }} </td>
                                                    <td data-toggle="toggle" class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                                
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="7">
                                                        <div class="col-md-12 ">
                                                            <span class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserWishlist->description)?  Common_function::string_read_more(@$row->getUserWishlist->description) : '-'  !!}</p>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Documents</span>                                                    
                                                                    <div class="">
                                                                    @forelse ($row->getdocuments as $dockey => $docval)
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                    @empty
                                                                    <span>-</span>
                                                                    @endforelse
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count  ) }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip ?  @$row->created_ip :'-'}} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip ? @$row->updated_ip :'-' }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif(@$section == 'Ideas')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Ideas' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-ideas-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th>Why is it a big idea?</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if (count($user->UserIdeas) == 0)
                                                <tr>
                                                    <td colspan='6' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($user->UserIdeas as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserIdea->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserIdea->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_idea_video'), @$row->getUserIdea->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserIdea->file ? Common_function::get_s3_file(config('custom_config.s3_idea_video'),@$row->getUserIdea->file) : asset('images/default/ideas_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_idea_big'), @$row->getUserIdea->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserIdea->file ? Common_function::get_s3_file(config('custom_config.s3_idea_thumb'),@$row->getUserIdea->file) : asset('images/default/ideas_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{url(asset('images/default/ideas_default.jpg'))}}" data-fancybox = "gallery" >
                                                            <img src="{{ url(asset('images/default/ideas_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif         
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserIdea->title != '') ? @$row->getUserIdea->title : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserIdea->big_idea != '') ? @$row->getUserIdea->big_idea : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserIdea->created_at ? Common_function::show_date_time($row->getUserIdea->created_at) : '-'  }} </td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space">
                                                            <span class="user-span-bold" >Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserIdea->description)? Common_function::string_read_more(@$row->getUserIdea->description):'-'  !!}</p>
                                                        </div>
                                                        <div class="col-md-12 education-class" >
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold" >Documents</span>
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold">Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des">{{ @$row->like_count }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count) }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip?@$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif(@$section == 'Diary')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Diary' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-diary-tab">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-width">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th></th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Created Date</th>
                                            <th></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; @endphp
                                        @if (count(@$diary) == 0)
                                            <tr>
                                                <td colspan='5' class="center">No Records Found</td>
                                            </tr>
                                        @else
                                        @foreach($diary as $key => $row)
                                            <tr>
                                                <td class="{{ (@$row->getUserDiaryFeed->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                <td class="text-center">
                                                    @php
                                                        @$ext = explode(".",@$row->file);                                                           
                                                    @endphp
                                                    @if(@$row->file != '')
                                                        @if(@$ext[1] == 'mp4')       
                                                        <a href="{{Common_function::get_s3_file(config('custom_config.s3_diary_video'), @$row->file)}}" data-fancybox = "gallery" >                 
                                                            <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->file ? Common_function::get_s3_file(config('custom_config.s3_diary_video'),@$row->file) : asset('images/default/diary_default.png')}}"></video> </div>
                                                        @else
                                                        <a href="{{Common_function::get_s3_file(config('custom_config.s3_diary_big'), @$row->file)}}" data-fancybox = "gallery" >
                                                            <img src="{{ @$row->file ? Common_function::get_s3_file(config('custom_config.s3_diary_thumb'),@$row->file) : asset('images/default/diary_default.png') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                alt="VioGraf" />
                                                        @endif
                                                    @else 
                                                    <a href="{{ url(asset('images/default/diary_default.png'))}}" data-fancybox = "gallery" >
                                                        <img src="{{ url(asset('images/default/diary_default.png'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                    @endif      
                                                </td>
                                                <td class="edu-des desc-info">{!! Common_function::string_read_more(@$row->description)?Common_function::string_read_more(@$row->description):'-' !!}</td>
                                                <td class="edu-des">{{ @$row->date ? date("jS M, Y", strtotime($row->date)) : '-'  }} </td>
                                                <td class="edu-des">{{ @$row->getUserDiaries->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </td>
                                                <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>                                            
                                            </tr>
                                            <tr class="expandable-body-new display-none hideTr">
                                                <td colspan="5">
                                                    <div class="col-md-12 education-class" >
                                                        <div class="row">
                                                            <div class="col-md-4 edu-des-space docs-alingment">
                                                                <span class="user-span-bold">Documents</span>
                                                                <div class="">
                                                               
                                                                    @forelse ($row->getUserDiaryFeed->getdocuments as $dockey => $docval)
                                                                    <div id="doc_{{$docval->id}}" class="row">
                                                                        <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                        <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                    </div>
                                                                    @empty
                                                                    <span>-</span>
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4  edu-des-space">
                                                                <span class="user-span-bold" >Privacy</span>
                                                                <p class="edu-des">{{ucfirst($row->getUserDiaries->privacy)}}</p>
                                                            </div>
                                                            <div class="col-md-4 ">
                                                                <span class="user-span-bold">Like Count</span>
                                                                <p class="edu-des"> {{ @$row->getUserDiaries->like_count }} </p>
                                                            </div>                                                    
                                                            <div class="col-md-4">
                                                                <span class="user-span-bold">Comment Count</span>
                                                                <p class="edu-des">{{  nl2br(@$row->getUserDiaries->comment_count) }}</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <span class="user-span-bold">Created Date</span>
                                                                <p class="edu-des"> {{ @$row->getUserDiaries->created_at ? Common_function::show_date_time($row->getUserDiaries->created_at) : '-'  }} </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <span class="user-span-bold">Updated Date</span>
                                                                <p class="edu-des"> {{ @$row->getUserDiaries->updated_at ? Common_function::show_date_time($row->getUserDiaries->updated_at) : '-'  }} </p>
                                                            </div>                                                    
                                                            <div class="col-md-4">
                                                                <span class="user-span-bold">Created Ip</span>
                                                                <p class="edu-des"> {{ @$row->getUserDiaries->created_ip?@$row->getUserDiaries->created_ip:'-'  }} </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <span class="user-span-bold">Updated Ip</span>
                                                                <p class="edu-des"> {{ @$row->getUserDiaries->updated_ip?@$row->getUserDiaries->updated_ip:'-'  }} </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach 
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @elseif(@$section == 'LifeLessons')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='LifeLessons' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-lifelesson-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                        <tr>
                                            <th width="1%">#</th>
                                            <th width="10%"></th>
                                            <th>Title</th>
                                            <th width="14%">Created Date</th>
                                            <th width="8%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; @endphp
                                            @if (count($user->UserLifeLessons) == 0)
                                                <tr>
                                                    <td colspan='5' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($user->UserLifeLessons as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserLifeLesson->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserLifeLesson->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_lifelesson_video'), @$row->getUserLifeLesson->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserLifeLesson->file ? Common_function::get_s3_file(config('custom_config.s3_lifelesson_video'),@$row->getUserLifeLesson->file) : asset('images/default/lifelesson_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_lifelesson_big'), @$row->getUserLifeLesson->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserLifeLesson->file ? Common_function::get_s3_file(config('custom_config.s3_lifelesson_thumb'),@$row->getUserLifeLesson->file) : asset('images/default/lifelesson_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else
                                                        <a href="{{url(asset('images/default/lifelesson_default.jpg'))}}" data-fancybox = "gallery" > 
                                                            <img src="{{ url(asset('images/default/lifelesson_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif      
                                                    </td>
                                                    <td class="edu-des">{{(@$row->getUserLifeLesson->title  != '') ? @$row->getUserLifeLesson->title : '-'}}</td>
                                                    <td class="edu-des">{{ @$row->getUserLifeLesson->created_at ? Common_function::show_date_time($row->getUserLifeLesson->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center newtoggle header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="5">
                                                        <div class="col-md-12 edu-des-space ">
                                                            <span class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserLifeLesson->description)? Common_function::string_read_more(@$row->getUserLifeLesson->description):'-'  !!}</p>
                                                        </div>
                                                                                            
                                                        <div class="col-md-12 education-class">
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold" >Documents</span>                                            
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count) }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip ?@$row->created_ip :'-' }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif(@$section == 'Dreams')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Dreams' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-dreams-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th width="14%">Creaqted Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; @endphp
                                            @if (count($user->UserDreams) == 0)
                                            <tr>
                                                <td colspan='6' class="center">No Records Found</td>
                                            </tr>
                                            @else
                                            @foreach($user->UserDreams as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserDream->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserDream->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_dream_video'), @$row->getUserDream->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserDream->file ? Common_function::get_s3_file(config('custom_config.s3_dream_video'),@$row->getUserDream->file) : asset('images/default/dream_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_dream_big'), @$row->getUserDream->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserDream->file ? Common_function::get_s3_file(config('custom_config.s3_dream_thumb'),@$row->getUserDream->file) : asset('images/default/dream_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{ url(asset('images/default/dream_default.jpg'))}}" data-fancybox = "gallery" > 
                                                            <img src="{{ url(asset('images/default/dream_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif     
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserDream->title != '') ? @$row->getUserDream->title : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserDream->created_at ? Common_function::show_date_time($row->getUserDream->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space ">
                                                            <span class="user-span-bold" >Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserDream->description)? Common_function::string_read_more(@$row->getUserDream->description):'-'  !!}</p>
                                                        </div>
                                                        <div class="col-md-12 education-class">
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold" >Documents</span>                                                
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count) }}</p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip? @$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                        @elseif(@$section == 'Experiences')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Experiences' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-xxperiences-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th width="14%">Date</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if (count($user->UserExperiences) == 0)
                                                <tr>
                                                    <td colspan='6' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($user->UserExperiences as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserExperience->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserExperience->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_experience_video'), @$row->getUserExperience->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align">
                                                                    <video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserExperience->file ? Common_function::get_s3_file(config('custom_config.s3_experience_video'),@$row->getUserExperience->file) : asset('images/default/experience_default.jpg')}}"></video>
                                                                </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_experience_big'), @$row->getUserExperience->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserExperience->file ? Common_function::get_s3_file(config('custom_config.s3_experience_thumb'),@$row->getUserExperience->file) : asset('images/default/experience_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{url(asset('images/default/experience_default.jpg'))}}" data-fancybox = "gallery" >
                                                            <img src="{{ url(asset('images/default/experience_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif                                                                      
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserExperience->title != '') ? @$row->getUserExperience->title : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserExperience->date != '') ? date("jS M, Y", strtotime(@$row->getUserExperience->date)) : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserExperience->created_at ? Common_function::show_date_time($row->getUserExperience->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space ">
                                                            <span class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserExperience->description)? Common_function::string_read_more(@$row->getUserExperience->description):'-'  !!}</p>
                                                        </div>                                    
                                            
                                                        <div class="col-md-12 education-class" >
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold">Documents</span>                                                        
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)                                                        
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>                                        
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count) }}</p>                                                
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">
                                                                    Created Date
                                                                    </span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>                                                        
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">
                                                                        Updated Date
                                                                    </span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip? @$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>                    
                                </div>
                            </div>
                        @elseif(@$section == 'Moments')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Moments' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-moments-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if (count($user->UserMoments) == 0)
                                                <tr>
                                                    <td colspan='6' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($user->UserMoments as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">                                                    
                                                        @if($row->getUserMomentImage->count() > 0)   
                                                            @foreach(@$row->getUserMomentImage as $key=>$val) 
                                                                @php
                                                                    @$ext = explode(".",@$val->file);                                                           
                                                                @endphp                        
                                                                    @if($key <= 0)
                                                                        @php
                                                                            $count = @$row->getUserMomentImage->count();                                                          
                                                                            $remianig_count = $count - 1;                                                       
                                                                        @endphp
                                                                        <div class="bg-image">
                                                                            @if(@$ext[1] == 'mp4') 
                                                                                <a href="{{ (@$val->file!='') ? Common_function::get_s3_file(config('custom_config.s3_moment_video'), @$val->file) : asset('images/default/dream_default.jpg')}}" data-fancybox = "gallery" 
                                                                                    data-caption='<div class="image_fancybox_details"><b>Feed Id :</b> {{@$row->id}}</div>'>
                                                                                    @if($count > 1)
                                                                                    <span class="icon-span">
                                                                                        <span class="image-count-span"><i class="fas fa-plus plus-icon"></i>{{$remianig_count}}</span>
                                                                                    </span>
                                                                                    @endif
                                                                                    <div class="list-image-prof">
                                                                                        <video class="video-tag"><source id="videoSource"/ src="{{@$val->file ? Common_function::get_s3_file(config('custom_config.s3_moment_video'),@$val->file) : asset('images/default/moment_default.jpg')}}"></video>
                                                                                    </div>                                                            
                                                                                </a>
                                                                            @else                                                     
                                                                                <a href="{{ (@$val->file!='') ? Common_function::get_s3_file(config('custom_config.s3_moment_big'), @$val->file) : asset('images/default/moment_default.jpg')}}" data-fancybox = "gallery"
                                                                                data-caption='<div class="image_fancybox_details"><b>Feed Id :</b> {{@$row->id}}</div>'>
                                                                                    @if($count > 1)
                                                                                    <span class="icon-span">
                                                                                        <span class="image-count-span"><i class="fas fa-plus plus-icon"></i>{{$remianig_count}}</span>
                                                                                    </span>
                                                                                    @endif
                                                                                    <img src="{{ (@$val->file != '') ? Common_function::get_s3_file(config('custom_config.s3_moment_thumb'),@$val->file) : asset('images/default/moment_default.jpg') }}" 
                                                                                    class="list-image-prof"  alt="VioGraf" />
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    @else                                                            
                                                                        @if(@$ext[1] == 'mp4') 
                                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_moment_video'),@$val->file)}}" style="display: none"  data-fancybox = "gallery"
                                                                            data-caption='<div class="image_fancybox_details"><b>Feed Id :</b> {{@$row->id}}</div>'>  
                                                                                <div class="list-image-prof">
                                                                                    <video class="video-tag"><source id="videoSource"/ src="{{Common_function::get_s3_file(config('custom_config.s3_moment_video'),@$val->file)}}"></video>
                                                                                </div>                                                            
                                                                            </a>
                                                                        @else
                                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_moment_big'),@$val->file)}}" style="display: none"  data-fancybox = "gallery"
                                                                            data-caption='<div class="image_fancybox_details"><b>Feed Id :</b> {{@$row->id}}</div>'>
                                                                                    <img src="{{Common_function::get_s3_file(config('custom_config.s3_moment_thumb'),@$val->file)}}" class="list-image-prof">
                                                                            </a>
                                                                        @endif                                                           
                                                                    @endif    
                                                            @endforeach
                                                        @else 
                                                        <a href="{{ url(asset('images/default/moment_default.jpg'))}}" data-fancybox = "gallery">
                                                            <img src="{{ url(asset('images/default/moment_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif                                                                                   
                                                    </td>
                                                    <td class="edu-des">{{ @$row->getUserMoment->title ? @$row->getUserMoment->title : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserMoment->created_at ? Common_function::show_date_time($row->getUserMoment->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space">
                                                            <span class="user-span-bold ">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserMoment->description)? Common_function::string_read_more(@$row->getUserMoment->description):'-'  !!}</p>
                                                        </div>                                    
                                            
                                                        <div class="col-md-12 education-class">
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold">Documents</span>                                                        
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)                                                        
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class=" doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>                                        
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>                                                   
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count  ) }}  </p>                                                
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">
                                                                    Created Date
                                                                    </span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>                                                        
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">
                                                                        Updated Date
                                                                    </span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip? @$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>    
                                </div>                
                            </div>
                        @elseif(@$section == 'Firsts')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Firsts' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-firsts-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if(count(@$first_data) == 0)
                                                <tr>
                                                    <td colspan='6' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach(@$first_data as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserFirst->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserFirst->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_first_video'), @$row->getUserFirst->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserFirst->file ? Common_function::get_s3_file(config('custom_config.s3_first_video'),@$row->getUserFirst->file) : asset('images/default/firstlast_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_first_big'), @$row->getUserFirst->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserFirst->file ? Common_function::get_s3_file(config('custom_config.s3_first_thumb'),@$row->getUserFirst->file) : asset('images/default/firstlast_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{ url(asset('images/default/firstlast_default.jpg'))}}" data-fancybox = "gallery">
                                                            <img src="{{ url(asset('images/default/firstlast_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif                                                                      
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserFirst->title != '') ? @$row->getUserFirst->title  : '-'}}</td>
                                                    <td class="edu-des">{{ @$row->getUserFirst->created_at ? Common_function::show_date_time($row->getUserFirst->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>                                        
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space">
                                                            <span class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserFirst->description)? Common_function::string_read_more(@$row->getUserFirst->description):'-'  !!}</p>
                                                        </div>                                    
                                            
                                                        <div class="col-md-12 education-class">
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold">Documents</span>                                                        
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)                                                        
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>                                        
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>                                                   
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count  ) }}  </p>                                                
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>                                                        
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                   
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip? @$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>                    
                                </div>
                            </div>
                        @elseif(@$section == 'Lasts')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Lasts' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-lasts-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if (count(@$last_data) == 0)
                                                <tr>
                                                    <td colspan='6' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($last_data as $row)
                                                <tr>
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserLast->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserLast->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_last_video'), @$row->getUserLast->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserLast->file ? Common_function::get_s3_file(config('custom_config.s3_last_video'),@$row->getUserLast->file) : asset('images/default/firstlast_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_last_big'), @$row->getUserLast->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserLast->file ? Common_function::get_s3_file(config('custom_config.s3_last_thumb'),@$row->getUserLast->file) : asset('images/default/firstlast_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                            <a href="{{ url(asset('images/default/firstlast_default.jpg'))}}" data-fancybox = "gallery">
                                                            <img src="{{ url(asset('images/default/firstlast_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif                                                                      
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserLast->title != '') ? @$row->getUserLast->title : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserLast->created_at ? Common_function::show_date_time($row->getUserLast->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>                                    
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space ">
                                                            <span class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserLast->description)? Common_function::string_read_more(@$row->getUserLast->description):'-'  !!}</p>
                                                        </div>                                    
                                            
                                                        <div class="col-md-12 education-class">
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold">Documents</span>                                                        
                                                                    <div class="">
                                                                        @forelse ($row->getdocuments as $dockey => $docval)                                                        
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>                                        
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count  ) }}  </p>                                                
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>                                                        
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip? @$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>                    
                                </div>
                            </div>
                        @elseif(@$section == 'Spiritual')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Spiritual' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-spiritual-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                    <thead>
                                        <tr>
                                            <th width="1%">#</th>
                                            <th width="10%"></th>
                                            <th>Practice</th>
                                            <th>When Started</th>                                                                                                                        
                                            <th width="14%">Created Date</th>
                                            <th width="8%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; 
                                        @endphp
                                        @if (count($user->UserSpirituals) == 0)
                                            <tr>
                                                <td colspan='6' class="center">No Records Found</td>
                                            </tr>
                                        @else
                                        @foreach($user->UserSpirituals as $row)
                                            <tr>
                                                <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                <td class="text-center">
                                                    @php
                                                        @$ext = explode(".",@$row->getUserSpiritualJourney->file);                                                           
                                                    @endphp
                                                    @if(@$row->getUserSpiritualJourney->file != '')
                                                        @if(@$ext[1] == 'mp4')       
                                                        <a href="{{Common_function::get_s3_file(config('custom_config.s3_spiritual_journey_video'), @$row->getUserSpiritualJourney->file)}}" data-fancybox = "gallery" >                 
                                                            <div class="education-user-img img-responsive img-circle video_img img-fluid video-align"><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserSpiritualJourney->file ? Common_function::get_s3_file(config('custom_config.s3_spiritual_journey_video'),@$row->getUserSpiritualJourney->file) : asset('images/default/spiritualjourney_default.jpg')}}"></video> </div>
                                                        @else
                                                        <a href="{{Common_function::get_s3_file(config('custom_config.s3_spiritual_journey_big'), @$row->getUserSpiritualJourney->file)}}" data-fancybox = "gallery" >
                                                            <img src="{{ @$row->getUserSpiritualJourney->file ? Common_function::get_s3_file(config('custom_config.s3_spiritual_journey_thumb'),@$row->getUserSpiritualJourney->file) : asset('images/default/spiritualjourney_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                alt="VioGraf" />
                                                        @endif
                                                    @else 
                                                        <a href="{{ url(asset('images/default/spiritualjourney_default.jpg'))}}" data-fancybox = "gallery">
                                                        <img src="{{ url(asset('images/default/spiritualjourney_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                    @endif                                                                      
                                                </td>
                                                <td class="edu-des">{{ @$row->getUserSpiritualJourney->practice ? @$row->getUserSpiritualJourney->practice : '-' }}</td>
                                                <td class="edu-des">{{ @$row->getUserSpiritualJourney->when_started ? @$row->getUserSpiritualJourney->when_started : '-' }}</td>                                            
                                                <td class="edu-des">{{ @$row->getUserSpiritualJourney->created_at ? Common_function::show_date_time($row->getUserSpiritualJourney->created_at) : '-'  }}</td>
                                                <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                            </tr>                                    
                                    
                                            <tr class="expandable-body-new display-none hideTr">
                                                <td colspan=12>
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <strong>Description</strong>
                                                                <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserSpiritualJourney->description)? Common_function::string_read_more(@$row->getUserSpiritualJourney->description):'-'  !!}</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Why Started</strong>
                                                                <p class="edu-des">{{ @$row->getUserSpiritualJourney->why_started ? @$row->getUserSpiritualJourney->why_started : '-' }}</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Who Influenced</strong>
                                                                <p class="edu-des">{{ @$row->getUserSpiritualJourney->who_influenced ? @$row->getUserSpiritualJourney->who_influenced : '-' }}</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Benefit</strong>
                                                                <p class="edu-des">{{ @$row->getUserSpiritualJourney->benefit ? @$row->getUserSpiritualJourney->benefit : '-' }}</p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Documents</strong>                                                        
                                                                <div class="">
                                                                    @forelse ($row->getdocuments as $dockey => $docval)                                                        
                                                                    <div id="doc_{{$docval->id}}" class="row">
                                                                        <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                        <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                    </div>
                                                                    @empty
                                                                    <strong>-</strong>
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Privacy</strong>
                                                                <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                            </div>
                                                            <div class="col-md-4 ">
                                                                <strong>Like Count</strong>
                                                                <p class="edu-des"> {{ @$row->like_count }} </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Comment Count</strong>
                                                                <p class="edu-des">{{  nl2br(@$row->comment_count  ) }}  </p>                                                
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Created Date</strong>
                                                                <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>                                                        
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Updated Date</strong>
                                                                <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Created Ip</strong>
                                                                <p class="edu-des"> {{ @$row->created_ip? @$row->created_ip:'-'  }} </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <strong>Updated Ip</strong>
                                                                <p class="edu-des"> {{ @$row->updated_ip?@$row->updated_ip:'-'  }} </p>
                                                            </div>                                    
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach 
                                        @endif
                                    </tbody>
                                    </table>  
                                </div>                  
                            </div>  
                        @elseif(@$section == 'Milestone')
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Milestone' ? 'active':''}} "  role="tabpanel" aria-labelledby="custom-tabs-four-milestone-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-width">
                                        <thead>
                                            <tr>
                                                <th width="1%">#</th>
                                                <th width="10%"></th>
                                                <th>Title</th>
                                                <th width="14%">Achieve Date</th>
                                                <th width="14%">Created Date</th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; 
                                            @endphp
                                            @if (count($milestone) == 0)
                                                <tr>
                                                    <td colspan='6' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($milestone as $row)
                                                <tr>
                                                    <td class="{{ (@$row->getUserMilestoneFeed->is_save_as_draft == 1) ? 'admin_color_class' : ''}}">{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->file);                                                           
                                                        @endphp
                                                        @if(@$row->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_milestone_video'), @$row->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img img-fluid video-align">
                                                                    <video class="video-tag"><source id="videoSource"/ src="{{@$row->file ? Common_function::get_s3_file(config('custom_config.s3_milestone_video'),@$row->file) : asset('images/default/milestone_default.jpg')}}"></video>
                                                                </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_milestone_big'), @$row->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->file ? Common_function::get_s3_file(config('custom_config.s3_milestone_thumb'),@$row->file) : asset('images/default/milestone_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                            <a href="{{ url(asset('images/default/milestone_default.jpg'))}}" data-fancybox = "gallery">
                                                            <img src="{{ url(asset('images/default/milestone_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif                                                                      
                                                    </td>
                                                    <td class="edu-des">{{  (@$row->title != '') ? @$row->title : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->achieve_date != '') ? date("jS M, Y", strtotime(@$row->achieve_date)) : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr">
                                                    <td colspan="6">
                                                        <div class="col-md-12 edu-des-space ">
                                                            <span class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->description)? Common_function::string_read_more(@$row->description):'-'  !!}</p>
                                                        </div>                                    
                                            
                                                        <div class="col-md-12 education-class" >
                                                            <div class="row">
                                                                <div class="col-md-4 edu-des-space docs-alingment">
                                                                    <span class="user-span-bold">Documents</span>                                                        
                                                                    <div class="">
                                                                        @forelse ($row->getUserMilestoneFeed->getdocuments as $dockey => $docval)                                                        
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a></p>
                                                                        </div>
                                                                        @empty
                                                                        <span>-</span>
                                                                        @endforelse
                                                                    </div>
                                                                </div>                                        
                                                                <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->getUserMilestoneFeed->privacy)}}</p>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->getUserMilestoneFeed->like_count }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->getUserMilestoneFeed->comment_count) }}</p>                                                
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">
                                                                    Created Date
                                                                    </span>
                                                                    <p class="edu-des"> {{ @$row->getUserMilestones->created_at ? Common_function::show_date_time($row->getUserMilestones->created_at) : '-'  }} </p>                                                        
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">
                                                                        Updated Date
                                                                    </span>
                                                                    <p class="edu-des"> {{ @$row->getUserMilestones->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>                                                    
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->getUserMilestones->created_ip? @$row->getUserMilestones->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->getUserMilestones->updated_ip?@$row->getUserMilestones->updated_ip:'-'  }} </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                            @endif
                                        </tbody>
                                    </table>     
                                </div>               
                            </div>
                        @else
                            <div class="tab-pane fade show  {{ app('request')->input('section')=='Education' || app('request')->input('section')=='' ? 'active':''}} " id="custom-tabs-four-education" role="tabpanel" aria-labelledby="custom-tabs-four-education-tab">
                            <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-width">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th></th>
                                            <th> School/College</th>
                                            <th>Start Date</th>
                                            <th> End Date</th>
                                            <th> Created Date</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=1; @endphp
                                            @if (count(@$user->UserEducationFeed) == 0)
                                                <tr>
                                                    <td colspan='7' class="center">No Records Found</td>
                                                </tr>
                                            @else
                                            @foreach($user->UserEducationFeed as $row)
                                            
                                                <tr data-widget="expandable-table" aria-expanded="false" >
                                                    <td class="{{ (@$row->is_save_as_draft == 1) ? 'admin_color_class' : ''}}" >{{  $i++ }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            @$ext = explode(".",@$row->getUserEducation->file);                                                           
                                                        @endphp
                                                        @if(@$row->getUserEducation->file != '')
                                                            @if(@$ext[1] == 'mp4')       
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_education_video'), @$row->getUserEducation->file)}}" data-fancybox = "gallery" >                 
                                                                <div class="education-user-img img-responsive img-circle video_img "><video class="video-tag"><source id="videoSource"/ src="{{@$row->getUserEducation->file ? Common_function::get_s3_file(config('custom_config.s3_education_video'),@$row->getUserEducation->file) : asset('images/default/education_default.jpg')}}"></video> </div>
                                                            @else
                                                            <a href="{{Common_function::get_s3_file(config('custom_config.s3_education_big'), @$row->getUserEducation->file)}}" data-fancybox = "gallery" >
                                                                <img src="{{ @$row->getUserEducation->file ? Common_function::get_s3_file(config('custom_config.s3_education_thumb'),@$row->getUserEducation->file) : asset('images/default/education_default.jpg') }}" class="education-user-img img-responsive img-circle video_img img-fluid"
                                                                    alt="VioGraf" />
                                                            @endif
                                                        @else 
                                                        <a href="{{ url(asset('images/default/education_default.jpg'))}}" data-fancybox = "gallery">
                                                            <img src="{{ url(asset('images/default/education_default.jpg'))}}" class="img-fluid education-user-img img-responsive img-circle" alt="VioGraf" />   </a>
                                                        @endif      
                                                    </td>
                                                    <td class="edu-des">{{ (@$row->getUserEducation->name != '') ? @$row->getUserEducation->name : '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserEducation->start_date != '') ? Common_function::date_with_weekday(@$row->getUserEducation->start_date) : 
                                                        '-' }}</td>
                                                    <td class="edu-des">{{ (@$row->getUserEducation->end_date != '') ? Common_function::date_with_weekday(@$row->getUserEducation->end_date) : '-' }}</td>
                                                    <td class="edu-des">{{ @$row->getUserEducation->created_at ? Common_function::show_date_time($row->getUserEducation->created_at) : '-'  }}</td>
                                                    <td data-toggle="toggle"  class="center newtoggle header"><i class="fa fa-chevron-right arrow-user-info" ></i></td>
                                                </tr>
                                        
                                                <tr class="expandable-body-new display-none hideTr ">
                                                    <td colspan="7">
                                                        <div class="col-md-12 edu-des-space edu-listalign">
                                                            <span  class="user-span-bold">Description</span>
                                                            <p class="edu-des desc-info">{!! Common_function::string_read_more(@$row->getUserEducation->description)? Common_function::string_read_more(@$row->getUserEducation->description):'-'  !!}</p>
                                                        </div>
                                                        <div class="col-md-12 edu-des-space docs-alingment ">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Documents</span>                                                    
                                                                    <div class="">
                                                              
                                                                    @forelse ($row->getdocuments as $dockey => $docval)
                                                                        <div id="doc_{{$docval->id}}" class="row">
                                                                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$row->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="doc-link-admin docfiles text-truncate" download>
                                                                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid doc-pin-admin" alt="VioGraf"></span>{{' '.$docval->file}}</a>
                                                                        </div>
                                                                    @empty
                                                                    <span>-</span>
                                                                    @endforelse
                                                                    </div>
                                                                </div>   
                                                                 <div class="col-md-4  edu-des-space">
                                                                    <span class="user-span-bold" >Privacy</span>
                                                                    <p class="edu-des">{{ucfirst($row->privacy)}}</p>
                                                                </div>                                                   
                                                                <div class="col-md-4 ">
                                                                    <span class="user-span-bold">Like Count</span>
                                                                    <p class="edu-des"> {{ @$row->like_count }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Comment Count</span>
                                                                    <p class="edu-des">{{  nl2br(@$row->comment_count) }} </p>
                                                                </div>  
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">My school mates</span>
                                                                    <p class="edu-des"> {{ @$row->getUserEducation->my_buddies  }} </p>                                                            
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span  class="user-span-bold">Class/Grade</span>
                                                                    <p class="edu-des">{{  @$row->getUserEducation->achievements }}  </p>                                                            
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Date</span>
                                                                    <p class="edu-des"> {{ @$row->created_at ? Common_function::show_date_time($row->created_at) : '-'  }} </p>
                                                                </div>                                                                                                               
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Date</span>
                                                                    <p class="edu-des"> {{ @$row->updated_at ? Common_function::show_date_time($row->updated_at) : '-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Created Ip</span>
                                                                    <p class="edu-des"> {{ @$row->created_ip?@$row->created_ip:'-'  }} </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <span class="user-span-bold">Updated Ip</span>
                                                                    <p class="edu-des"> {{ @$row->updated_ip ? @$row->updated_ip :'-' }} </p>
                                                                </div>
                                                            </div>
                                                        </div>                                        
                                                    </td>
                                                </tr>
                                            @endforeach  
                                            @endif
                                        </tbody>
                                    </table>
                            </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- END MODULE DATA BODY -->                
            </div>
             <!-- END OTHER MODULES DATA(LAST CARD) -->  

            
        </div>
	</div>
   
@stop