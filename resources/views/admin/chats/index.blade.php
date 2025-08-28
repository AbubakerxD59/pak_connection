@extends('admin.layouts.secure')
@section('page_title', 'Chats')
@section('page_content')
    @can('view_user')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Chats</h1>
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
                                                            <th>User</th>
                                                            <th>Status</th>
                                                            <th>Agent</th>
                                                            <th>Action</th>
                                                        </thead>
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
    @endcan
@endsection
@push('scripts')
    <script type="text/javascript">
        $('#dataTable').DataTable({
            "paging": true,
            'iDisplayLength': 10,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ route('chats.dataTable') }}",
            },
            columns: [{
                    data: 'user_name'
                },
                {
                    data: 'status_view'
                },
                {
                    data: 'agent_name'
                },
                {
                    data: 'action'
                },
            ],
        });
    </script>
@endpush
