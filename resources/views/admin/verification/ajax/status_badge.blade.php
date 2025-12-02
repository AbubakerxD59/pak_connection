<div>
    @if ($status == 'pending')
        <span class="badge badge-warning">Pending</span>
    @elseif($status == 'approved')
        <span class="badge badge-success">Approved</span>
    @elseif($status == 'rejected')
        <span class="badge badge-danger">Rejected</span>
    @endif
</div>
