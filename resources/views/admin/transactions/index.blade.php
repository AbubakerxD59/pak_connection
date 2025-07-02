@extends('admin.layouts.secure')
@section('page_title', 'Transactions')
@section('page_content')
    @can('view_transactions')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Transactions</h1>
                {{-- <div class="float-right">
                    <a class="btn btn-primary" href="{{ route('promo-code.create') }}">
                        <i class="fas fa-plus-square"></i> Add new</a>
                </div> --}}
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">



                            {{-- // show transactions  --}}

                            <div class="card">
                                <div class="card-header with-border clearfix">
                                    <div class="card-title">
                                        <i class="fas fa-list"></i>
                                        Transactions
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

                                                    <table class="table table-striped table-bordered" id="transactions_dt">
                                                        <thead>
                                                            {{-- <th>Transaction ID</th> --}}
                                                            <th>Customer</th>
                                                            <th>Order ID</th>
                                                            <th>Package</th>
                                                            <th>Amount</th>
                                                            <th>Package Amount</th>
                                                            <th>Discount Amount</th>
                                                            <th>Status</th>
                                                            <th>Date</th>


                                                            {{-- <th>Coupon</th>

                                                            
                                                            <th>Action</th> --}}
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- // show orders  --}}

                            <div class="card">
                                <div class="card-header with-border clearfix">
                                    <div class="card-title">
                                        <i class="fas fa-list"></i>
                                        Orders
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

                                                    <table class="table table-striped table-bordered" id="order_dt">
                                                        <thead>
                                                            <th>Order ID</th>
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
        $('#transactions_dt').DataTable({
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
                url: "{{ route('transactions.dataTable') }}",
                // url: "{{ route('orders.dataTable') }}",
            },
            columns: [
                // {
                //     data: 'id'
                // },
                {
                    data: 'customer_name'
                },
                {
                    data: 'order_id'
                },
                {
                    data: 'package_name'
                },
                {
                    data: 'total_amount'
                },
                {
                    data: 'package_amount'
                },
                {
                    data: 'discount_amount'
                },

                {
                    data: 'status_view'
                },
                {
                    data: 'date'
                },







                // {
                //     data: 'coupon_name'
                // },


                // {
                //     data: 'action'
                // }
            ],
        });
    </script>


    <script type="text/javascript">
        // server side dataTable
        $('#order_dt').DataTable({
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
                // url: "{{ route('transactions.dataTable') }}",
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
