@extends('admin.layouts.secure')
@section('page_title', 'Edit Feature')
@section('page_content')
    @can('edit_feature')
        <div class="page-content">
            <form method="POST" action="{{ route('features.update', $feature->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit Feature
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('features.index') }}">back to Packages list</a>
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
                                                <label for="name" class="form-label">Name</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="{{ old('name', $feature->name) }}" placeholder="Enter package name"
                                                    required>
                                                @error('name')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="icon" class="form-label">Icon</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="icon" id="icon" class="form-control">
                                                <img id="iconPreview" class="rounded mt-1" width="150px"
                                                    src="{{ $feature->icon }}">

                                                @error('icon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header with-border clearfix">
                                        <div class="card-title">
                                            <i class="fas fa-star"></i>
                                            Fields
                                        </div>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @can('add_fields')
                                            <div class="content-header clearfix">
                                                <div class="float-right">
                                                    <a class="btn btn-primary" data-toggle="modal" data-target="#add_fields_modal">
                                                        <i class="fas fa-plus-square"></i> Add Field</a>
                                                </div>
                                            </div>
                                        @endcan
                                        <div class="table-list">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <?php
                                                        $selected_fields = [];
                                                        ?>
                                                        <table class="table table-striped table-bordered" id="fields_datatable">
                                                            <thead>
                                                                <th>ID</th>
                                                                <th>Name</th>
                                                                <th>Type</th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($feature->load('fields')->fields as $key => $field)
                                                                    <?php
                                                                    array_push($selected_fields, $field->id);
                                                                    ?>
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ $field->name }}</td>
                                                                        <td>{{ $field->type }}</td>
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
            </form>
        </div>
    @endcan
    @include('admin.features.add_fields_modal', [
        'feature' => $feature,
        'fields' => $fields,
        'selected_fields' => $selected_fields,
    ])
@endsection
@push('scripts')
    <script>
        var photoInput = document.getElementById('icon');
        var photoPreview = document.getElementById('iconPreview');

        photoInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.src = "/images/noimage.png";
            }
        });
    </script>
    <script>
        var fields_datatable = $('#fields_datatable').DataTable({
            "paging": true,
            'iDisplayLength': 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    </script>
    <script>
        $('#fieldForm').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData();
            var feature_id = $('#feature_id').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            var fields = $('.field_id');
            var field_ids = [];
            $.each(fields, function(index, value) {
                if ($(this).is(':checked')) {
                    field_ids.push($(this).val());
                }
            });
            formData.append('_token', token);
            formData.append('feature_id', feature_id);
            formData.append('field_ids', field_ids);

            $.ajax({
                url: "{{ route('features.addField') }}",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        $('#add_fields_modal').modal('toggle');
                        location.reload();
                    } else {
                        toastr.error(response.error);
                    }
                },
                error: function(response) {
                    response = response.responseJSON;
                    $.each(errors, function(index, error) {
                        toastr.error(error);
                    });
                }
            });
        });
    </script>
    <script>
        $('#check_all').on('click', function() {
            if ($(this).prop('checked')) {
                $('.field_id').prop('checked', true);
            } else {
                $('.field_id').prop('checked', false);
            }
        });
    </script>
@endpush
