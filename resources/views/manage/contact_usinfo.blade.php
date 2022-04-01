@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <h3>{{ $title }}</h3>
            <ol class="breadcrumb">
                <li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/manage/contact-us') }}">{{$main_module}}</a></li>
                <li class="active">{{ $title }}</li>
            </ol>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <a href="{{ url('/manage/contact-us') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <!-- About Me Box Start-->
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header with-border">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                    <div class="row">
                    @php /*
                        <div class="col-md-4">                        
                            <h3 class="profile-username text-center">{{ $contactus->name }}
                            </h3>
                            <p class="text-center margin">                            
                                <a href="{{ url('manage/contact-us/edit') }}/{{ $contactus->id }}"><i class="fas fa-edit"></i> Edit</a>
                            </p>
                        </div>
                        */ @endphp
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Name</strong>
                                    <p class="text-muted">{{ $contactus->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong> Email Address</strong>
                                    <p><a href="mailto:{{ $contactus->email }}">{{ $contactus->email }}</a></p>
                                </div>
                                
                                <div class="col-md-4">
                                    <strong> Contact Number </strong>
                                    <p class="text-muted">{{ $contactus->contact_number ? $contactus->contact_number :'-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong> Subject</strong>
                                    <p>{{ $contactus->subject }}</a></p>
                                </div>
                                
                                <div class="col-md-4">
                                    <strong>Created Date</strong>
                                    <p class="text-muted">{{@$contactus->created_at ? Common_function::show_date_time($contactus->created_at) : ''}}</p>
                                </div>
                                
                                <div class="col-md-4">
                                    <strong> Created Ip </strong>
                                    <p class="text-muted">{!! long2ip($contactus->created_ip) !!}</p>
                                </div>
                                <div class="col-md-12">
                                    <strong> Message</strong>
                                    <p>{{ $contactus->message }}</a></p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
	</div>
@stop