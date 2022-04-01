@extends('layouts.page')
@php $title = 'Moments'; @endphp
@section('title', @$title)
@section('pageContent')
    <div class="inner_moments_page  sectionbox sectionbottom30">
        <div class="title_btn_row">
            <div class="create_title">
                <h1 class="main_title">Moments</h1>
            </div>
            <div class="createnew">
                <a href="{{route('moments.add')}}" class="pink_btn addplus"> <span>  </span> Create New</a>
            </div>
        </div>
        @if(count($momentCount) == 0)
             <div class="text-center">
                <p class="cls-no-record">No Records Found</p>
            </div>
        @else
            <div class="appdata">
            <ul class="moments_row">
                @include('include.moment-block')
            </ul>
        </div>
       
        @endif
        @if(count($momentCount) > 4)
            @php
                $last_rec = (array)$result->toArray()[$result->count() - 1];
            @endphp
            <div class="text-center">
                <a href="javascript:;" data-id="{{ @$last_rec['id'] }}" class="view_more_moment">View More</a>
            </div>
        @endif
    </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="filetable" value="{{@$filetable}}">
    <input type="hidden" id="liketable" value="{{@$likeTable}}">
    <input type="hidden" id="commenttable" value="{{@$commentTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="type_id" value="{{@config('custom_config.user_feed_type')['moments']}}">
    
@stop
    