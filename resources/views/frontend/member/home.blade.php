@extends('frontend.main')
@section('body')
    <section class="membership-portal container">

        @if ($isPackageExpired)
            <div class="alert alert-danger text-center font-weight-bold d-flex align-items-center justify-content-between">
                Your package has expired. Please renew it to continue accessing all services.
                <br>
                <a href="{{ route('frontend.home') }}" class="btn btn-sm btn-primary mt-2">
                    Get Package
                </a>
            </div>
        @endif

        <p class="text-center">
            <span class="h4">Package:</span>
            @if (!empty($package))
                <span class="font-weight-bold h4">{{ $package->name ?? '-' }}</span>
            @else
                <span class="font-weight-bold h4">No package found</span>
            @endif
        </p>
        <div class="row px-5 mx-5">
            @foreach ($features as $feature)
                <div class="col-md-4 p-3 text-center">
                    
                    <div class="pointer feature_fields bg-inafo" data-id="{{ $feature->id }}"
                        data-name="{{ $feature->name }}">
                        <img src="{{ $feature->icon }}" alt=""
                            style="width: 200px; height: 200px; object-fit: fill; border-radius: 5px;" data-toggle="modal"
                            data-target="#feature_fields">
                    </div>

                    <div class="my-3">
                        <span>
                            <strong class="feature_name">{{ strtoupper($feature->name) }}</strong>
                            <br>
                            @if ($feature->book)
                                {!! service_book_status($feature->book) !!}
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- @include('frontend.member.modals.feature_fields', ['package' => $package ?? null]) --}}
    @if (!empty($package))
        @include('frontend.member.modals.feature_fields', ['package' => $package])
    @else
        <span class="text-muted text-center">No package data available</span>
    @endif
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Show fields in modal
            $(document).on('click', '.feature_fields', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var modal = $('#feature_fields');
                modal.find('#service_id').val(id);
                modal.find('.modal-title').html(name);
                $.ajax({
                    url: "{{ route('frontend.member.getFields') }}",
                    method: "GET",
                    data: {
                        "feature_id": id
                    },
                    success: function(response) {
                        if (response.success) {
                            modal.find('.card-body').html(response.data);
                            if (response.book) {
                                modal.find('#saveBtn').attr("disabled", true);
                            } else {
                                modal.find('#saveBtn').attr("disabled", false);
                            }
                        } else {
                            toastr.error(response.message);
                        }
                    }
                })
            });
            // Submit feature form


            $(document).on('submit', '#submitFeatureForm', function(event) {
                event.preventDefault();
                var form = $("#submitFeatureForm");
                var modal = $('#feature_fields');
                var formData = new FormData(this);
                var submitBtn = $('#saveBtn');

                submitBtn.prop('disabled', true).text('Sending...');

                toastr.info("We’re processing your request. Please wait a moment...");


                $.ajax({
                    url: "{{ route('frontend.member.bookService') }}",
                    method: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response) {

                        if (response.success) {
                            modal.modal('toggle');
                            modal.find('.card-body').empty();

                            // ✅ Show success alert
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // 🔵 NEW: Show success toast with response message
                            toastr.success(response.message);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // 🔵 NEW: Show error toast with response message
                            toastr.error(response.message);
                        }

                        submitBtn.prop('disabled', false).text('Send');
                    },
                    error: function(xhr) {
                        // 🔵 NEW: Handle general errors
                        submitBtn.prop('disabled', false).text('Send');
                        toastr.error("Something went wrong. Please try again.");
                    }
                });
            });

        });
    </script>
@endpush
