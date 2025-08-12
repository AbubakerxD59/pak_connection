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
                                                        id="booked_services_dataTable">
                                                        <thead>
                                                            <th>Member ID</th>
                                                            <th>Customer</th>
                                                            <th>Service</th>
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
    @include('admin.features.add_features_modal')
    @include('admin.booked-services.generate_invoice_modal')
    @include('admin.packages.view_booked_service')
@endsection
@push('scripts')
    @include('admin.booked-services.js.script')
@endpush
