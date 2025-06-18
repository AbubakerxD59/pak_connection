@extends('admin.layouts.secure')
@section('page_title', $user->full_name)
@section('page_content')
    @can('edit_user')
        <div class="page-content">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit User
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('users.index') }}">back to Users list</a>
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
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="full_name" class="form-label">Full Name</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="full_name" id="full_name"
                                                    value="{{ old('full_name', $user->full_name) }}"
                                                    placeholder="Enter user name" required>
                                                @error('full_name')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="email" class="form-label">Email</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="{{ old('email', $user->email) }}" placeholder="Enter user email">
                                                @error('email')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="password" class="form-label">Password</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" name="password" id="password">
                                                @error('password')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <?php
                                        $roleName = !empty($user->roles()->first()) ? $user->roles()->first()->id : '0';
                                        ?>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="role" class="form-label">Role</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="role" id="role" class="form-control">
                                                    <option value="">Select Role</option>
                                                    @if (count($roles) > 0)
                                                        @foreach ($roles as $role)
                                                            @if (!in_array($role->name, ['Super Admin']))
                                                                <option value="{{ $role->id }}"
                                                                    {{ old('role', $roleName) == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="active" class="form-label">Is Active</label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-check">
                                                    <input type="hidden" name="active" value="0">
                                                    <input type="checkbox" id="activeCheckbox" name="active"
                                                        class="form-check-input" value="1"
                                                        {{ $user->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="active">Yes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 text-right">
                                            <button type="submit"
                                                class="btn btn-outline-success">{{ __('users.btn_submit_text') }}</button>
                                            <a href="{{ route('users.index') }}"
                                                class="btn btn-outline-dark">{{ __('users.btn_cancel_text') }}</a>
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
                                                                    <th>ID</th>
                                                                    <th>User</th>
                                                                    <th>Service</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($user->load('bookServices')->bookServices as $key => $service)
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $service->user->full_name }}</td>
                                                                            <td>{{ $service->service->name }}</td>
                                                                            <td>{!! service_book_status($service->status) !!}</td>
                                                                            <td>
                                                                                <div class="row">
                                                                                    <div>
                                                                                        <span
                                                                                            class="btn btn-outline-success btn-sm view_booked_service"
                                                                                            data-id="{{ $service->id }}">View</span>
                                                                                    </div>
                                                                                    @can('edit_booked_services')
                                                                                        <div>
                                                                                            <a href="{{ route('users.booked_service.edit', $service->id) }}"
                                                                                                class="btn btn-outline-primary btn-sm mx-1">Edit</a>
                                                                                        </div>
                                                                                    @endcan
                                                                                    @can('delete_booked_services')
                                                                                        <div>
                                                                                            <a href="{{ route('users.booked_service.delete', $service->id) }}"
                                                                                                class="btn btn-outline-danger btn-sm mx-1">Delete</a>
                                                                                        </div>
                                                                                    @endcan
                                                                                </div>
                                                                            </td>
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
                                @endcan
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    @endcan
@endsection
@include('admin.packages.view_booked_service')
@push('scripts')
    <script>
        $(document).ready(function() {
            var booked_services_dataTable = $('#booked_services_dataTable').DataTable({
                "paging": true,
                'iDisplayLength': 10,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script>
        $(document).on('click', '.view_booked_service', function() {
            var service_id = $(this).data('id');
            $.ajax({
                url: "{{ route('packages.show_booked_service') }}",
                type: "GET",
                data: {
                    "id": service_id
                },
                success: function(response) {
                    if (response.status) {
                        $("#view_booked_service").find(".modal-body").html(response.body);
                        $("#view_booked_service").find(".modal-title").html(response.title);
                        $("#view_booked_service").modal('toggle');
                    } else {
                        toastr.error(response.error);
                    }
                }
            });
        })
    </script>
@endpush
