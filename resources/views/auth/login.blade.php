@extends('adminlte.master')

@section('title', 'Login Page')

@section('adminlte_css')
<style>
    .login-page {
        height: 100vh;
        min-height: 500px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
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

@section('body')
<div class="login-page">
    <div class="container">
        <div class="card flex-row p-5">
            <div class="col-6 d-flex justify-content-center">
                <div class="pe-5" style="border-right: 1px solid rgba(0, 0, 0, 0.125)">
                    <img src="{{ asset('assets/image/logo/login.png') }}" alt="logo" style="max-width: 90%" />
                </div>
            </div>
            <div class="col-6 d-flex align-items-center">
                <div class="ps-5">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('assets/image/logo/sinko.png') }}" alt="sinko" style="width: 30%" />
                    </div>
                    <div>
                        <form action="{{ $login_url }}" method="post">
                            {{ csrf_field() }}
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control" placeholder="email" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span><i class="fas fa-envelope"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="password" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <div>
                                    <button type="submit" class="btn btn-block btn-primary">
                                        <span class="fas fa-sign-in-alt"></span>
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop