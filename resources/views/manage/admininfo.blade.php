@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <h3>{{ $title }}</h3>
            <ol class="breadcrumb">
                <li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/manage/admins') }}">{{$main_module}}</a></li>
                <li class="active">{{ $title }}</li>
            </ol>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <a href="{{ url('/manage/admins') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
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
                        <div class="col-md-4">
                        <center>
                            <img class="profile-user-img img-responsive img-circle" src="{{@$admin->profile_photo ? Common_function::get_s3_file(config('custom_config.s3_admin_thumb'),$admin->profile_photo) : url('images/userdefault-1.svg')}}">
                        </center>
                            <h3 class="profile-username text-center">{{ $admin->first_name.' '.$admin->last_name }}
                            </h3>
                            
                            <p class="text-center margin">
                            @if($admin->admin_type != "super_admin")
                                <a href="{{ url('manage/admins/edit') }}/{{ $admin->id }}"><i class="fas fa-edit"></i> Edit</a>
                            @endif
                            </p>
                        </div>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Name</strong>
                                    <p class="text-muted">{{ $admin->first_name.' '.$admin->last_name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong> Email Address</strong>
                                    <p><a href="mailto:{{ $admin->email }}">{{ $admin->email }}</a></p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Admin Type</strong>
                                    <p class="text-muted">{{ ($admin->admin_type == 'super_admin')?'Super Admin':'Sub Admin' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong> Phone </strong>
                                    <p class="text-muted">{{ $admin->contact_number?$admin->contact_number:'-' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Created Date</strong>
                                    <p class="text-muted">{{@$admin->created_at ? Common_function::show_date_time($admin->created_at) : ''}}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Updated Date</strong>
                                    <p class="text-muted">{{@$admin->updated_at ? Common_function::show_date_time($admin->updated_at) : '-'}}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong> Created Ip </strong>
                                    <p class="text-muted">{!! long2ip($admin->created_ip) !!}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong> Updated Ip </strong>
                                    <p class="text-muted">{!! long2ip($admin->updated_ip)?long2ip($admin->updated_ip):'-' !!}</p>
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