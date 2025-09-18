@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <form action="{{ route('frontend.member.profile_update') }}" method="POST">
            @csrf
            <div class="col-12 row">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="full_name" class="form-label">Full Name</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="full_name" id="full_name"
                            value="{{ old('full_name', $user->full_name) }}" placeholder="Enter user name" required>
                        @error('full_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                    </div>
                    <div class="col-md-9">
                        <input type="email" class="form-control" name="email" id="email"
                            value="{{ old('email', $user->email) }}" placeholder="Enter user email" readonly disabled>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="membership_id" class="form-label">Membership ID</label>
                    </div>
                    <div class="col-md-9">
                        <input type="email" class="form-control" name="membership_id" id="membership_id"
                            value="{{ old('membership_id', $user->membership_id) }}" readonly disabled>
                        @error('membership_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="whatsapp_number" class="form-label">Whatsapp#</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number"
                            value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                            placeholder="Enter whatsapp number">
                        @error('whatsapp_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            value="{{ old('phone_number', $user->phone_number) }}" placeholder="Enter phone number">
                        @error('phone_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="package" class="form-label">Package</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="package" id="package"
                            value="{{ old('package', $user->getPackage() ? $user->getPackage()->name : '') }}" readonly
                            disabled>
                        @error('package')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="city" class="form-label">City</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="city" id="city"
                            value="{{ old('city', $user->city) }}" placeholder="Enter city">
                        @error('city')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="country" class="form-label">Country</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="country" id="country"
                            value="{{ old('country', $user->country) }}" placeholder="Enter country">
                        @error('country')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row col-6">
                    <div class="col-md-3">
                        <label for="address" class="form-label">Address</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="address" id="address"
                            value="{{ old('address', $user->address) }}" placeholder="Enter address">
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row justify-content-center col-12 mt-4">
                    <button type="submit"
                        class="btn btn-primary col-3">{{ __('users.btn_submit_text') }}</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('script')
@endpush
