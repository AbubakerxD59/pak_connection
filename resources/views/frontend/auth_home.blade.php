@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2 class="welcome-title">Welcome to the <strong class="green-color">PAK CONNECTIONS</strong></h2>
            <h3 class="member-name">
                <span class="green-color">{{ auth()->user()->full_name }}</span>
            </h3>
            <div class="d-flex justify-content-center">
                <div class="card membership-card col-md-6 col-lg-5">
                    <div class="card-body">
                        <h4 class="membershit-title">Current Membership : <br><strong
                                class="green-color">{{ auth()->user()->getPackage() ? auth()->user()->getPackage()->name : '-' }}</strong>
                        </h4>
                        <h6>Expiry Date: <span
                                class="green-color font-bold">{{ \Carbon\Carbon::parse(auth()->user()->pkg_end_time)->format(setting('date_format', 'Y-m-d')) }}</span>
                        </h6>
                    </div>
                </div>
            </div>
        </header>
        <div class="customize-button text-center pt-4">
            <a class="btn btn-primary mt-3" id="order-service-btn" href="{{ route('frontend.member.home') }}">Order a
                Service</a>
            <a class="btn btn-support mt-3" href="tel:{{ setting('support_phone') }}">
                <span><img src="/assets/img/headphone.png" alt="Head Phone"></span>
                Contact Support
            </a>
        </div>
    </section>

    @include('frontend.modal.verification')
@endsection

@push('styles')
    <style>
        #verificationModal .modal-content {
            border-radius: 10px;
        }

        #verificationModal .modal-header {
            background-color: #28a745;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        #verificationModal .modal-header .close {
            color: white;
            opacity: 1;
        }

        #passport-image-preview-container,
        #passport-pdf-preview-container,
        #address-image-preview-container,
        #address-pdf-preview-container {
            margin-top: 15px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            const orderServiceBtn = $('#order-service-btn');
            const verificationModal = $('#verificationModal');
            const verificationForm = $('#verification-form');
            const passportInput = $('#passport_image');
            const proofOfAddressInput = $('#proof_of_address');
            const passportImagePreview = $('#passport-image-preview');
            const passportImagePreviewContainer = $('#passport-image-preview-container');
            const passportPdfPreview = $('#passport-pdf-preview');
            const passportPdfPreviewContainer = $('#passport-pdf-preview-container');
            const addressImagePreview = $('#address-image-preview');
            const addressImagePreviewContainer = $('#address-image-preview-container');
            const addressPdfPreview = $('#address-pdf-preview');
            const addressPdfPreviewContainer = $('#address-pdf-preview-container');
            const unverifiedContent = $('#unverified-content');
            const pendingContent = $('#pending-content');
            const submitBtn = $('#submit-verification-btn');

            // Function to update modal based on document statuses
            function updateModalWithDocumentStatuses(documents) {
                // Handle Passport
                const passportStatus = documents.passport.status;
                const passportApproved = documents.passport.is_approved;
                const passportPending = documents.passport.is_pending;
                const passportRejected = documents.passport.is_rejected;

                if (passportApproved) {
                    $('#passport-verified-status').show();
                    $('#passport-pending-status').hide();
                    $('#passport-rejected-status').hide();
                    $('#passport-upload-section').hide();
                    $('#passport-required').hide();
                    $('#passport_image').removeAttr('required');
                } else if (passportPending) {
                    $('#passport-verified-status').hide();
                    $('#passport-pending-status').show();
                    $('#passport-rejected-status').hide();
                    $('#passport-upload-section').hide();
                    $('#passport-required').hide();
                    $('#passport_image').removeAttr('required');
                } else if (passportRejected) {
                    $('#passport-verified-status').hide();
                    $('#passport-pending-status').hide();
                    $('#passport-rejected-status').show();
                    $('#passport-upload-section').show();
                    $('#passport-required').show();
                    $('#passport_image').attr('required', 'required');
                } else {
                    // Unverified
                    $('#passport-verified-status').hide();
                    $('#passport-pending-status').hide();
                    $('#passport-rejected-status').hide();
                    $('#passport-upload-section').show();
                    $('#passport-required').show();
                    $('#passport_image').attr('required', 'required');
                }

                // Handle Proof of Address
                const addressStatus = documents.proof_of_address.status;
                const addressApproved = documents.proof_of_address.is_approved;
                const addressPending = documents.proof_of_address.is_pending;
                const addressRejected = documents.proof_of_address.is_rejected;

                if (addressApproved) {
                    $('#address-verified-status').show();
                    $('#address-pending-status').hide();
                    $('#address-rejected-status').hide();
                    $('#address-upload-section').hide();
                    $('#address-required').hide();
                    $('#proof_of_address').removeAttr('required');
                } else if (addressPending) {
                    $('#address-verified-status').hide();
                    $('#address-pending-status').show();
                    $('#address-rejected-status').hide();
                    $('#address-upload-section').hide();
                    $('#address-required').hide();
                    $('#proof_of_address').removeAttr('required');
                } else if (addressRejected) {
                    $('#address-verified-status').hide();
                    $('#address-pending-status').hide();
                    $('#address-rejected-status').show();
                    $('#address-upload-section').show();
                    $('#address-required').show();
                    $('#proof_of_address').attr('required', 'required');
                } else {
                    // Unverified
                    $('#address-verified-status').hide();
                    $('#address-pending-status').hide();
                    $('#address-rejected-status').hide();
                    $('#address-upload-section').show();
                    $('#address-required').show();
                    $('#proof_of_address').attr('required', 'required');
                }
            }

            // Check verification status and handle Order a Service button click
            orderServiceBtn.on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('frontend.member.verification.status') }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.is_verified) {
                            // User is verified, proceed to order service
                            window.location.href = '{{ route('frontend.member.home') }}';
                        } else {
                            // Show upload form with document statuses
                            unverifiedContent.show();
                            pendingContent.hide();

                            // Update modal with document statuses
                            if (response.documents) {
                                updateModalWithDocumentStatuses(response.documents);
                            }

                            verificationModal.modal('show');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to check verification status');
                    }
                });
            });

            // Function to handle file preview
            function handleFilePreview(file, imagePreview, imagePreviewContainer, pdfPreview,
                pdfPreviewContainer, errorMessage) {
                if (file) {
                    // Check file size (5MB = 5242880 bytes)
                    if (file.size > 5242880) {
                        toastr.error(errorMessage + ' - File size must not exceed 5MB');
                        return false;
                    }

                    // Check file type
                    const fileType = file.type;
                    if (fileType === 'application/pdf') {
                        // Show PDF preview
                        const fileURL = URL.createObjectURL(file);
                        pdfPreview.attr('src', fileURL);
                        pdfPreviewContainer.show();
                        imagePreviewContainer.hide();
                        return true;
                    } else if (fileType.startsWith('image/')) {
                        // Show image preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.attr('src', e.target.result);
                            imagePreviewContainer.show();
                            pdfPreviewContainer.hide();
                        };
                        reader.readAsDataURL(file);
                        return true;
                    } else {
                        toastr.error(errorMessage +
                            ' - Invalid file type. Please upload an image or PDF file.');
                        return false;
                    }
                } else {
                    imagePreviewContainer.hide();
                    pdfPreviewContainer.hide();
                    return false;
                }
            }

            // Passport document preview (Image or PDF)
            passportInput.on('change', function() {
                const file = this.files[0];
                if (!handleFilePreview(file, passportImagePreview, passportImagePreviewContainer,
                        passportPdfPreview, passportPdfPreviewContainer, 'Passport')) {
                    $(this).val('');
                }
            });

            // Proof of Address document preview (Image or PDF)
            proofOfAddressInput.on('change', function() {
                const file = this.files[0];
                if (!handleFilePreview(file, addressImagePreview, addressImagePreviewContainer,
                        addressPdfPreview, addressPdfPreviewContainer, 'Proof of Address')) {
                    $(this).val('');
                }
            });

            // Load document statuses when modal is shown
            verificationModal.on('show.bs.modal', function() {
                $.ajax({
                    url: '{{ route('frontend.member.verification.status') }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.is_verified) {
                            // User is fully verified, don't show upload form
                            unverifiedContent.hide();
                            pendingContent.hide();
                        } else if (response.documents) {
                            // Update modal with document statuses
                            updateModalWithDocumentStatuses(response.documents);

                            // Check if both documents are pending
                            const bothPending = response.documents.passport.is_pending &&
                                response.documents.proof_of_address.is_pending;
                            if (bothPending && !response.documents.passport.is_approved && !
                                response.documents.proof_of_address.is_approved) {
                                unverifiedContent.hide();
                                pendingContent.show();
                            } else {
                                unverifiedContent.show();
                                pendingContent.hide();
                            }
                        }
                    },
                    error: function() {
                        // On error, just show the form
                        unverifiedContent.show();
                        pendingContent.hide();
                    }
                });
            });

            // Submit verification form
            verificationForm.on('submit', function(e) {
                e.preventDefault();

                // Client-side validation - check if at least one file is provided
                const passportFile = passportInput[0].files.length > 0;
                const addressFile = proofOfAddressInput[0].files.length > 0;
                const passportRequired = passportInput.attr('required') !== undefined;
                const addressRequired = proofOfAddressInput.attr('required') !== undefined;

                if (passportRequired && !passportFile) {
                    toastr.error('Please upload your Passport document.');
                    return;
                }

                if (addressRequired && !addressFile) {
                    toastr.error('Please upload your Proof of Address document.');
                    return;
                }

                if (!passportFile && !addressFile) {
                    toastr.error('Please upload at least one document.');
                    return;
                }

                const formData = new FormData(this);

                submitBtn.prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Uploading...');

                $.ajax({
                    url: '{{ route('frontend.member.verification.upload') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            verificationForm[0].reset();
                            passportImagePreviewContainer.hide();
                            passportPdfPreviewContainer.hide();
                            addressImagePreviewContainer.hide();
                            addressPdfPreviewContainer.hide();

                            // Refresh document statuses to update the modal
                            $.ajax({
                                url: '{{ route('frontend.member.verification.status') }}',
                                type: 'GET',
                                success: function(statusResponse) {
                                    if (statusResponse.is_verified) {
                                        // Both documents verified, close modal or show success
                                        unverifiedContent.hide();
                                        pendingContent.hide();
                                        verificationModal.modal('hide');
                                        toastr.success(
                                            'Your verification is complete!'
                                        );
                                    } else if (statusResponse.documents) {
                                        // Update modal with new document statuses
                                        updateModalWithDocumentStatuses(
                                            statusResponse.documents);

                                        // Check if both documents are now pending
                                        const bothPending = statusResponse
                                            .documents.passport.is_pending &&
                                            statusResponse.documents
                                            .proof_of_address.is_pending;
                                        if (bothPending) {
                                            unverifiedContent.hide();
                                            pendingContent.show();
                                        } else {
                                            unverifiedContent.show();
                                            pendingContent.hide();
                                        }
                                    }
                                },
                                error: function() {
                                    // Fallback to showing pending message
                                    unverifiedContent.hide();
                                    pendingContent.show();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Failed to upload verification document');
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(
                            '<i class="fa fa-upload"></i> Submit Verification');
                    }
                });
            });

            // Read More toggle functionality
            $('#read-more-toggle').on('click', function() {
                $('#additional-info').slideToggle(300);
                $('#read-more-icon').toggleClass('fa-chevron-down fa-chevron-up');
                const isVisible = $('#read-more-icon').hasClass('fa-chevron-up');
                if (isVisible) {
                    $('#read-more-text').text('Read Less');
                } else {
                    $('#read-more-text').text('Read More');
                }
            });
        });
    </script>
@endpush
