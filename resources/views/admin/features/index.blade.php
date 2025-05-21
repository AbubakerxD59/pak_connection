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
                url: "{{ route('features.dataTable') }}",
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'icon_image'
                },
                {
                    data: 'name'
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
        });
    </script>
@endpush
