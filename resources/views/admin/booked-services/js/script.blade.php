<script>
    var booked_services_dataTable = '';
    $(document).ready(function() {
        // datatable
        booked_services_dataTable = $('#booked_services_dataTable').DataTable({
            "paging": true,
            'iDisplayLength': 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ route('booked-services.dataTable') }}",
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'customer_name'
                },
                {
                    data: 'membership_id'
                },
                {
                    data: 'service'
                },
                {
                    data: 'status_view'
                },
                {
                    data: 'action'
                }
            ],
        });
        // view details
        $(document).on('click', '.view_booked_service', function() {
            var service_id = $(this).data('id');
            $.ajax({
                url: "{{ route('booked-services.view') }}",
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
        });
        let clickedBtn = null;
        // Handle modal open and reset form
        $(document).on('click', '.generate-invoice-btn', function() {
            const serviceId = $(this).data('id');
            clickedBtn = $(this); // track the clicked button

            console.log('called 1');

            // Reset form fields
            $('#modalBookedServiceId').val(serviceId);
            $('#amount').val('');
            $('#final_price').val('');
            $('#promo_code_id').prop('selectedIndex', 0);
            // $("#invoiceModal").modal("show");
            $("#invoiceModal").modal("toggle");

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
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            // Disable both buttons
            if (clickedBtn) clickedBtn.prop('disabled', true).text('Processing...');
            submitBtn.prop('disabled', true).text('Generating...');
            // Show toast message
            toastr.info("Submitting your invoice request. Please wait while we process your details...");
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
                        booked_services_dataTable.ajax.reload();
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
                    // $('#invoiceModal').modal('hide');

                    form[0].reset();

                    // Re-enable buttons
                    if (clickedBtn) {
                        clickedBtn.prop('disabled', false).text('Generate Invoice');
                        clickedBtn = null;
                    }
                    submitBtn.prop('disabled', false).text('Generate');
                    // setTimeout(() => {
                    //     location.reload();
                    // }, 1000);
                    $("#invoiceModal").modal("toggle");

                }
            });
        });
        $(document).on('click', '.deposit-payment-btn', function() {
            toastr.info( "Please wait while we generate your deposit invoice. This may take a few seconds...");
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
                        booked_services_dataTable.ajax.reload();
                        toastr.success(response.message);
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
                }
            });
        });

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
                        booked_services_dataTable.ajax.reload();
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
                }
            });
        }
        $(document).on('click', '.update-next-status', function() {
            handleStatusAction(this, "{{ route('booked-services.requestDeposit') }}", 'Processing...');
        });
        let selectedButton = null;
        $(document).on('click', '.update-schedule-status', function() {
            selectedButton = $(this);
            $('#book_service_id').val(selectedButton.data('id'));
            $('#status').val(selectedButton.data('status'));
            $('#status_text').val(selectedButton.data('status-text'));

            // console.log('before');
            $('#statusUpdateModal').modal('toggle');
            // return true;
            // console.log('after');
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
                        booked_services_dataTable.ajax.reload();
                        toastr.success(response.message);
                        // $('#statusUpdateModal').modal('hide');
                        $('#statusUpdateModal').modal('toggle');
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
                    // setTimeout(() => location.reload(), 1000);
                }
            });
        });
    });
</script>