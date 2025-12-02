@extends('admin.layouts.secure')
@section('page_title', 'Settings')
@section('page_content')
    @can('view_settings')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Settings Management</h1>
                <div class="float-right">
                    @can('add_settings')
                        <a class="btn btn-primary" href="{{ route('settings.create') }}">
                            <i class="fas fa-plus-square"></i> Add New Setting
                        </a>
                    @endcan
                </div>
            </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-list">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="dataTable">
                                                    <thead>
                                                        <th>ID</th>
                                                        <th>Key</th>
                                                        <th>Value</th>
                                                        <th>Action</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                        </tr>
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
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Server side dataTable
            $('#dataTable').DataTable({
                "paging": true,
                'iDisplayLength': 10,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{ route('settings.dataTable') }}",
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'key'
                    },
                    {
                        data: 'value'
                    },
                    {
                        data: 'action'
                    }
                ],
            });

            // Delete setting
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    $('#dataTable').DataTable().ajax.reload();
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete setting.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
    @endcan
