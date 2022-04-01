@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="my-profile-page sectionbox">
        <h1 class="main_title">Myself</h1>
        @include("include.myself_menu")
        @include('include.notification')
        <div class="my-education">
            <div class="add-item_area text-right">
                <a href="{{ route ('career.add') }}" class="pink_btn addplus"><span> </span> Add Career</a>
            </div>
            
            <ul class="my-education-list_area">
            @if (count(@$results) == 0)
            <p class="cls-no-record">No Records Found</p>
            @else
            @foreach($results as $key=>$row )
                <li class="my-education-listbox">
                <div class="new_education_img_section">                                               
                        @php
                            @$ext = explode(".",@$row->file);                            
                        @endphp
                        @if(!empty(@$row->file))
                            @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                                <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_career_video'),@$row->file) }}">
                            </video>
                            @if(@$row->getUserCareerFeed->is_save_as_draft == 1)
                                <span class="draft-class-other draft-video-edu-career">Draft</span>
                            @endif
                               
                            @else                            
                                <div class="education-img" style="background-image:url({{ Common_function::get_s3_file(config('custom_config.s3_career_thumb'),@$row->file) }});">
                                @if(@$row->getUserCareerFeed->is_save_as_draft == 1)
                                    <span class="draft-class-other draft-class-edu-career">Draft</span>
                                @endif
                                </div>
                            @endif 
                        @else
                            <div class="education-img" style="background-image:url('{{asset('images/default/career_default.jpg')}}')">
                                @if(@$row->getUserCareerFeed->is_save_as_draft == 1)
                                    <span class="draft-class-other draft-class-edu-career">Draft</span>
                                @endif
                            </div>
                        @endif  
                    </div>
                    <div class="education-detail">
                    <a href="{{url('/career/information/'.$row->feed_id)}}"><h3>{{(@$row->name != '') ? @$row->name : '-'}}</h3></a>
                        <ul class="designation_year">
                            <li><span><img src="{{asset ('images/icons/first_rank.svg') }}" alt="VioGraf"/></span><p>{{(@$row->role !='') ? @$row->role : '-'}}</p></li>
                            <li>
                                <p> 
                                    @if (@$row->start_date == '' && @$row->end_date == '')
                                        -
                                    @else
                                        @if (@$row->start_date != '' && @$row->end_date != '')
                                            {{date(' M Y',strtotime(@$row->start_date)) .' - '.date(' M Y',strtotime(@$row->end_date))}}
                                        @else
                                            {{date(' M Y',strtotime(@$row->start_date)) .' - '. 'Present'}}
                                        @endif
                                    @endif
                                </p>
                            </li>
                        </ul>
                        <div class="predovicflex">
                            <span class=""><img src="{{asset ('images/icons/predovic.svg') }}" alt="VioGraf"/></span>
                            <p class="colleagues-p-tag">{{ (@$row->buddies != '') ? @$row->buddies : '-' }}</p>  
                        </div>
                        <div class="predovicflex">
                            <span class=""> <img src="{{asset ('images/Trophy.svg') }}" class="career_achievement" alt="VioGraf"/></span>
                            <p class="colleagues-p-tag">{{(@$row->achievements != '')  ? @$row->achievements  : '-'}}</p>                       
                        </div>
                        <p>
                            @php
                                $str_des = @$row->description;
                            @endphp
                            @if(strlen($str_des)>=55)
                                {{ str_limit($str_des, 55, $end='') }} 
                                <a href="{{url('/career/information/'.$row->feed_id)}}">More info.</a>
                            @else
                            {{ $str_des }}
                            @endif
                        </p>
                    </div>
                    <div class="edit_delete_icon">
                    <a href="{{url('/career/information/'.$row->feed_id)}}" data-toggle="tooltip" title="Information">
                            <img src="{{ asset('images/Group.svg') }}" class="img-fluid" alt="VioGraf">
                        </a>
                        <a href="{{url('/career/edit/'.$row->feed_id)}}" data-toggle="tooltip" title="Edit">
                            <img src="{{ asset('images/icons/edit_icon.svg') }}" class="img-fluid" alt="VioGraf">
                        </a>
                        <a href="javascript:;" class="delete_feed" id="feed_id" data-id="{{@$row->feed_id}}"  data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                            <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid" alt="VioGraf" />
                        </a>
                    </div>
                </li>
                @php /*<input type="hidden" id="feed_id" value="{{@$row->id}}"> */@endphp
                @endforeach
                @endif 
            </ul>
        </div>
    </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="liketable" value="{{@$likeTable}}">
    <input type="hidden" id="commenttable" value="{{@$commentTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
    <input type="hidden" id="feed_id" value="{{@$row->id}}">
@stop