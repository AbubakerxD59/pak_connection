@extends('admin.layouts.secure')
@section('page_title', 'Services')
@section('page_content')
    @can('view_user')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Services</h1>
                <div class="float-right d-flex">
                    <form action="{{ route('fields.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <input type="file" name="import" id="import" class="form-control d-none"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <label for="import" class="btn btn-primary my-0 mx-2">
                            <i class="fa fa-download"></i>
                            Import
                        </label>
                    </form>
                    <a class="btn btn-primary" data-toggle="modal" data-target="#add_features_modal">
                        <i class="fas fa-plus-square"></i>
                        Add new
                    </a>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadPdfModal">
                        Upload Book Service PDF
                    </button>

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
                                                    <table class="table table-striped table-bordered table-sortable"
                                                        id="dataTable">
                                                        <thead>
                                                            <th></th>
                                                            <th>ID</th>
                                                            <th>Icon</th>
                                                            <th>Name</th>
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
    @include('admin.features.add_features_modal')
@endsection
@push('scripts')
    <script type="text/javascript">
        // server side dataTable
        var dataTable = $('#dataTable').DataTable({
            "paging": true,
            'iDisplayLength': 100,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ route('features.dataTable') }}",
            },
            columns: [{
                    data: 'order_span'
                },
                {
                    data: 'order'
                },
                {
                    data: 'icon_image'
                },
                {
                    data: 'name_link'
                },
                {
                    data: 'action'
                }
            ],
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('change', '#import', function() {
                $('#importForm').submit();
            });
            // sortable
            $('.table-sortable tbody').sortable({
                handle: 'span'
            }).bind('sortupdate', function(e, ui) {
                var feature_id = ui.item.find('.order_row').data('id');
                var order_id = ui.item.find('.order_row').data('order');
                var order = ui.item[0].rowIndex;
                var no_of_entry = $("select[name='dataTable_length']").find(":selected").val();
                var page = $('.pagination').find('.active').find('a').html();

                $.ajax({
                    url: "{{ route('features.saveOrder') }}",
                    method: "GET",
                    data: {
                        "feature_id": feature_id,
                        "old_order": order_id,
                        "new_order": order,
                        "total_records": no_of_entry,
                        "page": page,
                    },
                    success: function(response) {
                        if (response.success) {
                            dataTable.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                })
            });
        });
    </script>
@endpush
