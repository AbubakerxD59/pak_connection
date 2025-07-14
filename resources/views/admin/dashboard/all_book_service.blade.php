@extends('admin.layouts.secure')
@section('page_title', 'Book Services')
@section('page_content')
    @can('view_user')
        <div class="page-content">
         <div class="content-header clearfix">
                <h1 class="float-left">Book Service</h1>
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
                                                                {{-- <th>ID</th> --}}
                                                                <th>Member ID</th>
                                                                <th>Customer</th>
                                                                <th>Service</th>
                                                                <th>Package</th>
                                                                <th>Status</th>
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
                url: "{{ route('booked-services.dashboard.bookservice.dataTable') }}",
            },
            columns: [ {
                    data: 'membership_id'
                },
                {
                    data: 'customer_name'
                },
                {
                    data: 'service'
                },
                {
                    data:'package'
                },
                {
                    data: 'status_view'
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

@include('admin.booked-services.generate_invoice_modal')
@include('admin.packages.view_booked_service')
@push('scripts')
    @include('admin.booked-services.js.script')
@endpush
