@extends('layouts.blank')

@section('title', 'login Kalab & Kasublab')

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-warning">
            {{ Session::get('message') }}
        </div>
    @endif
    <form id="login-form" action="{{ route('login') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="username">Username</label>
            <input type="number" class="form-control underlined" name="id" id="username" placeholder="Your email address" required> </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control underlined" name="password" id="password" placeholder="Your password" required> </div>
        <div class="form-group">
            <label for="remember">
                <input class="checkbox" id="remember" type="checkbox">
                <span>Remember me</span>
            </label>
            <a href="reset.html" class="forgot-btn pull-right">Forgot password?</a>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary">Login</button>
        </div>
        <div class="form-group">
            <p class="text-muted text-center">Do not have an account?
                <a href="signup.html">Sign Up!</a>
            </p>
        </div>
    </form>
@endsection

@push('js')
    @if(Session::has('message'))
        <script>
            swal({
                icon: "error",
                title: "{{ Session::get('message') }}"
            });
        </script>
    @endif
@endpush