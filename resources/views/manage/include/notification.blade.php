<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		@if($errors->any())
			<div class="alert alert-danger alert-dismissible alert-notify">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{{--<h4><i class="icon fa fa-ban"></i> Error!</h4>--}}
				@foreach(array_unique($errors->all()) as $error)
					{{ $error }}<br/>
				@endforeach
			</div>
		@endif
		@if ($message = Session::get('error'))
			<div class="alert alert-danger alert-dismissible ">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{{--<h4><i class="icon fa fa-ban"></i> Error!</h4>--}}
				{{ $message }}
			</div>
		@endif
		@if ($message = Session::get('success'))
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{{--<h4><i class="icon fa fa-check"></i> Success!</h4>--}}
				{{ $message }}
			</div>
		@endif
	</div>
</div>