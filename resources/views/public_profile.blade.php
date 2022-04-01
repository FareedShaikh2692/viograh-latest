@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="user_small_detail sectionbottom30">
    
        <div class="sectionbox ">
            <div class="user_detail_row">
                <div class="user_detail_img">
                    <img src="{{@$results->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),@$results->profile_image) : url('images/userdefault-1.svg')}}" class="img-fluid" alt="VioGraf"/> 
                </div>
                <div class="user_detail_details">               
                    <h1 class="main_title">{{@$results->first_name}} {{@$results->last_name}}</h1>
                    @if(@$results->places_lived)
                    <p>
                        <span> 
                            <img src="{{asset('/images/icons/location.svg')}}" class="img-fluid" alt="VioGraf"/> 
                        </span>{{@$results->places_lived ? @$results->places_lived : '-'}}</p>
                    @endif
                    @if(@$results->biography)                        
                        <p class="desc-info">{!! $results->biography ? Common_function::string_read_more(@$results->biography) :'-' !!}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>  
    <div class="about_details_tabs tabarea2 sectionbottom30">
        <div class="sectionbox ">
            <h2 class="main_title">About</h2>
            <div class="tabbox">
                <ul id="tabs2" class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a id="Myself" href="#myself" class="nav-link active" data-toggle="tab"
                            role="tab">Myself</a>
                    </li>
                    <li class="nav-item">
                        <a id="Education" href="#education" class="nav-link" data-toggle="tab" role="tab">Education</a>
                    </li>
                    <li class="nav-item">
                        <a id="Career" href="#career" class="nav-link" data-toggle="tab" role="tab">Career</a>
                    </li>  
                    <li class="nav-item">
                        <a id="Milestone" href="#milestone" class="nav-link" data-toggle="tab" role="tab">Milestone</a>
                    </li>                    
                </ul>
                <div id="content2" class="tab-content" role="tablist">
                    <div id="myself" class="card tab-pane fade show active" role="tabpanel"
                        aria-labelledby="Myself">
                        <div class="card-header" role="tab" id="heading-A">
                            <div class="row">                    
                                <div class="col-md-12">
                                    <ul class="about_detaillist">
                                    <div class="row">   
                                        <div class="col-sm-12">
                                            <div class="public-profile-details" >
                                                <h3>Essence Of Life</h3>
                                                @if(@$results->essence_of_life != '')
                                                <h4><span>{{@$results->essence_of_life}}</span></h4>
                                                @else
                                                <span>-</span>
                                                    @endif
                                            </div>
                                        </div>                                  
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Places Lived</h3>
                                                @if(@$results->places_lived !='')
                                                <h4><span>{{@$results->places_lived}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Blood Group</h3>
                                                @if(@$results->blood_group !='')
                                                <h4><span>{{@$results->blood_group}}</span></h4>
                                                @else
                                                <span>-</span>
                                                    @endif
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Profession</h3>
                                                @if(@$results->profession !='')
                                                <h4><span>{{@$results->profession}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6"> 
                                            <div class="public-profile-details" >
                                                <h3>Place Of Birth</h3>
                                                @if(@$results->place_of_birth !='')
                                                <h4><span>{{@$results->place_of_birth}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">     
                                            <div class="public-profile-details" >
                                                <h3>Favourite movies</h3>
                                                @if(!empty(@$results->favourite_movie))
                                                <h4><span>{{@$results->favourite_movie}}</span></h4>
                                                @else
                                                <span>-</span>
                                                    @endif
                                            </div>             
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Favourite songs</h3>
                                                @if(!empty(@$results->favourite_song))
                                                <h4><span>{{@$results->favourite_song}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Favourite books</h3>
                                                @if(@$results->favourite_book != '')
                                                <h4><span>{{@$results->favourite_book}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Favourite Eating Joints</h3>
                                                @if(@$results->favourite_eating_joints !='')
                                                <h4><span>{{@$results->favourite_eating_joints}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Hobbies</h3>
                                                @if(@$results->hobbies != '')
                                                <h4><span>{{@$results->hobbies}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                     
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Food</h3>
                                                @if(@$results->food !='')
                                                <h4><span>{{@$results->food}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Role Model</h3>
                                                @if(@$results->role_model != '')
                                                    <h4><span>{{@$results->role_model}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Car</h3>
                                                @if(@$results->car !='')
                                                <h4><span>{{@$results->car}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-6">           
                                                <div class="public-profile-details" >
                                                <h3>Brand</h3>
                                                @if(@$results->brand !='')
                                                <h4><span>{{@$results->brand}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>               
                                        </div>
                                        
                                        <div class="col-sm-6">    
                                            <div class="public-profile-details" >
                                                <h3>TV Shows</h3>
                                                @if(@$results->tv_shows !='')
                                                <h4><span>{{@$results->tv_shows}}</span></h4>
                                                @else
                                                <span>-</span>
                                                    @endif
                                            </div>                         
                                        </div>
                                        
                                        <div class="col-sm-6">    
                                                <div class="public-profile-details" >
                                                <h3>Actors</h3>
                                                @if(@$results->actors !='')
                                                <h4><span>{{@$results->actors}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>                              
                                        </div>
                                        
                                        <div class="col-sm-6">    
                                            <div class="public-profile-details" >
                                                <h3>Sports Person</h3>
                                                @if(@$results->sports_person !='')
                                                    <h4><span>{{@$results->sports_person}}</span></h4>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </div>                                                          
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Politician</h3>
                                                @if(@$results->politician != '') 
                                                    <h4><span>{{@$results->politician}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>           
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Diet</h3>
                                                @if(@$results->diet != '')
                                                <h4><span>{{@$results->diet}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>         
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="public-profile-details" >
                                                <h3>Zodiac Sign</h3>
                                                @if(@$results->zodiac_sign !='')
                                                <h4><span>{{@$results->zodiac_sign}}</span></h4>
                                                @else
                                                <span>-</span>
                                                @endif
                                            </div>       
                                        </div>
                                    </div>                                    
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="education" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
                        <div class="card-header" role="tab" id="Education">
                            <div class="my-profile-page ">
                                <div class="my-education">
                                    <ul class="my-education-list_area">
                                        @if (@$education_data->count() == 0)
                                            <p class="cls-no-record">No Records Found</p>
                                        @else
                                        @foreach(@$education_data as $key=>$row )
                                        
                                        <li class="my-education-listbox">
                                            <div class="new_education_img_section">                                               
                                                @php
                                                    @$ext = explode(".",@$row->file);                            
                                                @endphp
                                                
                                                @if(!empty(@$row->file))
                                                    @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                                                        <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_education_video'),@$row->file) }}"></video>
                                                    @else                            
                                                        <div class="education-img" style="background-image:url({{ Common_function::get_s3_file(config('custom_config.s3_education_big'),@$row->file) }});">
                                                        </div>
                                                    @endif 
                                                @else
                                                    <div class="education-img" style="background-image:url('{{asset('images/default/education_default.jpg')}}')">
                                                    </div>
                                                @endif  
                                            </div>
                                            <div class="education-detail-public-profile">                            
                                            <a href="{{url('/education/information/'.@$row->feed_id)}}"><h3>{{ @$row->name }}</h3></a>
                                                <ul class="designation_year">
                                                    <li><span><img src="{{asset ('images/icons/first_rank.svg') }}" alt="VioGraf"/></span><p>
                                                    {{ (@$row->achievements != '') ? @$row->achievements : '-' }}</p></li>
                                                    <li><p>
                                                    @if (@$row->start_date == '' && @$row->end_date == '')
                                                        - 
                                                    @else
                                                        @if (@$row->start_date != '' && @$row->end_date != '')
                                                       
                                                            {{date('M Y',strtotime(@$row->start_date))}} - {{date(' M Y',strtotime(@$row->end_date))}}
                                                        @else
                                                            {{date(' M Y',strtotime(@$row->start_date)) .' - '. 'Pursuing'}}
                                                        @endif
                                                    @endif
                                                   </p></li>                            
                                                </ul> 
                                                <div class="predovicflex">
                                                    <span class=""><img src="{{asset ('images/icons/predovic.svg') }}" alt="VioGraf"/></span>
                                                    <p class="colleagues-p-tag">{{ @$row->my_buddies }}</p>
                                                </div>                       
                                                <p>
                                                    @php
                                                        $str_des = @$row->description;
                                                    @endphp
                                                    @if(strlen($str_des)>=135)
                                                        {{ str_limit($str_des, 135, $end='') }} 
                                                        <a href="{{url('/education/information/'.@$row->feed_id)}}">More info.</a>
                                                    @else
                                                    {{ $str_des }}
                                                    @endif
                                                </p>
                                            </div> 
                                        </li>
                                        @endforeach
                                        @endif                      
                                    </ul>
                                </div>                            
                            </div>
                        </div>
                    </div>
                    <div id="career" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
                        <div class="card-header" role="tab" id="Career">
                            <div class="my-profile-page">
                                <div class="my-education">
                                    <ul class="my-education-list_area">
                                        @if (count(@$career_data) == 0)
                                        <p class="cls-no-record">No Records Found</p>
                                        @else
                                        @foreach($career_data as $key=>$row )
                                            <li class="my-education-listbox">
                                            <div class="new_education_img_section">                                               
                                                @php
                                                    @$ext = explode(".",@$row->file);                            
                                                @endphp
                                                
                                                @if(!empty(@$row->file))
                                                    @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                                                        <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_career_video'),@$row->file) }}"></video>
                                                    @else                            
                                                        <div class="education-img" style="background-image:url({{ Common_function::get_s3_file(config('custom_config.s3_career_big'),@$row->file) }});">
                                                        </div>
                                                    @endif 
                                                @else
                                                    <div class="education-img" style="background-image:url('{{asset('images/default/career_default.jpg')}}')">
                                                    </div>
                                                @endif  
                                            </div>
                                            <div class="education-detail-public-profile">
                                                <a href="{{url('/career/information/'.$row->feed_id)}}"><h3>{{@$row->name}}</h3></a>
                                                <ul class="designation_year">
                                                    <li><span><img src="{{asset ('images/icons/first_rank.svg') }}" alt="VioGraf"/></span><p>{{ (@$row->role != '')? @$row->role : '-'}}</p></li>
                                                    <li><p> 
                                                    @if (@$row->start_date == '' && @$row->end_date == '')
                                                        - 
                                                    @else
                                                        @if (@$row->start_date != '' && @$row->end_date != '')
                                                       
                                                            {{date('M Y',strtotime(@$row->start_date))}} - {{date(' M Y',strtotime(@$row->end_date))}}
                                                        @else
                                                            {{date(' M Y',strtotime(@$row->start_date)) .' - '. 'Pursuing'}}
                                                        @endif
                                                    @endif
                                                       </p></li>
                                                </ul>
                                                <div class="predovicflex">
                                                    <span class=""><img src="{{asset ('images/icons/predovic.svg') }}" alt="VioGraf"/></span>
                                                    <p class="colleagues-p-tag">{{ (@$row->buddies != '') ? @$row->buddies : '-' }}</p>  
                                                </div>
                                                <div class="predovicflex">
                                                    <span class=""> <img src="{{asset ('images/Trophy.svg') }}" class="career_achievement" alt="VioGraf"/></span>
                                                    <p class="colleagues-p-tag">{{ (@$row->achievements != '')? @$row->achievements : '-'}}</p>                       
                                                </div>                       
                                                <p>
                                                    @php
                                                        $str_des = @$row->description;
                                                    @endphp
                                                    @if(strlen($str_des)>=135)
                                                        {{ str_limit($str_des, 135, $end='') }} 
                                                        <a href="{{url('/career/information/'.$row->feed_id)}}">More info.</a>
                                                    @else
                                                    {{ $str_des }}
                                                    @endif
                                                </p>
                                            </div>
                                            </li>
                                        @php /*<input type="hidden" id="feed_id" value="{{@$row->id}}"> */@endphp
                                        @endforeach
                                        @endif 
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div id="milestone" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
                        <div class="card-header" role="tab" id="Milestone">
                            <div class="inner-family-tree">
                                <div class="milestones-area">
                                    <ul class="milestones-section">
                                    @if((@$milestone_count) == 0)
                                        <p class="cls-no-record">No Records Found</p>
                                    @else
                                        @foreach(@$milestone_data_tab as $key=>$row )
                                            <li class="milestonebox viograf_col">
                                                <div class="milestone-col maindetail ">
                                                    <a href="{{route('milestone.information',$row->feed_id)}}">
                                                        <div class="milestone-detail">
                                                            @php
                                                                @$ext = explode(".",@$row->file);
                                                            @endphp
                                                            @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                                                                <div class="mile-img viografbox_img milestoneimage">
                                                                    <video class="milestone-video"><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_milestone_video'),@$row->file) }}"></video>
                                                                </div>
                                                            @else
                                                                <div class="mile-img viografbox_img milestoneimage" style="background-image:url('{{@$row->file ? Common_function::get_s3_file(config('custom_config.s3_milestone_thumb'),@$row->file) : url('images/default/milestone_default.jpg')}}')">
                                                                </div>
                                                            @endif  
                                                            @if(@$row->getUserMilestoneFeed->is_save_as_draft == 1)
                                                            <span class="draft-class">Draft</span>
                                                            @endif
                                                            <div class="mile-col viografbox_text">
                                                                <h3>{{ @$row->title }}</h3>
                                                                <p>{{ @$row->description }}</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                
                                                <div class="milestone-col">
                                                    <div class="milestone-date">
                                                        @php
                                                            if (@$row->achieve_date != ''){
                                                                $fulldate=@$row->achieve_date; 
                                                                $explodedate=explode('-',$fulldate);
                                                                $year=@$explodedate[0];
                                                                $date=@$explodedate[2];
                                                                $month=DateTime::createFromFormat('!m', @$explodedate[1]);
                                                                $monthname=$month->format('F');
                                                            }
                                                            else{
                                                                $year = '-';
                                                                $date = '-';
                                                                $monthname = '-';
                                                            }
                                                            
                                                        
                                                        @endphp
                                                        <h4> 
                                                            {{$year}}</h4>
                                                        <p><b>{{$date}}</b> {{$monthname}}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>        
        </div>
    </div>          
    <div class="your-viografarea tabarea sectionbottom30">
        <div class="sectionbox ">
            <h2 class="main_title">Feeds</h2>
            <div class="your_viograftab tabbox">
                <ul id="tabs" class="nav nav-tabs" role="tablist">
                    <li class="nav-item ">
                        <a id="tab-A" href="{{url('/public-profile/'.@$results->id.'?section=Wishlist#tabs')}}" class="nav-link {{(@$section=='Wishlist' || @$section=='')? 'active' : '' }}" role="tab">Wishlist</a>
                    </li>                    
                    <li class="nav-item ">
                        <a id="tab-B" href="{{url('/public-profile/'.@$results->id.'?section=Diary#tabs')}}" class="nav-link {{(@$section=='Diary' )? 'active' : '' }}"  role="tab">Diary</a>
                    </li>
                    <li class="nav-item ">
                        <a id="tab-C" href="{{url('/public-profile/'.@$results->id.'?section=Experiences#tabs')}}" class="nav-link {{(@$section=='Experiences')? 'active' : '' }}"
                            role="tab">Experiences</a>
                    </li>
                    
                    <li class="nav-item ">
                        <a id="tab-D" href="{{url('/public-profile/'.@$results->id.'?section=Ideas#tabs')}}" class="nav-link {{(@$section=='Ideas' )? 'active' : '' }}"  role="tab">Ideas</a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-E" href="{{url('/public-profile/'.@$results->id.'?section=Dreams#tabs')}}" class="nav-link  {{(@$section=='Dreams' )? 'active' : '' }}"  role="tab">Dreams</a>
                    </li>
                    <li class="nav-item ">
                        <a id="tab-F" href="{{url('/public-profile/'.@$results->id.'?section=Moments#tabs')}}" class="nav-link {{ (@$section=='Moments')? 'active' : ''}} " 
                        role="tab">Special Moments</a>
                    </li>
                    <li class="nav-item ">
                        <a id="tab-G" href="{{url('/public-profile/'.@$results->id.'?section=Life#tabs')}}" class="nav-link {{(@$section=='Life' )? 'active' : '' }}"  role="tab">Life
                            Lessons</a>
                    </li>
                    <li class="nav-item ">
                        <a id="tab-H" href="{{url('/public-profile/'.@$results->id.'?section=Firsts#tabs')}}" class="nav-link {{(@$section=='Firsts' )? 'active' : '' }}"  role="tab">Firsts</a>
                    </li>
                    <li class="nav-item ">
                        <a id="tab-I" href="{{url('/public-profile/'.@$results->id.'?section=Lasts#tabs')}}" class="nav-link {{(@$section=='Lasts' )? 'active' : '' }}"  role="tab">Lasts</a>
                    </li>
                    <li class="nav-item" id="spiritual_nav_id">
                        <a id="tab-J" href="{{url('/public-profile/'.@$results->id.'?section=Spiritual#spiritual_nav_id')}}" class="nav-link  {{(@$section=='Spiritual' )? 'active' : '' }}" role="tab">Spiritual Journey</a>
                    </li>
                    <li class="nav-item" id="milestone_nav_id">
                        <a id="tab-K" href="{{url('/public-profile/'.@$results->id.'?section=Milestone#milestone_nav_id')}}" class="nav-link  {{(@$section=='Milestone' )? 'active' : '' }}" role="tab">Milestones</a>
                    </li>
                </ul>

                <div id="content" class="tab-content" role="tablist">                
                    @if(@$section == 'Diary')
                        <div id="pane-B" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Diary' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-B">
                            <div class="card-header" role="tab" id="heading-B">
                            @if((@$diary_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata"> 
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.diary-block',['results' => @$diary_data])
                                    </div>     
                                   
                                    @if(@$diary_count >  4 )
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$diary_data->toArray()[@$diary_data->count() - 1];  
                                                                                        
                                            @endphp                                     
                                                <a href="javascript:;" data-id="{{ @$offset  }}" class="main_btn">View More</a>
                                        </div>                                        
                                        <input type="hidden" id="module" value="diary">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec[get_user_diary_feed]['type_id'] }}">
                                    @endif
                                </div>
                            @endif
                            </div>
                        </div> 
                    @elseif(@$section == 'Experiences') 
                        <div id="pane-C" class="card tab-pane inner_diary_page  {{ app('request')->input('section')=='Experiences' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-C">
                            <div class="card-header" role="tab" id="heading-C">
                            @if((@$experience_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata"> 
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.experience-block',['results' => @$experience_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4)
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$experience_data->toArray()[@$experience_data->count() - 1];                                               
                                            @endphp                                     
                                                <a href="javascript:;" data-id="{{ @$offset  }}" class="main_btn">View More</a>
                                        </div>                                        
                                        <input type="hidden" id="module" value="experience">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif 
                                </div>
                            @endif                                                           
                            </div>
                        </div>
                    @elseif(@$section == 'Ideas') 
                        <div id="pane-D" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Ideas' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-D">
                            <div class="card-header" role="tab" id="heading-D">
                            @if((@$idea_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.ideas-block',['results' => @$idea_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4)
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$idea_data->toArray()[@$idea_data->count() - 1];                                               
                                            @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$offset  }}" class="main_btn">View More</a>
                                        </div>
                                        <input type="hidden" id="module" value="Idea">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif  
                                </div>
                            @endif                                                             
                            </div>
                        </div>
                    @elseif(@$section == 'Dreams') 
                        <div id="pane-E" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Dreams' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-E">
                            <div class="card-header" role="tab" id="heading-E">
                            @if((@$dreams_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.dream-block',['results' => @$dreams_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4 )
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$dreams_data->toArray()[@$dreams_data->count() - 1];                                               
                                            @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$offset  }}" class="main_btn">View More</a>
                                        </div>
                                        <input type="hidden" id="module" value="dream">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif 
                                </div>
                            @endif                                                               
                            </div>
                        </div>
                    @elseif(@$section == 'Moments') 
                        <div id="pane-F" class="card tab-pane inner_moments_page {{ app('request')->input('section')=='Moments' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-F">
                            <div class="card-header" role="tab" id="heading-F">
                            @if((@$moments_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow moments_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.moment-block',['result' => @$moments_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4 )
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$moments_data->toArray()[@$moments_data->count() - 1];                                               
                                            @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$offset  }}" class="view_more_moment">View More</a>
                                        </div>
                                        <input type="hidden" id="module" value="Moments">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif  
                                </div>  
                            @endif                                                          
                            </div>
                        </div>
                    @elseif(@$section == 'Life') 
                        <div id="pane-G" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Life' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-G">
                            <div class="card-header" role="tab" id="heading-G">
                            @if((@$life_lessons_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.lifelesson-block',['results' => @$life_lessons_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4 )
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$life_lessons_data->toArray()[@$life_lessons_data->count() - 1];                                               
                                            @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$offset }}" class="main_btn">View More</a>
                                        </div>
                                        <input type="hidden" id="module" value="lifelesson">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif  
                                </div>
                            @endif                                                              
                            </div>
                        </div>
                    @elseif(@$section == 'Firsts')                       
                        <div id="pane-H" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Firsts' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-H">
                            <div class="card-header" role="tab" id="heading-H">                            
                            @if(count($first_data) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.first-block',['results' => @$first_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4)
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$first_data->toArray()[@$first_data->count() - 1];                                               
                                            @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$last_rec['id'] }}" class="main_btn">View More</a>
                                        </div>
                                        <input type="hidden" id="module" value="first">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif 
                                </div>
                            @endif                                                               
                            </div>
                        </div>
                    @elseif(@$section == 'Lasts')                       
                        <div id="pane-I" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Lasts' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-I">
                            <div class="card-header" role="tab" id="heading-I">
                            @if(count($last_data) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.last-block',['results' => @$last_data])
                                    </div>
                                    @if(@$resultcount->total >  4)
                                        <div class="text-center view-more-cls">
                                            @php                                           
                                                $last_rec = @$last_data->toArray()[@$last_data->count() - 1];                                                                            
                                            @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$last_rec['id'] }}" class="main_btn">View More</a>
                                        </div>
                                        <input type="hidden" id="module" value="last">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
                                    @endif 
                                </div> 
                            @endif                                                         
                            </div>
                        </div>
                    @elseif(@$section == 'Spiritual') 
                        <div id="pane-J" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Spiritual' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-J">
                            <div class="card-header" role="tab" id="heading-J">
                            @if((@$spiritual_journeys_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.spiritual-journey-block',['results' => @$spiritual_journeys_data])
                                    </div>                                
                                    @if(@$resultcount->total >  4 )
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$spiritual_journeys_data->toArray()[@$spiritual_journeys_data->count() - 1];                                               
                                            @endphp                                     
                                                <a href="javascript:;" data-id="{{ @$offset  }}" class="main_btn">View More</a>
                                        </div>                                    
                                        <input type="hidden" id="module" value="spiritual-journey">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}"> 
                                    @endif
                                </div> 
                            @endif                                                           
                            </div>
                        </div>
                    @elseif(@$section == 'Milestone') 
                        <div id="pane-K" class="card tab-pane inner_diary_page {{ app('request')->input('section')=='Milestone' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-K">
                            <div class="card-header" role="tab" id="heading-K">
                            @if((@$milestone_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else
                                <div class="appdata">
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.milestone-feed-block',['results' => @$milestone_data])
                                    </div>             
                                                   
                                    @if(@$milestone_count >  4 )
                                        <div class="text-center view-more-cls">
                                            @php
                                                $last_rec = @$milestone_data->toArray()[@$milestone_count - 1];                                          
                                            @endphp                                     
                                                <a href="javascript:;" data-id="{{ @$offset }}" class="main_btn">View More</a>
                                        </div>                                    
                                        <input type="hidden" id="module" value="milestone">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}"> 
                                    @endif
                                </div> 
                            @endif                                                           
                            </div>
                        </div>                         
                    @else
                        <div id="pane-A" class="card tab-pane inner_wishlist_page fade show {{ app('request')->input('section')=='Wishlist' || app('request')->input('section')=='' ? 'active':''}}" role="tabpanel" aria-labelledby="tab-A">
                            <div class="card-header" role="tab" id="heading-A">  
                            @if((@$wishlist_data->count()) == 0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                            @else 
                                <div class="appdata">       
                                    <div class="row your_viografrow viograf_row js-load_more_button" style="list-style-type:none;">
                                        @include('include.wishlist-block',['results' => @$wishlist_data])
                                    </div>     
                                   
                                    @if(@$resultcount->total >  4 )                                    
                                        <div class="text-center view-more-cls">   
                                        @php
                                            $last_rec = @$wishlist_data->toArray()[@$wishlist_data->count() - 1];                                                                                                                                       
                                        @endphp                                     
                                            <a href="javascript:;" data-id="{{ @$offset }}" class="main_btn">View More</a>
                                        </div>                                        
                                        <input type="hidden" id="module" value="Wishlist">
                                        <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}"> 
                                    @endif 
                                </div>
                            @endif
                            </div>
                        </div>
                                          
                    @endif                     
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="public_profile_module" value="{{@$public_profile_module}}">    
    <input type="hidden" id="user_id" value="{{@$user_id}}">
    
@stop