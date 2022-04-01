@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="search-result-page sectionbox">
        @if(@$results_total == 0)
            <div class="sectionbox_for_public_profile_message">
                <p class="cls-no-record cls-search"><b>No Records Found</b></br></p> 
                <p class="cls-no-record cls-search cls-search-text">Click on below button and please enter valid text for search</p>
                <div class="text-center">
                    <a href="" class="for_search_section_focus"> Click Me </a>
                </div>
            </div>
        @else
            <div class="search_text_div">
                <h1 class="main_title" id="typed_text_for_search"> {{(@$results_total == 1) ? @$results_total.' result found for' :  @$results_total.' results found for'  }} <span id="typing_text">"</span>"</h1>
            </div>
            <div class="appdata">
                <ul class="search-result-row">
                    @include('include.search-block')
                </ul>
            </div>
        @endif       
        @if(@$results_total > 4)
            <div class="text-center">
            @php
                $last_search_data_block = $results->toArray()[$results->count() - 1];      
            @endphp
                <a href="javascript:;" data-id="{{ @$offset }}" class="search_view_more">View More</a>
            </div>
            <input type="hidden" id="module" value="{{$module}}">            
        @endif
        <input type="hidden" name="search_text_hidden_name" id="search_text_hidden_id">
    </div>

   
@stop