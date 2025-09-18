@extends('admin.layouts.secure')
@section('page_title', 'Add Service')
@section('page_content')
    @can('add_feature')
        <div class="page-content">
            <form method="POST" action="{{ route('features.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="content-header clearfix">
                    <h1 class="float-left"> Add Service
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('features.index') }}">back to Services list</a>
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
                                                <label for="icon" class="form-label">Icon</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="icon" id="icon" class="form-control">
                                                <img class="iconPreview">

                                                @error('icon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="price" class="form-label">Price</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="price" id="price"
                                                    value="{{ old('price') }}" placeholder="Enter package price" required>
                                                @error('price')
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
@endpush
