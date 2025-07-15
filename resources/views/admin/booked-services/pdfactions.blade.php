<div class="d-flex">
    <div>
        <button type="button" class="btn btn-outline-success btn-sm view-pdf-btn" data-subject="{{ $service->subject }}"
            data-text="{{ $service->text }}" data-file="{{ asset($service->file) }}" data-toggle="modal" data-pdf_id="{{ $service->id }}"
            data-target="#viewPdfModal">
            View
        </button>

    </div>
    @can('edit_booked_services')
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary edit-pdf-btn" data-pdf_id="{{ $service->id }}"
                data-book_service_id="{{ $service->book_service_id }}" data-subject="{{ $service->subject }}"
                data-text="{{ $service->text }}" id="openUploadModal_Btn" data-toggle="modal" data-target="#uploadPdfModal">
                Edit
            </button>
            {{-- data-toggle="modal" data-target="#uploadPdfModal" --}}
        </div>
    @endcan
    @can('delete_booked_services')
        <div>
            <div>
                <form action="{{ route('booked-services.pdf.delete', $service->id) }}" method="POST" class="delete_form d-inline">
                    @csrf
                    @method('DELETE')
                    {{-- <button type="submit" class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this PDF?')">
                        Delete
                    </button> --}}
                    <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">Delete</button>
                </form>
            </div>

        </div>
    @endcan
</div>
