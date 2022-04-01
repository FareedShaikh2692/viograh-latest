@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('title', "Admin Forgot password")
@php( $password_email_url = View::getSection('password_email_url') ?? config('adminlte.password_email_url', 'password/email') )

@if (config('adminlte.use_route_url', false))
    @php( $password_email_url = $password_email_url ? route($password_email_url) : '' )
@else
    @php( $password_email_url = $password_email_url ? url($password_email_url) : '' )
@endif



@section('auth_body')

    <p class="login-box-msg">{{ trans('adminlte::adminlte.password_reset_message') }}</p>
    @include('manage.include.notification')
    <form action="{{ url('manage/password/email') }}" method="post" id="admin_forgot_form">
        {!! csrf_field() !!}

        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
            <input type="email" name="email" class="form-control" value="{{ isset($email) ? $email : old('email') }}"
                    placeholder="{{ trans('adminlte::adminlte.email') }}" autofocus>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <button type="submit"
                class="btn btn-dark btn-block btn-flat"
        >{{ trans('adminlte::adminlte.send_password_reset_link') }}</button>
    </form>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a href="{{ route('home') }}" class="login-link">
                Back To Login
            </a>
        </div>
    </div>

@stop
@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @include("vendor.adminlte.js_message")
    @stack('js')
    @yield('js')
@stop
