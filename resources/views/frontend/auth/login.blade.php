@extends('frontend.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login-styles.css') }}">
@endpush
@section('body')
    <div class="col-md-12 d-flex justify-content-center align-items-center my-5">
        <div class="col-md-3 card p-5 login-card-box">
            <p class="login-title">{{ __('auth.login_page_title') }}</p>
            <form method="post" action="{{ route('frontend.login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="membership_id" placeholder="Membership ID/Email"
                        value="{{ old('membership_id') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-12">
                        <input type="hidden" name="remember_me" value="0">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember_me" name="remember_me" value="1">
                            <label for="remember_me">{{ __('auth.remember_me') }}</label>
                        </div>
                    </div>
                </div>
                <div class="social-auth-links text-center mb-3">
                    <button type="submit" class="btn btn-primary btn-block">{{ __('auth.btn_sign_in') }}</button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="icheck-primary text-center">
                            <a href="{{ route('password.request') }}" class="btn btn-support w-100">Forgot Your
                                Password?</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
