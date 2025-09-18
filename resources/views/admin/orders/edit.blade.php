@extends('admin.layouts.secure')
@section('page_title', 'Edit Order')
@section('page_content')
    @can('edit_orders')
        <div class="page-content">
            <form method="POST" action="{{ route('orders.update', $order->id) }}" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit Order
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('orders.index') }}">back to Orders list</a>
                        </small>
                    </h1>
                    <div class="float-right">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="far fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <i class="fas fa-info"></i>
                                            Info
                                        </div>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="customer" class="form-label">Customer</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="customer"
                                                    value="{{ $order->getUser() }}" readonly disabled>
                                                <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                                                @error('customer')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="package" class="form-label">Package</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="package"
                                                    value="{{ $order->getPackage() }}" readonly disabled>
                                                <input type="hidden" name="package_id" value="{{ $order->package_id }}">
                                                @error('package')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="coupon" class="form-label">Coupon</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="coupon"
                                                    value="{{ $order->getCoupon() }}" readonly disabled>
                                                <input type="hidden" name="promo_id" value="{{ $order->promo_id }}">
                                                @error('coupon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="package_amount" class="form-label">Package Amount</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="package_amount"
                                                    value="{{ '£' . $order->price?->price }}" readonly disabled>
                                                <input type="hidden" name="total_amount" value="{{ $order->total_amount }}">
                                                @error('coupon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="discount_amount" class="form-label">Discount Amount</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="discount_amount"
                                                    value="{{ $order->getDiscount() }}" readonly disabled>
                                                @error('coupon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="total_amount" class="form-label">Total Amount</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="total_amount"
                                                    value="{{ $order->getTotal() }}" readonly disabled>
                                                @error('coupon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="status" id="status" class="form-control">
                                                    @foreach (\App\Models\Order::$status_array as $key => $status)
                                                        <option value="{{ $key }}"
                                                            {{ $key == $order->status ? 'selected' : '' }}>{{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('coupon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 text-right">
                                            <button type="submit"
                                                class="btn btn-outline-success">{{ __('users.btn_submit_text') }}</button>
                                            <a href="{{ route('users.index') }}"
                                                class="btn btn-outline-dark">{{ __('users.btn_cancel_text') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    @endcan
@endsection
@push('scripts')
@endpush
