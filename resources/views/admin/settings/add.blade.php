@extends('admin.layouts.secure')
@section('page_title', 'Add Setting')
@section('page_content')
    @can('add_settings')
        <div class="page-content">
        <div class="content-header clearfix">
            <h1 class="float-left">Add New Setting</h1>
            <div class="float-right">
                <a class="btn btn-secondary" href="{{ route('settings.index') }}">
                    <i class="fas fa-arrow-left"></i> Back to Settings
                </a>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('settings.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <label for="key">Setting Key <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('key') is-invalid @enderror" 
                                               id="key" 
                                               name="key" 
                                               value="{{ old('key') }}" 
                                               placeholder="e.g., company_name, app_version" 
                                               required>
                                        <small class="form-text text-muted">
                                            Use lowercase letters, numbers, and underscores only. Must be unique.
                                        </small>
                                        @error('key')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="value">Setting Value <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('value') is-invalid @enderror" 
                                                  id="value" 
                                                  name="value" 
                                                  rows="4" 
                                                  placeholder="Enter the setting value" 
                                                  required>{{ old('value') }}</textarea>
                                        @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Create Setting
                                        </button>
                                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @endcan
@endsection

