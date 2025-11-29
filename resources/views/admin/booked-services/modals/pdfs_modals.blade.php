<!-- Upload PDF Modal -->
<div class="modal fade" id="uploadPdfModal" tabindex="-1" role="dialog" aria-labelledby="uploadPdfModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="uploadPdfForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Upload PDF for Booked Service</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="hidden" name="pdf_id" id="pdf_id">
                    <label for="booked_service_id" class="form-label">Select Booked Service</label>
                    <select name="booked_service_id" id="booked_service_id" class="form-control" required>
                        <option value="{{ $service->id }}">{{ $service->getService() }}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Email Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="text" class="form-label">Email Body</label>
                    <textarea name="text" class="form-control" rows="4" id="text" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">Upload PDF</label>
                    <input type="file" name="file" class="form-control pdf_file" accept="application/pdf">
                    <div class="text-center p-2">
                        <iframe id="pdfPreview" style="display: none;" width="100%" height="500"
                            style="border: 1px solid #ccc;">
                        </iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="uploadPdfBtn" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- View PDF Modal -->
<div class="modal fade" id="viewPdfModal" tabindex="-1" role="dialog" aria-labelledby="viewPdfModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="viewPdfForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">PDF Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pdfIdView" name="pdf_id">
                    <input type="hidden" value="{{ $service->id }}" name="book_service_id" id="view_book_service_id">
                    <input type="hidden" value="{{ $service->user_id }}" name="user_id" id="viw_user_id">

                    <h5><strong>Subject:</strong> <span id="pdfSubjectView"></span></h5>
                    <p><strong>Message:</strong> <span id="pdfTextView"></span></p>
                    <p>
                        <strong>PDF File:</strong>
                        <a href="#" id="pdfDownloadLink" target="_blank" class="btn btn-outline-primary btn-sm">
                            View PDF
                        </a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="uploadPdfBtn" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary btn-cancel"
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
