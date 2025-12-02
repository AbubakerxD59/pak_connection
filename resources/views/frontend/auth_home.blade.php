@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <header class="membership-header">
            <h2 class="welcome-title">Welcome to the <strong class="green-color">PAK CONNECTIONS</strong></h2>
            <h3 class="member-name"><span class="green-color">{{ auth()->user()->full_name }}</span></h3>
            <div class="d-flex justify-content-center">
                <div class="card membership-card col-md-6 col-lg-5">
                    <div class="card-body">
                        <h4 class="membershit-title">Current Membership : <br><strong
                                class="green-color">{{ auth()->user()->getPackage() ? auth()->user()->getPackage()->name : '-' }}</strong>
                        </h4>
                        <h6>Expiry Date: <span
                                class="green-color font-bold">{{ date('Y-m-d', strtotime(auth()->user()->pkg_end_time)) }}</span>
                        </h6>
                    </div>
                </div>
            </div>
        </header>
        <div class="customize-button text-center pt-4">
            <a class="btn btn-primary mt-3" id="order-service-btn" href="{{ route('frontend.member.home') }}">Order a
                Service</a>
            <a class="btn btn-support mt-3" href="tel:+923205023407">
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

        #image-preview-container {
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
            const imagePreview = $('#image-preview');
            const imagePreviewContainer = $('#image-preview-container');
            const unverifiedContent = $('#unverified-content');
            const pendingContent = $('#pending-content');
            const submitBtn = $('#submit-verification-btn');

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
                        } else if (response.is_pending) {
                            // Show pending message
                            unverifiedContent.hide();
                            pendingContent.show();
                            verificationModal.modal('show');
                        } else {
                            // Show upload form
                            unverifiedContent.show();
                            pendingContent.hide();
                            verificationModal.modal('show');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to check verification status');
                    }
                });
            });

            // Image preview
            passportInput.on('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size (5MB = 5242880 bytes)
                    if (file.size > 5242880) {
                        toastr.error('File size must not exceed 5MB');
                        $(this).val('');
                        imagePreviewContainer.hide();
                        return;
                    }

                    // Check file type
                    const fileType = file.type;
                    if (fileType === 'application/pdf') {
                        imagePreview.attr('src', '{{ asset('assets/img/pdf-icon.png') }}');
                        imagePreviewContainer.show();
                    } else if (fileType.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.attr('src', e.target.result);
                            imagePreviewContainer.show();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        toastr.error('Invalid file type. Please upload an image or PDF file.');
                        $(this).val('');
                        imagePreviewContainer.hide();
                    }
                } else {
                    imagePreviewContainer.hide();
                }
            });

            // Submit verification form
            verificationForm.on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');

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
                            imagePreviewContainer.hide();

                            // Show pending message
                            unverifiedContent.hide();
                            pendingContent.show();
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
        });
    </script>
@endpush
