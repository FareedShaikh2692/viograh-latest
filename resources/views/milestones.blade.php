@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
<div class="inner-family-tree sectionbox">
    @include('include.notification')
        <div class="title_btn_row">
            <div class="create_title">
                <h1 class="main_title">Milestones</h1>
            </div>
            @if(@$milestoneCount != 0)
            <div class="createnew">
                <a href="{{route('milestone.add')}}" class="pink_btn addplus"> <span> </span>Create New</a>
            </div>
            @endif
        </div>
        @if(@$milestoneCount == 0)
            <div class="text-center milestone-padding">
                <p class="cls-no-record no-milestone">Now you have no Milestone.</p>
                <a href="{{route('milestone.add')}}" class="pink_btn addplus"> <span> </span>Add Milestone</a>
            </div>
        @else
        <div class="milestones-area appdata">
            <ul class="milestones-section js-load_more_button">
                @include('include.milestone-block')
            </ul>
        </div>
        @endif
        @if($milestoneCount > 4)
            <div class="text-center padding-milestone">
                @php
                    $last_rec = $results->count() ? $results->toArray()[$results->count() - 1] : 0 ;
                    
                @endphp
                <a href="javascript:;" data-id="{{ @$offset }}" class="main_btn">View More</a>
            </div>
        @endif
</div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="filetable" value="{{@$filetable}}">
    <input type="hidden" id="liketable" value="{{@$likeTable}}">
    <input type="hidden" id="commenttable" value="{{@$commentTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
@stop
    