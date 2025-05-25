@extends('frontend.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login-styles.css') }}">
@endpush
@section('body')
    <div class="col-md-12 d-flex justify-content-center align-items-center">
        <div class="col-md-3 card p-5">
            <p class="login-box-msg">{{ __('auth.login_page_title') }}</p>
            <form method="post" action="{{ route('frontend.login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="membership_id" placeholder="Membership ID/Email"
                        required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="row">
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
            </form>
        </div>
    </div>
@endsection
