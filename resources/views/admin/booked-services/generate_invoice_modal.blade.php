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
                    <div class="mb-3">
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
                    </div>

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
