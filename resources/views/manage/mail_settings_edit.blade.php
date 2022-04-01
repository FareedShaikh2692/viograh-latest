@extends('adminlte::page')

@section('title', $title)

@section('content_header')
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-6">
			<h3>{{ $title}}</h3>
			<ol class="breadcrumb">
				<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>   
                <li><a href="{{ url('/manage/mail-settings') }}">{{ $main_module }}</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <a href="{{ url('/manage/mail-settings') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
	</div>
@stop

@section('content')
@include('manage.include.notification')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">   
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"> Mail Template Information</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" id="{{$frm_id}}" action="{{$action}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 control-label">Title<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ old('title',@$mail_setting->title) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="slug" class="col-sm-2 control-label">Slug<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug"  {{@$mail_setting->slug!=''?'readonly':''}} value="{{ old('slug',@$mail_setting->slug) }}">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="module" class="col-sm-2 control-label">Module<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="module" id="module" value="{{ old('module',@$mail_setting->module) }}"  class = "" placeholder="Module">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="from_email" class="col-sm-2 control-label">From Email<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                    <input type="email" class="form-control" placeholder="From Email" name="from_email" id="from_email" value="{{old('from_email',@$mail_setting->from_email)}}" >
                                </div>
                            </div>
                            @if($method == 'Add')
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">Email For Admin?<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                <div class="admin-radio">
                                    <div class="float-left form-check pr-4 pt-2">
                                        <input class="form-check-input" name="to_admin" type="radio" id="is_admin_yes" value="1" {{(@$mail_setting->sent_mail_to_admin==1)?'checked':''}}>
                                        <label class="form-check-label pl-0" id="is_admin_yes" for="is_admin_yes">Yes</label>
                                    </div>
            
                                    <div class="float-left form-check pt-2">
                                        <input class="form-check-input" name="to_admin" type="radio" id="is_admin_no" value="0" {{(@$mail_setting->sent_mail_to_admin==0)?'checked':''}}>
                                        <label class="form-check-label pl-3" id="is_admin_no" for="is_admin_no">No</label>
                                    </div>
                                </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group to_email_frm_grp row {{$method=='Edit'?'block':''}}" id="to_email_frm_grp" >
                                <label for="to_email_frm_grp" id="to_email_frm_grp" class="col-sm-2 control-label">To Email</label>
                                <div class="col-sm-5">
                                    <input type="text" id="to_email" name="to_email" class="form-control" value="{{ old('to_email',@$mail_setting->to_email) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="from_text" class="col-sm-2 control-label">From Text<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                <input type="text" id="from_text" name="from_text" class="form-control" value="{{ old('from_text',@$mail_setting->from_text) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="subject" class="col-sm-2 control-label">Subject<i class="text-danger">*</i></label>
                                <div class="col-sm-5">
                                <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject',@$mail_setting->subject) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="comment" class="col-sm-2 control-label">Comment</label>
                                <div class="col-sm-5">
                                <input type="text" id="comment" name="comment" class="form-control" value="{{ old('comment',  @$mail_setting->comment) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mail_content" class="col-sm-2 control-label">Mail Content<i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                <textarea id="editor1" class="form-control" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px; padding: 10px; border:1px solid #ced4da"  name="mail_content">{{ old('mail_content',@$mail_setting->content) }}</textarea>
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