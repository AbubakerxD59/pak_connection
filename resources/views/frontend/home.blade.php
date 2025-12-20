@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header text-center mb-4">
            <h2 class="welcome-title">Welcome to <strong class="green-color">Membership Portal</strong></h2>
            <h2 class="welcome-title">Choose Your <strong class="green-color">Package</strong></h2>
        </header>

        @if ($package)
            @php
                $selectedType = 1; // Default to 1 Month
                $selectedPrice = $package->prices->where('type', $selectedType)->first();
                $features = $package->features()->orderBy('order', 'ASC')->get();
            @endphp

            <div class="package-selector-container mb-4">
                <div class="d-flex justify-content-center mb-4">
                    <div
                        class="col-md-6 d-flex justify-content-around justify-content-between package-duration-selector shadow">
                        @foreach (get_options('package_prices') as $key => $value)
                            <button class="package-duration-btn {{ $key == $selectedType ? 'active' : '' }}"
                                data-type="{{ $key }}" data-package-id="{{ $package->id }}">
                                {{ $value }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="package-card-container">
                    <div class="card package-main-card shadow">
                        <div class="card-body p-4">
                            <div class="row">
                                <!-- Left Section -->
                                <div class="col-md-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <i class="fa fa-shield-alt text-success mr-2" style="font-size: 18px;"></i>
                                        <h4 class="mb-0 font-weight-bold" style="font-size: 18px;">
                                            {{ $package->name }}</h4>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-6 text-center">
                                            <div class="package-price mb-5">
                                                <p class="text-center">
                                                    <span id="price-amount" class="h3 text-success font-weight-bold">
                                                        £{{ $selectedPrice ? number_format($selectedPrice->price, 0) : '0' }}
                                                    </span>

                                                    <span id="price-duration" class="h5 text-muted font-weight-bold">
                                                        / {{ $selectedPrice ? $selectedPrice->type_text : '1 Month' }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="package-buy">
                                                <a href="{{ $selectedPrice ? route('frontend.buy_memebership', $selectedPrice->id) : '#' }}"
                                                    class="btn btn-success p-3 w-100 mb-3" id="buy-button"
                                                    style="border-radius: 20px;">
                                                    Buy <span
                                                        id="buy-duration">{{ $selectedPrice ? $selectedPrice->type_text : '1 Month' }}</span>
                                                    Plan >
                                                </a>

                                                <div class="package-features-small mb-2 d-flex">
                                                    <p class="mb-2" style="font-size: 12px;">
                                                        Secure SSL Payment
                                                    </p>
                                                    <p class="mb-0 ml-1" style="font-size: 12px;">
                                                        <i class="fa fa-circle text-success"
                                                            style="font-size: 0.4rem; vertical-align: middle;"></i> Instant
                                                        Activation
                                                    </p>
                                                </div>

                                                <a href="tel:{{ setting('support_phone') }}"
                                                    class="btn border w-75 text-success"
                                                    style="border-radius: 20px; font-size: 12px;">
                                                    <i class="fa fa-phone"></i> Contact Sales Team
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Section -->
                                <div class="col-md-7">
                                    <div class="package-features-right">
                                        <h5 class="mb-3">
                                            <i class="fa fa-star text-warning"></i> What's Included
                                        </h5>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="list-unstyled feature-list">
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        24/7 Emergency Assistance
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        24/7 Personal Request Line
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        Bereavement Support
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        Airport VIP Meet & Greet
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        Accommodation Arrangements
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        Chauffeur & Transportation Services
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        Children's Activities & Support
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="list-unstyled feature-list">
                                                    @if ($features->count() > 0)
                                                        @foreach ($features->slice(7) as $feature)
                                                            <li class="mb-2">
                                                                <i class="fa fa-check-circle text-success mr-2"></i>
                                                                {{ $feature->name }}
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Guided Pakistan Tours
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Personal Shopping & Errand Services
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Wedding & Event Planning
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Medical Tourism Assistance
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Legal Support Services
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Security & Protection Services
                                                        </li>
                                                        <li class="mb-2">
                                                            <i class="fa fa-check-circle text-success mr-2"></i>
                                                            Household Staff Coordination
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <hr class="my-3">

                                        <div class="additional-benefits">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1">
                                                    <i class="fa fa-circle text-success mr-2"
                                                        style="font-size: 0.5rem;"></i>
                                                    Global Coverage
                                                </li>
                                                <li class="mb-1">
                                                    <i class="fa fa-circle text-success mr-2"
                                                        style="font-size: 0.5rem;"></i>
                                                    24/7 Priority Support
                                                </li>
                                                <li class="mb-0">
                                                    <i class="fa fa-circle text-success mr-2"
                                                        style="font-size: 0.5rem;"></i>
                                                    Money Back Guarantee
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="customize-button text-center pt-4 pt-md-5">
                @if (auth()->check())
                    <a class="btn btn-primary mt-3" href="{{ route('frontend.showLogin') }}">Order a Service</a>
                @else
                    <a class="btn btn-primary mt-3" href="{{ route('frontend.showLogin') }}">Already A Member</a>
                @endif
                <a class="btn btn-support mt-3" href="tel:{{ setting('support_phone') }}">
                    <span>
                        <img src="/assets/img/headphone.png" alt="Head Phone">
                    </span>
                    Contact Support
                </a>
            </div>
        @else
            <div class="alert alert-warning text-center">
                No packages available at the moment.
            </div>
        @endif
    </section>
@endsection

@push('styles')
    <style>
        .membership-header {
            margin-bottom: 30px;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .green-color {
            color: #28a745;
        }

        .package-duration-selector {
            background: white;
            border-radius: 50px;
            padding: 4px;
            display: inline-flex;
            position: relative;
        }

        .package-duration-btn {
            background: transparent;
            border: none;
            color: #666;
            padding: 10px 30px;
            margin: 0;
            border-radius: 50px;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        .package-duration-btn:hover {
            color: #333;
        }

        .package-duration-btn.active {
            background: #28a745;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .package-main-card {
            border: none;
            border-radius: 8px;
            border-top: 4px solid #28a745;
            margin-top: 20px;
        }

        .package-info-left {
            padding-right: 20px;
        }

        .package-info-left h4 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .package-price h2 {
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .btn-success.btn-lg {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 5px;
        }

        .package-features-small p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .btn-outline-success {
            border: 2px solid #28a745;
            color: #28a745;
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 5px;
        }

        .btn-outline-success:hover {
            background: #28a745;
            color: white;
        }

        .package-features-right h5 {
            font-size: 15px;
            font-weight: 600;
        }

        .feature-list {
            padding-left: 0;
        }

        .feature-list li {
            font-size: 16px;
            line-height: 1.8;
            padding: 3px 0;
        }

        .feature-list i.fa-check-circle {
            font-size: 1rem;
        }

        .additional-benefits {
            margin-top: 15px;
        }

        .additional-benefits li {
            font-size: 16px;
            ;
            line-height: 2;
        }

        .customize-button {
            margin-top: 40px;
            padding-bottom: 30px;
        }

        .btn-support {
            background: #f8f9fa;
            border: 2px solid #28a745;
            color: #28a745;
            padding: 10px 25px;
            margin-left: 10px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-support:hover {
            background: #28a745;
            color: white;
        }

        .btn-support img {
            width: 20px;
            height: 20px;
        }

        .package-buy {
            margin-top: 100px;
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 1.5rem;
            }

            .package-duration-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
                margin: 0 2px;
            }

            .package-price h2 {
                font-size: 2rem;
            }

            .package-info-left {
                padding-right: 0;
                margin-bottom: 20px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            @if ($package && $package->prices)
                var packagePrices = {
                    @foreach ($package->prices as $price)
                        {{ $price->type }}: {
                            id: {{ $price->id }},
                            price: {{ $price->price }},
                            type_text: "{{ $price->type_text }}"
                        },
                    @endforeach
                };

                $('.package-duration-btn').on('click', function() {
                    var type = $(this).data('type');
                    var price = packagePrices[type];

                    if (price) {
                        // Update active button
                        $('.package-duration-btn').removeClass('active');
                        $(this).addClass('active');

                        // Update price display
                        $('#price-amount').text('£' + price.price.toLocaleString());
                        $('#price-duration').text('/' + price.type_text);

                        // Update buy button
                        var buyUrl = '{{ route('frontend.buy_memebership', ':id') }}'.replace(':id', price
                            .id);
                        $('#buy-button').attr('href', buyUrl);
                        $('#buy-duration').text(price.type_text);
                    }
                });
            @endif
        });
    </script>
@endpush
