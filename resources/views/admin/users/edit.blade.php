@extends('admin.layouts.secure')
@section('page_title', $user->full_name)
@section('page_content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @can('edit_user')
        <div class="page-content">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit Customer
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('users.index') }}">back to Customers list</a>
                        </small>
                    </h1>
                    <div class="float-right">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="far fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
            </form>

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
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="full_name" id="full_name"
                                                value="{{ old('full_name', $user->full_name) }}"
                                                placeholder="Enter customer name" required>
                                            @error('full_name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ old('email', $user->email) }}" placeholder="Enter customer email">
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password" id="password">
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <?php
                                    $roleName = !empty($user->roles()->first()) ? $user->roles()->first()->id : '0';
                                    ?>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="role" class="form-label">Role</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select name="role" id="role" class="form-control">
                                                <option value="">Select Role</option>
                                                @if (count($roles) > 0)
                                                    @foreach ($roles as $role)
                                                        @if (!in_array($role->name, ['Super Admin']))
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role', $roleName) == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="active" class="form-label">Is Active</label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-check">
                                                <input type="hidden" name="active" value="0">
                                                <input type="checkbox" id="activeCheckbox" name="active"
                                                    class="form-check-input" value="1"
                                                    {{ $user->status ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active">Yes</label>
                                            </div>
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
                            {{-- Booked Services --}}
                            @can('view_booked_services')
                                <div class="card">
                                    <div class="card-header with-border clearfix">
                                        <div class="card-title">
                                            <i class="fas fa-star"></i>
                                            Booked Services
                                        </div>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-list">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered"
                                                            id="booked_services_dataTable">
                                                            <thead>
                                                                <th>ID</th>
                                                                <th>Customer</th>
                                                                <th>Service</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($user->load('bookServices')->bookServices as $key => $service)
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ $service->user->full_name }}</td>
                                                                        <td>{{ $service->service->name }}</td>
                                                                        <td>{!! service_book_status($service->status) !!}</td>
                                                                        <td>
                                                                            <div class="row">
                                                                                <div>

                                                                                    <button type="button"
                                                                                        class="btn btn-outline-secondary btn-sm generate-invoice-btn"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#invoiceModal"
                                                                                        data-id="{{ $service->id }}">
                                                                                        Generate Invoice
                                                                                    </button>

                                                                                </div>


                                                                                <div>
                                                                                    <span
                                                                                        class="btn btn-outline-secondary btn-sm view_booked_service deposit-payment-btn"
                                                                                        data-id="{{ $service->id }}">
                                                                                        Deposit Payment
                                                                                    </span>

                                                                                </div>

                                                                                <div>
                                                                                    <span
                                                                                        class="btn btn-outline-success btn-sm view_booked_service"
                                                                                        data-id="{{ $service->id }}">View</span>
                                                                                </div>
                                                                                @can('edit_booked_services')
                                                                                    <div>
                                                                                        <a href="{{ route('users.booked_service.edit', $service->id) }}"
                                                                                            class="btn btn-outline-primary btn-sm mx-1">Edit</a>
                                                                                    </div>
                                                                                @endcan
                                                                                @can('delete_booked_services')
                                                                                    <div>
                                                                                        <a href="{{ route('users.booked_service.delete', $service->id) }}"
                                                                                            class="btn btn-outline-danger btn-sm mx-1">Delete</a>
                                                                                    </div>
                                                                                @endcan
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </section>
            {{-- </form> --}}


            <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Generate Invoice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form id="invoiceForm" method="POST">
                            @csrf
                            <input type="hidden" name="book_service_id" id="modalBookedServiceId">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (£)</label>
                                    <input type="number" name="amount" id="amount" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="promo_code_id" class="form-label">Select Promo Code</label>
                                    <select name="promo_code_id" id="promo_code_id" class="form-select">
                                        <option value="">-- No Coupon --</option>
                                        @foreach ($promo_codes as $coupon)
                                            <option value="{{ $coupon->id }}"
                                                data-discount-type="{{ $coupon->discount_type }}"
                                                data-discount-amount="{{ $coupon->discount_amount }}">
                                                {{ $coupon->name }} ({{ $coupon->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Final Amount (£)</label>
                                    <input type="text" id="final_price" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Generate</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>




        </div>
    @endcan
@endsection
@include('admin.packages.view_booked_service')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@push('scripts')
    <script>
        $(document).ready(function() {
            var booked_services_dataTable = $('#booked_services_dataTable').DataTable({
                "paging": true,
                'iDisplayLength': 10,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script>
        $(document).on('click', '.view_booked_service', function() {
            var service_id = $(this).data('id');
            $.ajax({
                url: "{{ route('packages.show_booked_service') }}",
                type: "GET",
                data: {
                    "id": service_id
                },
                success: function(response) {
                    if (response.status) {
                        $("#view_booked_service").find(".modal-body").html(response.body);
                        $("#view_booked_service").find(".modal-title").html(response.title);
                        $("#view_booked_service").modal('toggle');
                    } else {
                        toastr.error(response.error);
                    }
                }
            });
        })
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // When button is clicked, inject service ID
            document.querySelectorAll('.generate-invoice-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const serviceId = this.getAttribute('data-id');
                    document.getElementById('modalBookedServiceId').value = serviceId;
                    document.getElementById('amount').value = '';
                    document.getElementById('final_price').value = '';
                    document.getElementById('promo_code_id').selectedIndex = 0;
                });
            });

            // Calculate final price
            const amountInput = document.getElementById('amount');
            const couponSelect = document.getElementById('promo_code_id');
            const finalPriceInput = document.getElementById('final_price');

            function calculateFinalPrice() {
                const amount = parseFloat(amountInput.value);
                const selected = couponSelect.options[couponSelect.selectedIndex];
                const discountType = selected.getAttribute('data-discount-type');
                const discountAmount = parseFloat(selected.getAttribute('data-discount-amount'));

                if (!amount || isNaN(amount)) {
                    finalPriceInput.value = '';
                    return;
                }

                let final = amount;

                if (discountType === 'percent') {
                    final -= (discountAmount / 100) * amount;
                } else if (discountType === 'fixed') {
                    final -= discountAmount;
                }

                final = Math.max(final, 0);
                finalPriceInput.value = final.toFixed(2);
            }

            amountInput.addEventListener('input', calculateFinalPrice);
            couponSelect.addEventListener('change', calculateFinalPrice);


            // ==================================================

            document.querySelectorAll('.generate-invoice-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const serviceId = this.getAttribute('data-id');
                    document.getElementById('modalBookedServiceId').value = serviceId;
                    document.getElementById('amount').value = '';
                    document.getElementById('final_price').value = '';
                    document.getElementById('promo_code_id').selectedIndex = 0;
                });
            });

            // Handle AJAX form submission
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);

                // const submitBtn = form.find('.generate-invoice-btn'); // get the button inside the form

                let clickedBtn = null; // global variable to store clicked button reference

                // Capture the clicked submit button before form submission
                $(document).on('click', '.generate-invoice-btn', function() {
                    clickedBtn = $(this); // store the reference globally
                });


                const url = "{{ route('users.book_service_invoice') }}";
                const data = {
                    _token: form.find('input[name="_token"]').val(),
                    book_service_id: $('#modalBookedServiceId').val(),
                    amount: $('#amount').val(),
                    final_price: $('#final_price').val(),
                    promo_code_id: $('#promo_code_id').val(),
                };

                if (clickedBtn) {
                    clickedBtn.prop('disabled', true).text('Processing...');
                }


                form[0].reset();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            booked_services_dataTable.ajax.reload(null, false);

                            // Open the Stripe payment link in a new tab
                            // window.open(response.url, '_blank');

                            // Hide the modal and reset the form

                        } else {
                            toastr.error(response.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Something went wrong!';
                        if (xhr.responseJSON?.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        // alert(errorMsg);
                        toastr.error(msg);
                    },
                    complete: function() {
                        // submitBtn.prop('disabled', false).text('Generate');

                        if (clickedBtn) {
                            clickedBtn.prop('disabled', false).text('Generate');
                        }

                        $('#invoiceModal').modal('hide');

                    }
                });

            });

        });
    </script>


    <script>
        $(document).on('click', '.deposit-payment-btn', function() {
            let bookedServiceId = $(this).data('id');

            $.ajax({
                url: '{{ route('users.deposit_payment') }}', // replace with your actual route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    book_service_id: bookedServiceId
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        booked_services_dataTable.ajax.reload(null, false);

                    } else {
                        toastr.error(response.message || 'Failed to create deposit invoice');
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                    toastr.error(errorMessage);
                }
            });
        });
    </script>
@endpush
