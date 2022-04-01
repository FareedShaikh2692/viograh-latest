@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('title', $title)
@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_body')
<p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
@include('manage.include.notification')
<form action="{{ url('manage/login') }}" method="post" id="admin_login_form">
    {{ csrf_field() }}
    <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
            placeholder="{{ trans('adminlte::adminlte.email') }}" autofocus>
    </div>
    <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
        <input type="password" name="password" class="form-control"
            placeholder="{{ trans('adminlte::adminlte.password') }}">
    </div>
    <div class="row">
        <div class="col-8">
            <a href="{{ $password_reset_url }}">
            {{ __('adminlte::adminlte.i_forgot_my_password') }}
        </a>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-dark btn-block btn-flat">
                {{ __('adminlte::adminlte.sign_in') }}
            </button>
        </div>
    </div>
</form>
@stop
@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @include("vendor.adminlte.js_message")
    @stack('js')
    @yield('js')
@stop

