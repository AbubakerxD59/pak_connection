@extends('frontend.main')
@section('body')
    <div class="membership-portal">
        <h1>Membership Portal</h1>
        <h2>Choose your Package</h2>
        <div class="packages">
            @foreach ($packages as $package)
                <div class="package">
                    <div class="package-box">
                        <h3>{{ $package->name }}</h3>
                        <p>{{ '£' . $package->price . ' for ' . $package->date_duration }} </p>
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
                    <a href="{{ route('frontend.buy_memebership', $package->id) }}" class="btn btn-primary">Buy Now</a>
                </div>
            @endforeach
        </div>
        {{-- <button class="customize-plan">Customize your Plan</button> --}}
    </div>
@endsection
