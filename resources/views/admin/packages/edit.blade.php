@extends('admin.layouts.secure')
@section('page_title', 'Edit Package')
@section('page_content')
    @can('edit_package')
        <div class="page-content">
            <form method="POST" action="{{ route('packages.update', $package->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit Package
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('packages.index') }}">back to Packages list</a>
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
                                                <input type="hidden" id="package_id" value="{{ $package->id }}">
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="{{ old('name', $package->name) }}" placeholder="Enter package name"
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
                                                <input type="file" name="icon" id="icon" class="form-control"
                                                    accept="image/*">
                                                <img id="iconPreview" class="rounded mt-1" width="150px"
                                                    src="{{ $package->icon }}">

                                                @error('icon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="price" class="form-label">Pricing</label>
                                            </div>
                                            @foreach (get_options('package_prices') as $key => $price)
                                                @php $packagePrice = $prices->where("type", $key)->first(); @endphp
                                                <div class="input-group col-md-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $price }}(£)</span>
                                                    </div>
                                                    <input type="number" class="form-control" name="price[{{ $key }}]"
                                                        value="{{ @$packagePrice->price }}">
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>
                                                        Active
                                                    </option>
                                                    <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>
                                                        Inactive
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="personal_assistance" class="form-label">Personal Assistance</label>
                                            </div>
                                            <div class="custom-control custom-switch col-md-09">
                                                <input type="hidden" name="personal_assistance" value="off">
                                                <input type="checkbox" class="custom-control-input" id="personal_assistance"
                                                    name="personal_assistance"
                                                    {{ $package->personal_assistance ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="personal_assistance"></label>
                                                @error('personal_assistance')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Services --}}
                                    <div class="card">
                                        <div class="card-header with-border clearfix">
                                            <div class="card-title">
                                                <i class="fas fa-star"></i>
                                                Services
                                            </div>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                    title="Collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @can('add_feature')
                                                <div class="content-header clearfix">
                                                    <div class="float-right">
                                                        <a class="btn btn-primary" data-toggle="modal"
                                                            data-target="#add_features_modal">
                                                            <i class="fas fa-plus-square"></i> Add Service</a>
                                                    </div>
                                                </div>
                                            @endcan
                                            <div class="table-list">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <?php
                                                            $selected_features = [];
                                                            ?>
                                                            <table class="table table-striped table-bordered"
                                                                id="features_dataTable">
                                                                <thead>
                                                                    <th>ID</th>
                                                                    <th>Name</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($package->load('features')->features as $key => $feature)
                                                                        <?php
                                                                        array_push($selected_features, $feature->id);
                                                                        ?>
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $feature->name }}</td>
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
                                                                        {{-- <th>ID</th> --}}
                                                                        <th>Member ID</th>
                                                                        <th>Customer</th>
                                                                        <th>Service</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </thead>
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
            </form>
        </div>
    @endcan
    @include('admin.packages.add_features_modal', [
        'features' => $features,
        'package' => $package,
        'selected_features' => $selected_features,
    ])
    @include('admin.packages.view_booked_service')
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
        var features_dataTable = $('#features_dataTable').DataTable({
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
        $('#featureForm').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData();
            var package_id = $('#package_id').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            var features = $('.feature_id');
            var feature_ids = [];
            $.each(features, function(index, value) {
                if ($(this).is(':checked')) {
                    feature_ids.push($(this).val());
                }
            });
            formData.append('_token', token);
            formData.append('package_id', package_id);
            formData.append('feature_ids', feature_ids);
            $.ajax({
                url: "{{ route('packages.add_facility') }}",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        $('#add_features_modal').modal('toggle');
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
                $('.feature_id').prop('checked', true);
            } else {
                $('.feature_id').prop('checked', false);
            }
        });
    </script>
    @include('admin.booked-services.js.script')
@endpush
