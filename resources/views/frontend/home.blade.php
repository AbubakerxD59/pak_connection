@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2>Welcome to the membershipc<br> <span class="green-color">Portal</span></h2>
            <h3>Choose your <strong class="green-color">Package</strong></h1>
        </header>
        {{-- <div class="packages"> --}}
        <div class="packages">
            @foreach ($packages as $package)
                <article class="package">
                    <div class="package-box">
                        <header class="packages-header">
                            <h3>{{ $package->name }}</h3>
                            <span class="font-weight-bold h5">
                                {{ '£' . $package->price }}
                            </span>
                            for
                            <span>
                                {{ $package->date_duration }}
                            </span>
                        </header>
                        <ul class="package-list">
                            @foreach ($package->checkFeatures() as $key => $feature)
                                @if ($key == 'include')
                                    <span><strong>Includes:</strong></span>
                                    @foreach ($feature as $include)
                                        <li class="parent-li">
                                            <span class="circle-icon bg-primary">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            {{ $include }}
                                        </li>
                                    @endforeach
                                @elseif($key == 'extra')
                                    <span><strong>Plus:</strong></span>
                                    @foreach ($feature as $extra)
                                        <li class="parent-li">
                                            <span class="circle-icon bg-primary">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            {{ $extra }}
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <span class="circle-icon bg-primary">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        {{ $feature }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    @if (!empty($package->is_user_package) && !$package->is_expired)
                        <p class="text-success">Ends on:
                            {{ \Carbon\Carbon::parse($package->pkg_end_time)->format('F d, Y h:i A') }}</p>
                    @else
                        <a href="{{ route('frontend.buy_memebership', $package->id) }}" class="btn btn-primary w-100">Buy
                            Now</a>
                    @endif

                    {{-- <a href="{{ route('frontend.buy_memebership', $package->id) }}" class="btn btn-primary w-100">Buy
                        Now</a> --}}
                </article>
            @endforeach
        </div>
        <!-- {{-- <button class="customize-plan">Customize your Plan</button> --}} -->
        <div class="customize-button text-center pt-4 pt-md-5">
            @if (auth()->check())
                <a class="btn btn-primary mt-3" href="{{ route('frontend.showLogin') }}">Order a Service</a>
            @else
                <a class="btn btn-primary mt-3" href="{{ route('frontend.showLogin') }}">Already A Member</a>
            @endif
            <a class="btn btn-support mt-3" href="tel:+923205023407">
                <span>
                     <img src="/assets/img/headphone.png" alt="Head Phone">
                </span>
                Contact Support
            </a>
        </div>
    </section>
@endsection
