@extends('adminlte::page')

@section('title', $title)

@section('content_header')
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-6">
			<h3>{{ $title }}</h3>
			<ol class="breadcrumb">
				<li><a href="{{ url('/manage') }}"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="{{ url('/manage/settings') }}">{{ $main_module }}</a></li>
				<li class="active">{{ $title }}</li>
			</ol>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<a href="{{ url('/manage/settings') }}" data-toggle="tooltip" title="Back" class="a_link"><i class="fa fa-arrow-left"></i> Back</a>
		</div>
	</div>
@stop
@section('content')
	@include('manage.include.notification')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title"> Setting Information</h3>
				</div>
				<form class="form-horizontal" id="{{$frm_id}}" enctype="multipart/form-data" action="{{ $action }}" method="post">
					{{ csrf_field() }}
					<div class="card-body">
						<div class="form-group row">
							<label class="col-sm-2 control-label">Title<i class="text-danger">*</i></label>
							<div class="col-sm-5"><input class="form-control" type="text" name="title" id="title" value="{{ old('title',@$setting->name) }}"/></div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 control-label">Key<i class="text-danger">*</i></label>
							<div class="col-sm-5"><input class="form-control" type="text" name="key" id="key" {{@$setting->setting_key!=''?'disabled':''}}  value="{{ old('key',@$setting->setting_key) }}"/></div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 control-label">Value<i class="text-danger">*</i></label>
							<div class="col-sm-5"><input class="form-control" type="text" name="value" id="value" value="{{ old('value',@$setting->value) }}"/></div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 control-label">Comment</label>
							<div class="col-sm-5">
								<textarea class="form-control" name="comment" id="comment">{{ old('comment',@$setting->comment) }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="offset-2 col-sm-10">
								<button type="submit" class="btn btn-dark">Submit</button>
							</div>
						</div>
					</div>
				</form>
                </div>
            </div>
        </div>
    </div>
@stop