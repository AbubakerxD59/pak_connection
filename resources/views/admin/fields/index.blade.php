@extends('admin.layouts.secure')
@section('page_title', 'Fields')
@section('page_content')
    @can('view_fields')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Fields</h1>
                <div class="float-right d-flex"></div>
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
                                                            <th>Name</th>
                                                            <th>Type</th>
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
                url: "{{ route('fields.dataTable') }}",
            },
            columns: [{
                    data: 'order_span'
                },
                {
                    data: 'order'
                },
                {
                    data: 'name'
                },
                {
                    data: 'type'
                }
            ],
        });
    </script>
    <script>
        $(document).ready(function() {
            // sortable
            $('.table-sortable tbody').sortable({
                handle: 'span'
            }).bind('sortupdate', function(e, ui) {
                var field_id = ui.item.find('.order_row').data('id');
                var order_id = ui.item.find('.order_row').data('order');
                var order = ui.item[0].rowIndex;
                var no_of_entry = $("select[name='dataTable_length']").find(":selected").val();
                var page = $('.pagination').find('.active').find('a').html();

                $.ajax({
                    url: "{{ route('fields.saveOrder') }}",
                    method: "GET",
                    data: {
                        "field_id": field_id,
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
