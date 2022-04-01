@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="inner_diary_page  sectionbox sectionbottom30">
        @include('include.notification')
        <div class="title_btn_row">
            <div class="create_title">
                <ul class="tabrow">
                    <li class="active">
                        <a href="{{route('first.index')}}">Firsts </a>
                    </li>
                    <li>
                        <a href="{{route('last.index')}}">Lasts</a>
                    </li>
                </ul>
            </div>
            <div class="createnew">
                <a href="{{route('first.add')}}" class="pink_btn addplus"> <span>  </span> Create New</a>
            </div>
        </div>

        @if(count($firstCount) == 0)
            <div class="text-center">
                <p class="cls-no-record">No Records Found</p>
            </div>
        @else
            <div class="appdata">
                <ul class="viograf_row js-load_more_button">
                @include('include.first-block')
                </ul>
            </div>
        @endif
        @if(count($firstCount) > 4)
            <div class="text-center">
                @php
                    $last_rec = $results->count() ? $results->toArray()[$results->count() - 1] : 0 ;
                @endphp
                <a href="javascript:;" data-id="{{ @$last_rec['id'] }}" class="main_btn">View More</a>
            </div>
        @endif
    </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="liketable" value="{{@$likeTable}}">
    <input type="hidden" id="commenttable" value="{{@$commentTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">
@stop