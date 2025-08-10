@extends('frontend.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login-styles.css') }}">
@endpush
@section('body')
    <div class="col-md-12 d-flex justify-content-center align-items-center my-5">
        <div class="col-md-3 card login-card-box p-5">
            <p class="login-title">{{ __('passwords.password_page_title') }}</p>
            <form method="post" action="{{ route('password.send_link') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="email" placeholder="Membership ID/Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="social-auth-links text-center mb-3">
                    <button type="submit" class="btn btn-primary btn-block">{{ __('passwords.send_link') }}</button>
                </div>
                {{-- @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif --}}

            </form>
        </div>
    </div>
@endsection
