@extends('admin.layouts.secure')
@section('page_title', 'Member Documents')
@section('page_content')
    <div class="page-content">
        <div class="content-header clearfix">
            <h1 class="float-left">Verification Documents - {{ $user->full_name }}</h1>
            <div class="float-right">
                <a class="btn btn-secondary" href="{{ route('verification.index') }}">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Member Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Full Name:</strong> {{ $user->full_name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Membership ID:</strong> {{ $user->membership_id }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Verification Status:</strong>
                                            @if ($user->verification_status == 'verified')
                                                <span class="badge bg-success">Verified</span>
                                            @elseif($user->verification_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($user->verification_status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">Unverified</span>
                                            @endif
                                        </p>
                                        <p><strong>WhatsApp:</strong> {{ $user->whatsapp_number }}</p>
                                        <p><strong>Country:</strong> {{ $user->country }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Submitted Documents</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-list">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Document Type</th>
                                                    <th>Status</th>
                                                    <th>Submitted Date</th>
                                                    <th>Verified By</th>
                                                    <th>Admin Notes</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($documents as $document)
                                                    <tr>
                                                        <td>{{ $document->id }}</td>
                                                        <td>{{ ucfirst($document->document_type) }}</td>
                                                        <td>
                                                            @if ($document->status == 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @elseif($document->status == 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @else
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                                        <td>{{ $document->verifiedBy ? $document->verifiedBy->full_name : '-' }}
                                                        </td>
                                                        <td>{{ $document->admin_notes ?? '-' }}</td>
                                                        <td>
                                                            <a href="{{ url($document->document_url) }}" target="_blank"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fa fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No documents found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
