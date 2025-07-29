@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2>Welcome to the <span class="green-color">PAK CONNECTIONS</span></h2>
            <h3><span class="green-color">{{ auth()->user()->full_name }}</span></h3>
            <div class="d-flex justify-content-center">
                <div class="card col-6">
                    <div class="card-body">
                        <h4>Current Membership : <span class="green-color">{{ auth()->user()->getPackage()->name }}</span>
                        </h4>
                        <h6>Expiry Date: <span class="text-danger">{{ auth()->user()->pkg_end_time }}</span></h6>
                    </div>
                </div>
            </div>
        </header>
        <div class="customize-button text-center pt-5">
            <a class="btn btn-primary mt-md-3 w-25" href="{{ route('frontend.showLogin') }}">Order a Service</a>
            <a class="btn btn-support mt-md-3 w-25" href="tel:+923205023407">
                <span><i class="fa fa-phone"></i></span>
                Contact Support
            </a>
        </div>
    </section>
@endsection
