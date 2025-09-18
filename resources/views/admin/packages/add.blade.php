@extends('admin.layouts.secure')
@section('page_title', 'Add Package')
@section('page_content')
    @can('add_package')
        <div class="page-content">
            <form method="POST" action="{{ route('packages.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="content-header clearfix">
                    <h1 class="float-left"> Add Package
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
                                                <input type="text" class="form-control" name="name" id="name"
                                                    value="{{ old('name') }}" placeholder="Enter package name" required>
                                                @error('name')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="price" class="form-label">Pricing</label>
                                            </div>
                                            @foreach (get_options('package_prices') as $key => $price)
                                                <div class="input-group col-md-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $price }}(£)</span>
                                                    </div>
                                                    <input type="number" class="form-control"
                                                        name="price[{{ $key }}]">
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="icon" class="form-label">Icon</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="icon" id="icon" class="form-control"
                                                    accept="image/*">
                                                <img id="iconPreview" class="rounded mt-1" width="200px">

                                                @error('icon')
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
                                                    name="personal_assistance">
                                                <label class="custom-control-label" for="personal_assistance"></label>
                                                @error('personal_assistance')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
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
@endpush
