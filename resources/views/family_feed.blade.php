@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="inner_family_feed_page  sectionbox sectionbottom30 ">
        <h1 class="main_title">Family Feed</h1>
        @if(count($result_family_feed) == 0)
            <div class="text-center">
                <p class="cls-no-record">No Records Found</p>
            </div>
        @else
        <div class="appdata">
            <ul class="viograf_row js-load_more_button">
                @include('include.home-family-block',['results' => @$result_family_feed]) 
            </ul>
        </div>
        @endif
        @if(count(@$familyfeed_count) > 24)
            <div class="text-center">
                @php
                    $last_rec = @$result_family_feed->toArray()[@$result_family_feed->count() - 1];
                @endphp
                <a href="javascript:;" data-id="{{ @$last_rec['id'] }}" class="main_btn">View More</a>
            </div>
        @endif
    </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="module" value="{{@$module}}">    
    <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">  
@stop