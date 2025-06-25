@extends('frontend.main')
@section('body')
    <section class="membership-portal container">
        <p class="text-center">
            <span class="h4">Package:</span>
            <span class="font-weight-bold h4">{{ $package->name }}</span>
        </p>
        <div class="row">
            @foreach ($features as $feature)
                <div class="col-md-4 p-3 text-center">
                    <div class="pointer feature_fields" data-id="{{ $feature->id }}" data-name="{{ $feature->name }}">
                        <img src="{{ $feature->icon }}" alt="" width="200px" class="rounded" data-toggle="modal"
                            data-target="#feature_fields">
                    </div>
                    <div class="my-3">
                        <span>
                            <strong class="feature_name">{{ strtoupper($feature->name) }}</strong>
                            <br>
                            {!! service_book_status($feature->book) !!}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @include('frontend.member.modals.feature_fields', ['package' => $package])
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
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            toastr.error(response.message);
                        }
                    }
                })
            });
        });
    </script>
@endpush
