@extends('admin.layouts.secure')
@section('page_title', 'Transactions')
@section('page_content')
    @can('view_transactions')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Payments</h1>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            {{-- // show transactions  --}}
                            <div class="card">
                                <div class="card-header with-border clearfix">
                                    <div class="card-title">
                                        <i class="nav-icon fas fa-star"></i>
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

                                                    <table class="table table-striped table-bordered" id="dash_transactions_dt">
                                                        <thead>
                                                            <th>Customer</th>
                                                            <th>Member ID</th>
                                                            {{-- <th>Order ID</th> --}}
                                                            <th>Package</th>
                                                            <th>Amount</th>
                                                            <th>Package Amount</th>
                                                            <th>Discount Amount</th>
                                                            <th>Status</th>
                                                            <th>Date</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                          <div class="row mt-5">
                                             <div class="col-md-4 mb-4">
                                                <div class="card text-white bg-success shadow">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <span class="fw-semibold">Paid Services Payments</span>
                                                            <span
                                                                class="fw-bold fs-4">£ {{  number_format(sum_paid_service_payable_amount() ,2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                             <div class="col-md-4 mb-4">
                                                <div class="card text-white bg-danger shadow">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <span class="fw-semibold">Unpaid Services Payments</span>
                                                            <span
                                                                class="fw-bold fs-4">£ {{  number_format(sum_unpaid_service_payable_amount() ,2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                             <div class="col-md-4 mb-4">
                                                <div class="card text-white bg-dark shadow">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <span class="fw-semibold">Total Services Payments</span>
                                                            <span
                                                                class="fw-bold fs-4">£ {{  number_format(sum_paid_service_payable_amount() + sum_unpaid_service_payable_amount() ,2) }}</span>
                                                        </div>
                                                    </div>
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
        $('#dash_transactions_dt').DataTable({
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
                url: "{{ route('transactions.dashboard.order.dataTable') }}",
                type: 'GET',
                data: function(d) {
                    d.filter_type = 'booked_service';
                }
            },
            columns: [
                // {
                //     data: 'id'
                // },
                {
                    data: 'customer_name'
                },
                {
                    data: 'member_id'
                },
                // {
                //     data: 'order_num'
                // },
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
                    data: 'trans_status_view'
                },
                {
                    data: 'date'
                },
            ],
        });
    </script>
@endpush
