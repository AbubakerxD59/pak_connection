@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2 class="welcome-title">Welcome to the<br> <strong class="green-color">PAK CONNECTIONS</strong></h2>
            <h3 class="member-name"><span class="green-color">{{ auth()->user()->full_name }}</span></h3>
            <div class="d-flex justify-content-center">
                <div class="card membership-card col-md-6 col-lg-5">
                    <div class="card-body">
                        <h4 class="membershit-title">Current Membership : <br><strong
                                class="green-color">{{ auth()->user()->getPackage() ? auth()->user()->getPackage()->name : '-' }}</strong>
                        </h4>
                        <h6>Expiry Date: <span
                                class="green-color font-bold">{{ date('Y-m-d', strtotime(auth()->user()->pkg_end_time)) }}</span>
                        </h6>
                    </div>
                </div>
            </div>
        </header>
        <div class="customize-button text-center pt-4">
            <a class="btn btn-primary mt-3" href="{{ route('frontend.showLogin') }}">Order a Service</a>
            <a class="btn btn-support mt-3" href="tel:+923205023407">
                <span><img src="/assets/img/headphone.png" alt="Head Phone"></span>
                Contact Support
            </a>
        </div>
    </section>
@endsection
