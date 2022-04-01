@extends('adminlte::page')
@section('title',$title)

@section('content_header')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-6">
            <h3>{{ $title }}</h3>
            <ol class="breadcrumb">
                <li><a href="{{ url('/manage') }}">Home</a></li>
                <li class="active">{{ $title }}</li>
            </ol>
        </div>        
    </div>
@stop
@section('content')
    @include('manage.include.notification')   
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">User Report</h3>
                </div>
                <form class="form-horizontal" id="report_frm" action="{{ route('reports.export') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="card-body">                        
                        <div class=" form-group row">
                            <div class="col-sm-4">    
                                <input type="text" class="form-control report_page_search" name="search" value="{{@$search}}" placeholder="Search by name or email" style="font-size:14px;">
                            </div>
                            <div class="col-sm-4">     
                                <span class="user_date_span">
                                    <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                </span>  
                                <input type="text" name="search_date" readonly='true' id="startdate" class="form-control report_page_search searchdate" placeholder="Start Date" value="{{@$search_date}}"   autocomplete="off"  style="font-size:14px;"/>  
                            </div>
                            <div class="col-sm-4">
                                <span class="user_date_span">
                                    <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                </span>  
                                <input type="text" name="search_end_date" readonly='true' id="enddate" class=" form-control report_page_search searchdate" placeholder="End Date" value="{{@$search_end_date}}"   autocomplete="off" style="font-size:14px;"/>        
                            </div>                            
                        </div>                        
                        <div class="form-group row">
                            <div class="col-5">
                            <button type="submit" class="btn btn-dark col-sm-5">Export</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @stop