@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2>Welcome to the Membership<br> <span class="green-color">Portal</span></h2>
            <h3>Choose your <strong class="green-color">Package</strong></h1>
        </header>
        <div class="toggle-container">
            @foreach (get_options('package_prices') as $key => $value)
                <button class="btn btn-primary mx-2 btn-price {{ $key == 1 ? 'active_pricing' : '' }}"
                    href="package_price-{{ $key }}">
                    {{ $value }}
                </button>
            @endforeach
        </div>
        {{-- package pricing --}}
        @foreach (get_options('package_prices') as $key => $value)
            @include('frontend.pricing.packages')
        @endforeach

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

@push('styles')
    <style>
        .toggle-container {
            padding: 5px;
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .btn-active {
            /* Custom styling for the active button */
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 500;
        }

        .btn-interval {
            /* Styling for the text */
            font-size: 1rem;
            color: #0d6efd;
            /* Blue color matching Bootstrap primary */
            font-weight: 500;
            padding: 0 15px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('.btn-price').on("click", function() {
                $('.btn-price').removeClass("active_pricing");
                $(this).addClass("active_pricing");
                var href = $(this).attr("href");
                $('.packages').hide();
                $('#' + href).show();
            });
        });
    </script>
@endpush
