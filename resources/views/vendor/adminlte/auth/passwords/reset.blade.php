@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('title', "Admin reset password")

@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_body')
<p class="login-box-msg">{{ trans('adminlte::adminlte.reset_password_here') }}</p>
@include('manage.include.notification')
<form action="{{ url($action) }}" method="post" id="admin_reset_password">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ @$token }}">

    <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
        <input type="email" name="email" class="form-control" value="{{ isset($email) ? $email : old('email') }}"
                placeholder="{{ trans('adminlte::adminlte.email') }}" autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
        <input type="password" id="password" name="password" class="form-control"
                placeholder="{{ trans('adminlte::adminlte.new_password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
        <input type="password" name="password_confirmation" class="form-control"
                placeholder="{{ trans('adminlte::adminlte.cnf_password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif
    </div>
    <button type="submit"
            class="btn btn-dark btn-block btn-flat"
    >{{ trans('adminlte::adminlte.reset_password') }}</button>
</form>
@stop

@section('adminlte_js')
    @include("vendor.adminlte.js_message")
    @yield('js')
@stop