@extends('admin.layouts.secure')
@section('page_title', 'Booked Service')
@section('page_content')
    @can('edit_booked_service')
        <div class="page-content">
            <form method="POST" action="{{ route('booked-services.update', $bookedService->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit Service
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ url()->previous() }}">back </a>
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
                                                {!! service_book_status($bookedService->status) !!}
                                            </div>
                                        </div>

                                        <div class="col-12 text-right">
                                            <button type="submit"
                                                class="btn btn-outline-success">{{ __('users.btn_submit_text') }}</button>
                                            <a href="{{ route('users.edit', $bookedService->user_id) }}"
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



    {{-- Transactions --}}
    @can('edit_booked_service')
        <div class="page-content">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        Transactions
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
                                                        id="transactions_dataTable">
                                                        <thead>

                                                            <th>Service</th>
                                                            <th>Total Amount</th>
                                                            <th>Discount Amount</th>
                                                            <th>Payable Amount</th>
                                                            <th>Status</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($bookedService->getTransactions() as $transaction)
                                                                <tr>
                                                                    <td>{{ $bookedService->name }}</td>
                                                                    <td>£
                                                                        {{ number_format($transaction->total_amount, 2) }}
                                                                    </td>
                                                                    <td>£
                                                                        {{ number_format($transaction->discount_amount, 2) }}
                                                                    </td>
                                                                    <td>£
                                                                        {{ number_format($transaction->payable_amount, 2) }}
                                                                    </td>

                                                                    <td>{!! get_status_view($transaction->status) !!}</td>
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
                        </div>
                    </div>
                </div>
            </section>
        </div>



    @endcan

    {{-- Book Service PDF --}}
    @can('edit_booked_service')
        <div class="page-content">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="nav-icon fas fa-money-bill"></i>
                                        Book Service PDF
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#uploadPdfModal" id="openUploadModalBtn">
                                            Add New
                                        </button>

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
                                                        id="services_pdf_dataTable">
                                                        <thead>

                                                            <th>Subject</th>
                                                            <th>Text</th>
                                                            <th>Action</th>

                                                        </thead>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endcan

    @include('admin.booked-services.generate_invoice_modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var transactions_dataTable = $('#transactions_dataTable').DataTable({
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
        $(document).ready(function() {
            let clickedBtn = null;

            // Handle modal open and reset form
            $(document).on('click', '.generate-invoice-btn', function() {
                const serviceId = $(this).data('id');
                clickedBtn = $(this); // track the clicked button



                // Reset form fields
                $('#modalBookedServiceId').val(serviceId);
                $('#amount').val('');
                $('#final_price').val('');
                $('#promo_code_id').prop('selectedIndex', 0);
            });

            // Calculate final price based on amount and selected coupon
            function calculateFinalPrice() {
                const amount = parseFloat($('#amount').val());
                const selectedOption = $('#promo_code_id option:selected');
                const discountType = selectedOption.data('discount-type');
                const discountAmount = parseFloat(selectedOption.data('discount-amount'));

                if (!amount || isNaN(amount)) {
                    $('#final_price').val('');
                    return;
                }

                let final = amount;

                if (discountType === 'percent') {
                    final -= (discountAmount / 100) * amount;
                } else if (discountType === 'fixed') {
                    final -= discountAmount;
                }

                final = Math.max(final, 0); // prevent negative values
                $('#final_price').val(final.toFixed(2));
            }

            // Bind events
            $('#amount').on('input', calculateFinalPrice);
            $('#promo_code_id').on('change', calculateFinalPrice);

            // Handle form submission
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();

                // let submitBtn = $('#submitBtn'); // Adjust selector as needed
                // let form = $('#invoiceForm'); // Adjust selector as needed

                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');

                // Disable both buttons
                if (clickedBtn) clickedBtn.prop('disabled', true).text('Processing...');
                submitBtn.prop('disabled', true).text('Generating...');

                // Show toast message
                toastr.info(
                    "Submitting your invoice request. Please wait while we process your details...");

                const data = {
                    _token: form.find('input[name="_token"]').val(),
                    book_service_id: $('#modalBookedServiceId').val(),
                    amount: $('#amount').val(),
                    final_price: $('#final_price').val(),
                    promo_code_id: $('#promo_code_id').val(),
                };



                $.ajax({
                    type: 'POST',
                    url: "{{ route('booked-services.createInvoice') }}",
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'An error occurred.';
                        toastr.error(errorMsg);
                    },
                    complete: function() {
                        $('#invoiceModal').modal('hide');
                        form[0].reset();

                        // Re-enable buttons
                        if (clickedBtn) {
                            clickedBtn.prop('disabled', false).text('Generate Invoice');
                            clickedBtn = null;
                        }
                        submitBtn.prop('disabled', false).text('Generate');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                });
            });
        });
    </script>






    <script>
        $(document).ready(function() {
            $(document).on('click', '.deposit-payment-btn', function() {

                toastr.info(
                    "Please wait while we generate your deposit invoice. This may take a few seconds..."
                );


                let $button = $(this);
                $button.prop('disabled', true); // Disable the button

                // Check if it is disabled
                console.log('Button disabled:', $button.prop('disabled')); // should log `true`
                $button.text('Processing...');


                let bookedServiceId = $button.data('id');

                $.ajax({
                    url: "{{ route('booked-services.requestDeposit') }}", // Your actual route
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        book_service_id: bookedServiceId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);

                            // Option 1: Reload the DataTable (only if using AJAX source)
                            // booked_services_dataTable.ajax.reload();

                            // Option 2: Full page reload
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(response.message ||
                                'Failed to create deposit invoice');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        $button.prop('disabled', false); // Re-enable the button
                        $button.prop('disabled', false).text('Deposit Payment');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);

                    }
                });
            });
        });
    </script>


    <script>
        function handleStatusAction(button, routeUrl, defaultText = 'Processing...') {
            const $button_for_status = $(button);
            const bookedServiceId = $button_for_status.data('id');
            const currentStatus = $button_for_status.data('status');
            const currentStatusText = $button_for_status.data('status-text');
            toastr.info(`Please wait while we process your request: ${currentStatusText}`);
            $button_for_status.prop('disabled', true).text(defaultText);
            $.ajax({
                url: "{{ route('booked-services.uploadSchedule') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    book_service_id: bookedServiceId,
                    status: currentStatus,
                    status_text: currentStatusText
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);

                    } else {
                        toastr.error(response.message || 'Something went wrong.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                    toastr.error(errorMessage);
                },
                complete: function() {
                    $button_for_status.prop('disabled', false).text(currentStatusText);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            });
        }


        $(document).ready(function() {
            $(document).on('click', '.update-next-status', function() {
                handleStatusAction(this, "{{ route('booked-services.requestDeposit') }}", 'Processing...');
            });
        });


        // status schedule : modal script
        $(document).ready(function() {
            let selectedButton = null;

            $(document).on('click', '.update-schedule-status', function() {
                selectedButton = $(this);
                $('#book_service_id').val(selectedButton.data('id'));
                $('#status').val(selectedButton.data('status'));
                $('#status_text').val(selectedButton.data('status-text'));
            });

            $('#statusUpdateForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Uploading...');


                // const formData = new FormData(this);

                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }

                // return true;

                $.ajax({
                    url: "{{ route('booked-services.uploadSchedule') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#statusUpdateModal').modal('hide');
                        } else {
                            toastr.error(response.message || 'Something went wrong.');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Submit');
                        setTimeout(() => location.reload(), 1000);
                    }
                });
            });

            ///////////////////////////////////////////////////////////////////////////
            ///////////////////// PDF Book Service Code ///////////////////////////////
            ///////////////////////////////////////////////////////////////////////////

            $('#uploadPdfForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var $btn = $('#uploadPdfBtn');
                var originalText = $btn.html();

                $btn.prop('disabled', true).html('Uploading...');

                $.ajax({
                    url: "{{ route('booked-services.upload-pdfs') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        toastr.success('PDF uploaded successfully.');
                        $('#uploadPdfModal').modal('toggle');
                        $('#uploadPdfForm')[0].reset();
                        $btn.prop('disabled', false).html(originalText);

                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');

                        services_pdf_dataTable.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong.');
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $(document).on('click', '.edit-pdf-btn', function() {
                $('#pdf_id').val($(this).data('pdf_id'));
                // $('#booked_service_id').val($(this).data('book_service_id'));
                $('#subject').val($(this).data('subject'));
                $('#text').val($(this).data('text'));
                $('#uploadPdfModal').modal('show');


            });

            $(document).ready(function() {
                $('#openUploadModalBtn').on('click', function() {

                    $('#pdf_id').val(''); // clear the hidden input

                    let pdfId = $('#pdf_id').val();

                    if (!pdfId) {
                        // No pdf_id means creating new → make file required
                        $('#pdf_file').attr('required', true);
                    } else {
                        // Editing existing → make file optional
                        $('#pdf_file').removeAttr('required');
                    }

                    $('#uploadPdfForm')[0].reset(); // optional: reset the full form
                });
            });

            $(document).on('click', '.view-pdf-btn', function() {
                const subject = $(this).data('subject');
                const text = $(this).data('text');
                const file = $(this).data('file');
                const get_pdf_id = $(this).data('pdf_id');


                $('#pdfIdView').val(get_pdf_id);
                $('#pdfSubjectView').text(subject);
                $('#pdfTextView').text(text);
                $('#pdfDownloadLink').attr('href', file); // set the PDF URL

                $('#viewPdfModal').modal('show');
            });


            $('#viewPdfForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var $btn = $('#uploadPdfBtn');
                var originalText = $btn.html();

                $btn.prop('disabled', true).html('Uploading...');

                $.ajax({
                    url: "{{ route('booked-services.send-pdf-email') }}", // update this route accordingly
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        toastr.success('Email sent successfully.');

                        // ✅ Hide modal and reset form
                        $('#viewPdfModal').modal('hide');
                        $('#viewPdfForm')[0].reset();
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalText);

                        // ✅ Fix overlay issue and mouse events
                        setTimeout(function() {
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }, 500);
                    }
                });
            });



        });
    </script>

    <script>
        $('#viewPdfModal').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        });
    </script>

    <script>
        $('#uploadPdfModal').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        });
    </script>



    <script>
        $('#statusUpdateModal').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        });
    </script>

    <script>
        $('#invoiceModal').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('padding-right', '');
        });
    </script>
@endpush

@push('scripts')
    @include('admin.booked-services.js.pdfscript')
@endpush
