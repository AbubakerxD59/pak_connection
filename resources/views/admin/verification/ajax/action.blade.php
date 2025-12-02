@can('view_verification')
    <a class="btn btn-sm btn-info text-white" href="{{ url($document->document_url) }}" target="_blank">
        <i class="fa fa-eye"></i>
        View
    </a>
@endcan

@if ($document->status == 'pending')
    @can('approve_verification')
        <button class="btn btn-sm btn-success approve-document mx-1" data-id="{{ $document->id }}">
            <i class="fa fa-check"></i>
            Approve
        </button>
    @endcan

    @can('reject_verification')
        <button class="btn btn-sm btn-danger reject-document" data-id="{{ $document->id }}">
            <i class="fa fa-times"></i>
            Reject
        </button>
    @endcan
@endif
