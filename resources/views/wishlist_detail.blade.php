@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="post-detail-page">
        <div class="inner_back_center_row row">
            <div class="inner_back_col">
                <a href="{{ (Common_function::previous_url()[0][3] == strtolower(@$module)) ? route('wishlist.index') : Common_function::previous_url()[1] }}" class="btn_back">
                    <img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt="VioGraf" />
                </a>
            </div>
            <div class="inner_center_page">
                <div class="inner-education-detailpage post-detail-page sectionbox">
                    <div class="education-imgs-lider slider">
                        <div class="banner_box">
                            <div class="banner_img">                       
                                @php
                                    @$ext = explode(".",@$results->getUserWishlist->file);                             
                                @endphp
                                @if(@$results->getUserWishlist->file != '')
                                    @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm') 
                                    <div class="video_box"></div>                           
                                        <video controls class="img-fluid" disablepictureinpicture controlslist="nodownload"><source id="videoSource"/ src="{{@$results->getUserWishlist->file ? Common_function::get_s3_file(config('custom_config.s3_wishlist_video'),@$results->getUserWishlist->file) : asset('images/default_banner.jpg')}}"></video>
                                    @else
                                        <img src="{{ @$results->getUserWishlist->file ? Common_function::get_s3_file(config('custom_config.s3_wishlist_big'),@$results->getUserWishlist->file) : asset('images/default/wishlist_default.jpg') }}" class="img-fluid"
                                            alt="VioGraf" />
                                    @endif
                                @else 
                                    <img src="{{ asset('images/default/wishlist_default.jpg') }}" class="img-fluid" alt="VioGraf" />   
                                @endif
                                @if(@$results->is_save_as_draft == 1)
                                 <span class="draft-class">Draft</span>
                                @endif
                            </div>
                        </div>               
                    </div>
                    <div class="post-detail-area">
                        <div class="post_title_row">
                            <div class="post_title_box">
                                <h1 class="main_title">{{(@$results->getUserWishlist->objective != '') ? @$results->getUserWishlist->objective  : '-'}}
                                
                                    <i>{{Common_function::get_time_ago(strtotime($results->created_at))}}</i>
                                </h1>
                            </div>                           
                           @if(@$results->user_id == Auth::user()->id)
                                <div class="edit_delete_icon">
                                    <a href="{{url('wishlist/edit/'.$results->id)}}" title="Edit">
                                        <img src="{{asset('images/icons/edit_icon.svg')}}" class="img-fluid" alt="VioGraf" />
                                    </a>
                                    <a href="javascript:;" class="delete_feed" data-id="{{@$results->id}}" data-module="{{@$module}}" title="Delete">
                                        <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid" alt="VioGraf" />
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="post_detail_box">
                        <div class="detail_info">
                            <h3>What is your Wish??</h3>        
                            <h4><span>{{ (@$results->getUserWishlist->wish != '') ? @$results->getUserWishlist->wish : '-'}}</span></h4>
                        </div>
                        <div class="detail_info">
                            <h3>By When?</h3>        
                            <h4><span>{{ (@$results->getUserWishlist->by_when != '') ? date('D d, M Y',strtotime(@$results->getUserWishlist->by_when)) : '-'}}</span></h4>
                        </div>
                            @if(!$results->getdocuments->isEmpty())
                                <div class="Documentsarea common-class">
                                    <h5>Documents</h5>
                                    <div class="Documentsrow">
                                    <div class="row">
                                        @foreach ($results->getdocuments as $docval)
                                        <div class="col-md-4 col-sm-4 col-12">
                                            <div class="Documentsbox">
                                                <a class="document-link-css" href="{{Common_function::get_s3_file(str_replace('[id]',$results->id,config('custom_config.s3_feed_document')),$docval->file)}}"  target="_blank">
                                                    <span>
                                                        <img src="{{asset('images/icons/link.svg')}}" class="img-fluid" alt="VioGraf">
                                                    </span>
                                                    {{$docval->file}}
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    </div>
                                </div>
                            @endif
                            <div class="detail_info">
                                <h3>Description</h3>        
                                <h4><span class="desc-info">{!! Common_function::string_read_more(@$results->getUserWishlist->description) !!}</span></h4>
                            </div>
                        </div>
                        @if(@$results->is_save_as_draft == 0)
                            <div>
                                <ul class="like_comments_row">
                                    <li class="like_box">
                                        <button type="button" class="like_unlike {{(@$results->getUserWishlistLikes->is_like != 0 )? 'active':''}}" id="like_unlike" value="">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                            <p id="countLike">{{Common_function::like_comment_count(@$results->like_count)}} {{@$results->like_count > 1?'Likes': 'Like'}}</p>
                                        </button>
                                    </li>
                                    <li class="like_comments">
                                        <div class="comment">
                                            <i class="fa fa-comments" aria-hidden="true"></i>
                                            <p id="countComment">{{Common_function::like_comment_count(@$results->comment_count)}} {{@$results->comment_count > 1?'Comments': 'Comment'}}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="comment_area">
                                <div class="comment_text_box">
                                    <div class="img_col">
                                        <img src="{{  @Auth::user()->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),  @Auth::user()->profile_image) : url('images/userdefault-1.svg') }}" alt="Family Feed" class="img-fluid">
                                    </div>
                                    <div class="form-group form_box send_message_box"> 
                                        <input type="text" name="comment" id="comment" placeholder="Write a comment..." maxlength="500" class="form-control comment-input">
                                        <button class="send_message">Send</button>
                                    </div>
                                </div>
                                <ul class="comment_list">
                                    <div class="cmts">
                                        @foreach (@$results->getUserWishlistComments as $k => $val)
                                            <li>
                                                <div class="img_col">
                                                    <img src="{{  @$val->getcommentedUser->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),  @$val->getcommentedUser->profile_image) : url('images/userdefault-1.svg') }}" alt="Family Feed" class="img-fluid">
                                                </div>
                                                <a href="{{route('public-profile.index',@$val->getcommentedUser->id)}}">
                                                    <div class="commentdetail">
                                                        <h4>{{@$val->getcommentedUser->first_name.' '.@$val->getcommentedUser->last_name}}   <p class="commentTime">&nbsp;{{Common_function::get_time_ago(strtotime($val->created_at))}}</p></h4>
                                                        <p>{{@$val->comment}}</p>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </div>
                                    @if(@$results->comment_count > 3)
                                        <div class="view_more_commentsarea" >
                                            <div id="view_more_commentsarea">
                                                <button type="button" class="btn-more" id="btn-more" data-cmtcount="{{count(@$results->getUserWishlistComments)}}" data-id ="{{@$val->id}}">View more comments</button>
                                            </div>
                                            <span> {{count(@$results->getUserWishlistComments)}} of {{Common_function::like_comment_count(@$results->comment_count)}}</span>
                                        </div>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="liketable" value="{{@$likeTable}}">
    <input type="hidden" id="commenttable" value="{{@$commentTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="feed_id" value="{{@$results->id}}">   
@stop