@extends('adminlte::page')

@section('title', $title)

@section('content_header')
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-6">
			<h3>{{ $title }}</h3>
			<ol class="breadcrumb">
				<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="{{ url('/manage/web-pages') }}">{{ $main_module }}</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<a href="{{ url('/manage/web-pages') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
		</div>
	</div>
@stop

@section('content')
@include('manage.include.notification')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
          <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"> Web Page Information</h3>
            </div>
                  <form class="form-horizontal" id="frm_webpages" enctype="multipart/form-data" action="{{ $action }}" method="post">
                    {{ csrf_field() }}
                    <div class="card-body">
                      <div class="form-group row">
                        <label class="col-sm-2 control-label">Title<i class="text-danger">*</i></label>
                        <div class="col-sm-5"><input type="text" name="title" id="title" class="form-control" value="{{ old('title',@$webpage->page_title) }}"/></div>
                      </div>
                      @if(@$webpage->slug != '')
                        <div class="form-group row">
                          <label class="col-sm-2 control-label">Permalink</label>
                          <div class="col-sm-5"><input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug',route('permalink',@$webpage->slug)) }}" disabled="disabled"/></div>
                        </div>
                      @else
                        {{--<div class="form-group row">
                          <label class="col-sm-2 control-label">Permalink</label>
                          <div class="col-sm-5"><input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug',url(@$webpage->slug)) }}" /></div>
                        </div>--}}
                      @endif
                      <div class="form-group row">
                        <label class="col-sm-2 control-label">Heading<i class="text-danger">*</i></label>
                        <div class="col-sm-5"><input type="text" class="form-control" name="heading" id="heading" value="{{ old('meta_key',@$webpage->page_heading) }}"/></div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 control-label">Meta Keywords<i class="text-danger">*</i></label>
                        <div class="col-sm-5"><input type="text" class="form-control" name="meta_key" id="meta_key" value="{{ old('meta_key',@$webpage->meta_tag) }}"/></div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-2 control-label">Meta Description<i class="text-danger">*</i></label>
                        <div class="col-sm-5"><textarea name="meta_desc" class="form-control" id="meta_desc">{{ old('meta_desc',@$webpage->meta_description) }}</textarea></div>
                      </div>
                      <div class="form-group row">
                          <label for="page_content" class="col-sm-2 control-label">Page Content<i class="text-danger">*</i></label>
                          <div class="col-sm-8">
                            <textarea id="editor1" class="form-control" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px; padding: 10px; border:1px solid #ced4da"  name="page_content">{{ old('page_content',@$webpage->page_content) }}</textarea>
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