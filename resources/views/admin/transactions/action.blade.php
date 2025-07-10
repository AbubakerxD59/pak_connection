<div class="d-flex">
    <button type="button" class="btn btn-outline-primary btn-sm view-transaction-btn"
        data-label="{{ $transaction->getUser() . '-' . $transaction->getPackage() }}"
        data-total="{{ str_replace('£', '', $transaction->total_amount) }}"
        data-discount="{{ str_replace('£', '', $transaction->discount_amount) }}"
        data-payable="{{ str_replace('£', '', $transaction->payable_amount) }}" data-status="{{ $transaction->status }}"
        data-toggle="modal" data-target="#viewTransactionModal">
        View
    </button>
</div>

<!-- View Transaction Modal -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header d-block">
                <h5 class="modal-title" id="transactionModalLabel"></h5>
                <span class="btn btn-success status_view paid_status" style="display:none;">Paid</span>
                <span class="btn btn-success status_view unpaid_status" style="display:none;">Unpaid</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="transactionModalBody">
                Loading...
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).on('click', '.view-transaction-btn', function() {
        const label = $(this).data('label');
        const status = $(this).data('status');
        const total = parseFloat($(this).data('total')).toFixed(2);
        const discount = parseFloat($(this).data('discount')).toFixed(2);
        const payable = parseFloat($(this).data('payable')).toFixed(2);
        $('#transactionModalLabel').text(label); // Set modal title
        $('.status_view').hide();
        if (status) {
            $(".paid_status").show();
        } else {
            $('.unpaid_status').show();
        }
        const html = `
        <table style="width: 100%; border-collapse: collapse; font-size: 16px; margin: 20px 0;">
          
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Total Amount</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;">£ ${total}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Discount</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;">£ ${discount}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Payable Amount</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;"><strong>£ ${payable}</strong></td>
            </tr>
        </table>
    `;
        $('#transactionModalBody').html(html);
    });
</script>
