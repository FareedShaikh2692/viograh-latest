@extends('adminlte::page')

@section('title',$title)
@section('content_header')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <h3>{{ $title }}</h3>
            <ol class="breadcrumb">
                <li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('/manage/mail-settings') }}">{{$main_module}}</a></li>
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
        <!-- About Me Box -->
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header with-border">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="mb-3">
                          <strong>Title</strong>
                          <p>{{ $mail_setting->title }}</p>
                        </div>
                        <div class="mb-3">
                          <strong>Module</strong>
                          <p class="text-muted">{{ $mail_setting->module }}</p>
                        </div>
                        <div class="mb-3">
                          <strong>Comment</strong>
                          <p class="text-muted">{{ $mail_setting->comment?$mail_setting->comment:'-' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                      <div class="mb-3">
                        <strong>Subject</strong>
                        <p>{{ $mail_setting->subject }}</p>
                      </div>
                      <div class="mb-3">
                        <strong>From</strong>
                        <p class="text-muted">{{ $mail_setting->from_text }}</p>
                      </div>
                      <div class="mb-3">
                        <strong>From Email</strong>
                        <p><a href="mailto:{{ $mail_setting->email }}">{{ $mail_setting->from_email }}</a></p>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                      <div class="mb-3">
                        <strong>To Email</strong>
                        <p class="text-muted">{{ $mail_setting->to_email }}</p>
                      </div>
                      <div class="mb-3">
                        <strong>Slug</strong>
                        <p class="text-muted">{{ $mail_setting->slug }}</p>
                      </div>  
                      @if($mail_setting->created_at != '')
                      <div class="mb-3">
                        <strong>Created At</strong>
                        <p>{{ Common_function::show_date_time($mail_setting->created_at) }}</p>
                      </div>
                      @endif
                    </div>
                    <div class="col-sm-12 col-md-3">
                      @if($mail_setting->updated_at != '')
                      <div class="mb-3">
                        <strong>Updated At</strong>
                        <p>{{ Common_function::show_date_time($mail_setting->updated_at) }}</p>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            $msg = str_replace('[SITE_LINK]', url(''), $mail_setting->content);
                            $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);							
							              $msg = str_replace('img {', 'img1{', $msg);
                            $msg = str_replace('', '<html>', $msg);
                            $msg = str_replace('', '</html>', $msg);
                            echo $msg;
                        ?>
                    </div>
                </div>
            </div>            
            <!-- /.box -->
        </div>
	</div>
@stop