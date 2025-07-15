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