@extends('adminlte::page')
@section('title', $title)

@section('content_header')
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-6">
			<h3>{{ $title}}</h3>
			<ol class="breadcrumb">
				<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>   
                <li><a href="{{ url('/manage/admins') }}">{{ $main_module }}</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <a href="{{ url('/manage/admins') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
	</div>
@stop
@section('alert-section')
	@include('manage.include.notification')
@stop
@section('content')
@include('manage.include.notification')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                </div>
                    <form class="form-horizontal" id="{{$frn_id}}" action="{{$action}}" method="post" enctype="multipart/form-data">
						{{ csrf_field() }}
                        <div class="card-body">
                            <!-- ./form sub header-->
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">First Name<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="first_name" name="fname" placeholder="First Name" value="{{ old('fname',@$admin->first_name) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">Last Name<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="last_name" name="lname" placeholder="Last Name" value="{{ old('lname',@$admin->last_name) }}">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="contact" id="contact" value="{{ old('contact',@$admin->contact_number) }}" placeholder="Phone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSkills" class="col-sm-2 control-label">Display Photo</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <input type="hidden" name="image" id="image" value="">
                                            <input type="file" name="file" id="imagefile" accept="image/*" class="file-input">
                                            <span class="input-group-addon file-input-img-span" style="overflow: hidden;">
                                                <img src="{{@$admin->profile_photo ? Common_function::get_s3_file(config('custom_config.s3_admin_thumb'),$admin->profile_photo) : url('images/userdefault-1.svg')}}" height="30">
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" disabled placeholder="Upload Image" value="{{@$admin->profile_photo}}">
                                        <input type="hidden" id="oldPhoto" value="{{@$admin->profile_photo ? Common_function::get_s3_file(config('custom_config.s3_admin_thumb'),$admin->profile_photo) : url('images/userdefault-1.svg')}}">
                                        <div class="input-group-append">
                                            <button class="file-input-browse btn btn-dark" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                        </div>
                                    </div>
                                    <span id="fileerr" class="help-block" style="color:red;font-size:14px;"></span>
                                </div>
                            </div>
                            @if(@$profile_view != 1)
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">Admin Type</label>
                                <div class="col-sm-5">
                                    <select name="admin_type" class="form-control">
                                        <option {{old('admin_type',@$admin->admin_type) == 'super_admin'?'selected':''}} value="super_admin">Super Admin</option>
                                        <option {{old('admin_type',@$admin->admin_type) == 'sub_admin'?'selected':''}} value="sub_admin">Sub Admin</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <!-- ./form sub header-->
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 control-label">Email<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" {{@$admin->email!=''?'readonly':''}} value="{{old('email',@$admin->email)}}" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">Password<i class="text-danger">*</i></label>

                                <div class="col-sm-5">
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">Confirm Password<i class="text-danger">*</i></label>

                                <div class="col-sm-5">
                                    <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-dark ">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
@stop

