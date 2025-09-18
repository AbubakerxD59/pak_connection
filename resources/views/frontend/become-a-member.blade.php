@extends('frontend.main')
@section('body')
    <div class="container buy-membership mt-4 mb-5 my-md-5">
        <h2 class="membership-heading">Your cart</h2>
        <span class="h5">Enter your details</span>
        <form action="{{ route('frontend.checkout') }}" method="POST" class="col-md-12 membership-form">
            @csrf
            <input type="hidden" name="price_id" value="{{ $price->id }}">
            <div class="row">
                <div class="col-md-8 cart-details bg-light rounded p-4">
                    <h2>{{ $price->package->name }}</h2>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="full_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                value="{{ auth()->check() ? auth()->user()->full_name : old('full_name') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ auth()->check() ? auth()->user()->email : old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if (!auth()->check())
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="{{ old('password') }}" required>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="whatsapp_number">Whatsapp Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="whatsapp_number" name="whatsapp_number"
                                value="{{ auth()->check() ? auth()->user()->whatsapp_number : old('whatsapp_number') }}"
                                required>
                            @error('whatsapp_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone_number">Phone Number</label>
                            <input type="number" class="form-control" id="phone_number" name="phone_number"
                                value="{{ auth()->check() ? auth()->user()->phone_number : old('phone_number') }}">
                            @error('full_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="{{ auth()->check() ? auth()->user()->city : old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="form-group col-md-6">
                            <label for="country">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="country" name="country"
                                value="{{ auth()->check() ? auth()->user()->country : old('country') }}" required>
                            @error('country')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St"
                            value="{{ auth()->check() ? auth()->user()->address : old('address') }}">
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <h4>Emergency Contact Information</h4>
                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="emergency_full_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="emergency_full_name"
                                name="emergency_full_name"
                                value="{{ auth()->check() ? auth()->user()->emergency_full_name : old('emergency_full_name') }}">
                            @error('emergency_full_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="form-group col-md-6">
                            <label for="emergency_phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input type="xt" class="form-control" id="emergency_phone_number"
                                name="emergency_phone_number"
                                value="{{ auth()->check() ? auth()->user()->emergency_phone_number : old('emergency_phone_number') }}">
                            @error('emergency_phone_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-4 cart-summary bg-white rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label for="period" class="form-label">Period</label>
                        <input type="text" class="form-control col-3" value="{{ $price->type_text }}" disabled
                            readonly>
                    </div>
                    <h4 class="mt-3">Features</h4>
                    <ul class="package-list">
                        @foreach ($price->package->checkFeatures() as $key => $feature)
                            @if ($key == 'include')
                                @foreach ($feature as $include)
                                    <li>
                                        <span class="circle-icon bg-primary">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        {{ $include }}
                                    </li>
                                @endforeach
                            @elseif($key == 'extra')
                                @foreach ($feature as $extra)
                                    <li>
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
                    <h4>Subtotal</h4>
                    <h3 class="summary-price">
                        {{ number_format((float) $price->price, 2) . ' £' }}
                        {{-- <span class="text-muted text-decoration-line-through">Rs.35,952.00</span> --}}
                    </h3>
                    {{-- <p class="text-success">Discount -72% <span class="text-danger">-Rs.69,120.00</span></p> --}}
                    <div class="mb-5">
                        <label for="coupon" class="form-label">Have a coupon code?</label>
                        <input type="text" name="promo" class="form-control" placeholder="Enter coupon code"
                            value="{{ old('promo') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Continue</button>
                    {{-- <small class="text-muted d-block mt-3">30-day money-back guarantee</small> --}}
                </div>
            </div>
        </form>
    </div>
@endsection
