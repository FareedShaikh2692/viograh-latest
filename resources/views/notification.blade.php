@extends('layouts.page')
@php $title = 'Notifications'; @endphp
@section('title', @$title)
@section('pageContent')
    <div class="inner_notifications_page  sectionbox sectionbottom30">
        <h1 class="main_title">Notifications</h1>
        <ul class="inner_notificationsrow" id="myList" >
        @if (@$notification->count() == 0)
                <p class="cls-no-record">No Notification Available</p>
        @else
            <div class="appdata">
                <ul class="inner_notificationsrow viograf_row js-load_more_button">
                    @include('include.notification-block',['results'=>@$notification])
                </ul>
            </div>
        @endif
            <div class="hiddenfield">
            <input type="hidden" id="offset" name="offset" value="">
            </div>
        </ul>
         @if(@$notification_count > 5)
        <div class="text-center">
            @php
                $last_rec = $notification->toArray()[$notification->count() - 1];     
            @endphp
            <a href="javascript:;" data-id="{{ @$last_rec['id'] }}" class="main_btn">View More</a>
        </div>
        @endif
    </div>
    
    <input type="hidden" id="module" value="{{@$module}}">    
    <input type="hidden" id="type_id" value="{{ @$last_rec['type_id'] }}">  
@stop
