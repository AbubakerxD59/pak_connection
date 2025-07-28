@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2>Welcome to the membership <span class="green-color">Portal</span></h2>
            <h3>Choose your <span class="green-color">Package</span></h1>
        </header>
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


                    <a href="{{ route('frontend.buy_memebership', $package->id) }}" class="btn btn-primary w-100">Buy
                        Now</a>
                </article>
            @endforeach
        </div>
        <!-- {{-- <button class="customize-plan">Customize your Plan</button> --}} -->
        <div class="customize-button text-center pt-5">
            <a class="btn btn-primary mt-md-3 w-100" href="{{ route('frontend.showLogin') }}">Already A Member</a>
        </div>
    </section>
@endsection
