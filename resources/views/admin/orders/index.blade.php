@extends('admin.layouts.secure')
@section('page_title', 'Orders')
@section('page_content')
    @can('view_orders')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Orders</h1>
                {{-- <div class="float-right">
                    <a class="btn btn-primary" href="{{ route('promo-code.create') }}">
                        <i class="fas fa-plus-square"></i> Add new</a>
                </div> --}}
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
                                                            <th>Customer</th>
                                                            <th>Package</th>
                                                            <th>Coupon</th>
                                                            <th>Package Amount</th>
                                                            <th>Discount Amount</th>
                                                            <th>Amount</th>
                                                            <th>Status</th>
                                                            <th>Date</th>
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
        // server side dataTable
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
                url: "{{ route('orders.dataTable') }}",
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'customer_name'
                },
                {
                    data: 'package_name'
                },
                {
                    data: 'coupon_name'
                },
                {
                    data: 'package_amount'
                },
                {
                    data: 'discount_amount'
                },
                {
                    data: 'total_amount'
                },
                {
                    data: 'status_view'
                },
                {
                    data: 'date'
                },
                {
                    data: 'action'
                }
            ],
        });
    </script>
@endpush
