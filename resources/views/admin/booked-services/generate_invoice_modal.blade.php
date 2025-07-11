<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Invoice</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="invoiceForm" method="POST">
                @csrf
                <input type="hidden" name="book_service_id" id="modalBookedServiceId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (£)</label>
                        <input type="number" name="amount" id="amount" class="form-control" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="promo_code_id" class="form-label">Select Promo Code</label>
                        <select name="promo_code_id" id="promo_code_id" class="form-control">
                            <option value="">Select Promo</option>
                            @foreach ($promo_codes as $coupon)
                                <option value="{{ $coupon->id }}" data-discount-type="{{ $coupon->discount_type }}"
                                    data-discount-amount="{{ $coupon->discount_amount }}">
                                    {{ $coupon->code }}
                                    ({{ $coupon->discount_type == '%' ? $coupon->discount_amount . ' %' : '£ ' . $coupon->discount_amount }})
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="mb-3">
                        <label class="form-label">Final Amount (£)</label>
                        <input type="text" id="final_price" class="form-control" readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Generate</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="statusUpdateForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusUpdateModalLabel">Upload PDF for Status Update</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="book_service_id" id="book_service_id">
                    <input type="hidden" name="status" id="status">
                    <input type="hidden" name="status_text" id="status_text">

                    <div class="mb-3">
                        <label for="pdf_file" class="form-label">Upload Itinerary & Schedule (PDF) File</label>
                        <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                            class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Upload PDF Modal -->
{{-- <div class="modal fade" id="uploadPdfModal" tabindex="-1" aria-labelledby="uploadPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog"> --}}
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
                        <option value="{{ $bookedService->id }}">{{ $bookedService->getService() }}</option>
                    </select>
                    {{-- @foreach ($bookedServices as $service)
                        <option value="{{ $service->id }}">{{ $service->id }} - {{ $service->service_name }}</option>
                    @endforeach --}}
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
                    <input type="file" name="file" class="form-control" accept="application/pdf">
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

                    <input type="hidden" value="{{ $bookedService->id }}" name="book_service_id"
                        id="view_book_service_id">
                    <input type="hidden" value="{{ $bookedService->user_id }}" name="user_id" id="viw_user_id">

                    <h5><strong>Subject:</strong> <span id="pdfSubjectView"></span></h5>
                    <p><strong>Message:</strong> <span id="pdfTextView"></span></p>
                    <p>
                        <strong>PDF File:</strong>
                        <a href="#" id="pdfDownloadLink" target="_blank"
                            class="btn btn-outline-primary btn-sm">
                            View PDF
                        </a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="uploadPdfBtn" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

