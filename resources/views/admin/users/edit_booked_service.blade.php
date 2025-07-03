@extends('admin.layouts.secure')
@section('page_title', 'Booked Service')
@section('page_content')
    @can('edit_booked_service')
        <div class="page-content">
            <form method="POST" action="{{ route('users.booked_service.update', $bookedService->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
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
                                                <label for="user" class="form-label">Customer</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="user"
                                                    value="{{ $bookedService->getUser() }}" required readonly>
                                                <input type="hidden" value="{{ $bookedService->user_id }}" name="user_id">
                                                @error('user')
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
                                                    value="{{ $bookedService->getPackage() }}" required readonly>
                                                <input type="hidden" value="{{ $bookedService->package_id }}"
                                                    name="package_id">
                                                @error('package')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="service" class="form-label">Service</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="service"
                                                    value="{{ $bookedService->getService() }}" required readonly>
                                                <input type="hidden" value="{{ $bookedService->service_id }}"
                                                    name="service_id">
                                                @error('service')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        @foreach ($bookedService->load('bookFields')->bookFields as $bookField)
                                            @php $field = $bookField->getField(); @endphp
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label for="{{ $field->name }}" class="form-label">
                                                        {{ $field->name }}
                                                    </label>
                                                </div>
                                                <div class="col-md-9">
                                                    @if ($field->type == 'dropdown')
                                                        <select class="form-control" name="fields[{{ $field->id }}]"
                                                            id="{{ $field->name }}" required>
                                                            @foreach ($field->options as $option)
                                                                <option value="{{ $option }}"
                                                                    {{ $bookField->value == $option ? 'selected' : '' }}>
                                                                    {{ str_replace('"', '', $option) }}</option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($field->type == 'textarea')
                                                        <textarea class="form-control" name="fields[{{ $field->id }}]" id="{{ $field->name }}" required>{{ $bookField->value }}</textarea>
                                                    @else
                                                        <input type="{{ $field->type }}" class="form-control"
                                                            name="fields[{{ $field->id }}]" id="{{ $field->name }}"
                                                            value="{{ $bookField->value }}" onclick="this.showPicker()"
                                                            required>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1"
                                                        {{ $bookedService->status == '1' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="2"
                                                        {{ $bookedService->status == '2' ? 'selected' : '' }}>
                                                        Approved</option>
                                                    <option value="3"
                                                        {{ $bookedService->status == '3' ? 'selected' : '' }}>
                                                        Started</option>
                                                    <option value="4"
                                                        {{ $bookedService->status == '4' ? 'selected' : '' }}>
                                                        Finished</option>

                                                    <option value="5"
                                                        {{ $bookedService->status == '5' ? 'selected' : '' }}>Deposit Requested
                                                    </option>
                                                    <option value="6"
                                                        {{ $bookedService->status == '6' ? 'selected' : '' }}>Order in Progress
                                                    </option>
                                                    <option value="7"
                                                        {{ $bookedService->status == '7' ? 'selected' : '' }}>Invoice Created
                                                    </option>

                                                    <option value="8"
                                                        {{ $bookedService->status == '8' ? 'selected' : '' }}>Full Payment
                                                        Received</option>
                                                    <option value="9"
                                                        {{ $bookedService->status == '9' ? 'selected' : '' }}>Schedule Created
                                                    </option>
                                                    <option value="10"
                                                        {{ $bookedService->status == '10' ? 'selected' : '' }}>Pre Arrival
                                                    </option>
                                                    <option value="11"
                                                        {{ $bookedService->status == '11' ? 'selected' : '' }}>Member Arrived
                                                    </option>
                                                    <option value="12"
                                                        {{ $bookedService->status == '12' ? 'selected' : '' }}>Order Completed
                                                    </option>

                                                    <option value="-1"
                                                        {{ $bookedService->status == '-1' ? 'selected' : '' }}>
                                                        Rejected</option>
                                                </select>
                                                @error('service')
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
