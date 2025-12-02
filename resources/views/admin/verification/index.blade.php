@extends('admin.layouts.secure')
@section('page_title', 'Member Verification')
@section('page_content')
    @can('view_verification')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Member Verification Documents</h1>
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
                                                        <th>Member Details</th>
                                                        <th>Document Type</th>
                                                        <th>Status</th>
                                                        <th>Submitted Date</th>
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

    @include('admin.verification.modals.reject')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // DataTable
            var table = $('#dataTable').DataTable({
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
                    url: "{{ route('verification.dataTable') }}",
                },
                columns: [{
                        data: 'user_details'
                    },
                    {
                        data: 'document'
                    },
                    {
                        data: 'status_badge'
                    },
                    {
                        data: 'created_date'
                    },
                    {
                        data: 'action'
                    }
                ],
            });

            // View Document
            $(document).on('click', '.view-document', function() {
                var documentId = $(this).data('id');

                $.ajax({
                    url: '/verification/document/' + documentId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var doc = response.document;
                            $('#doc-user-name').text(doc.user_name);
                            $('#doc-user-email').text(doc.user_email);
                            $('#doc-membership-id').text(doc.membership_id);
                            $('#doc-type').text(doc.document_type);
                            $('#doc-status').text(doc.status);
                            $('#doc-created-at').text(doc.created_at);
                            $('#doc-download-link').attr('href', doc.document_url);

                            // Preview document
                            var extension = doc.document_url.split('.').pop().toLowerCase();
                            if (extension === 'pdf') {
                                $('#doc-preview').html('<embed src="' + doc.document_url +
                                    '" type="application/pdf" width="100%" height="500px" />'
                                );
                            } else {
                                $('#doc-preview').html('<img src="' + doc.document_url +
                                    '" alt="Document" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">'
                                );
                            }

                            $('#viewDocumentModal').modal('show');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to load document');
                    }
                });
            });

            // Approve Document
            $(document).on('click', '.approve-document', function() {
                var documentId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to approve this verification document?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/verification/approve/' + documentId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    table.ajax.reload();
                                }
                            },
                            error: function() {
                                toastr.error('Failed to approve document');
                            }
                        });
                    }
                });
            });

            // Show Reject Modal
            var currentRejectId = null;
            $(document).on('click', '.reject-document', function() {
                currentRejectId = $(this).data('id');
                $('#admin_notes').val('');
                $('#rejectModal').modal('show');
            });

            // Reject Document
            $('#reject-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/verification/reject/' + currentRejectId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        admin_notes: $('#admin_notes').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#rejectModal').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Failed to reject document');
                        }
                    }
                });
            });
        });
    </script>
@endpush
    @endcan
