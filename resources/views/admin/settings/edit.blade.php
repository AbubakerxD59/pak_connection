@extends('admin.layouts.secure')
@section('page_title', 'Edit Setting')
@section('page_content')
    @can('edit_settings')
        <div class="page-content">
        <div class="content-header clearfix">
            <h1 class="float-left">Edit Setting</h1>
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
                                <form action="{{ route('settings.update', $setting->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group">
                                        <label for="key">Setting Key <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('key') is-invalid @enderror" 
                                               id="key" 
                                               name="key" 
                                               value="{{ old('key', $setting->key) }}" 
                                               required>
                                        <small class="form-text text-muted">
                                            Use lowercase letters, numbers, and underscores only.
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
                                                  required>{{ old('value', $setting->value) }}</textarea>
                                        @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <small class="text-muted">
                                            <strong>Setting ID:</strong> {{ $setting->id }}
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Update Setting
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

