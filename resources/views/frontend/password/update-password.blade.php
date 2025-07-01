@extends('frontend.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/login-styles.css') }}">
@endpush
@section('body')
    <div class="col-md-12 d-flex justify-content-center align-items-center">
        <div class="col-md-3 card p-5">
            @if ($record == '404')
                <div class="alert alert-danger">
                    Invalid link or no record found.
                </div>
            @else
                <p class="login-box-msg">{{ __('passwords.update_password_page_title') }}</p>


                @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


                <form method="post" action="{{ route('password.reset') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="email" placeholder="Membership ID/Email" required>
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
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror



                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror




                    <div class="social-auth-links text-center mb-3">
                        <button type="submit"
                            class="btn btn-primary btn-block">{{ __('passwords.update_password') }}</button>

                    </div>

                </form>
            @endif
        </div>
    </div>
@endsection
