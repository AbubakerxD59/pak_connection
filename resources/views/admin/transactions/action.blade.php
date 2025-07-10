<div class="d-flex">
    {{-- @can('edit_transactions')
        <div>
            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        </div>
    @endcan
    @can('delete_transactions')
        <div>
            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="delete_form d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn"
                >Delete</button>
            </form>
        </div>
    @endcan --}}
    
    <button type="button" class="btn btn-outline-primary btn-sm view-transaction-btn"
        data-service="{{ $transaction->service_name }}" data-total="{{ str_replace('£', '', $transaction->total_amount) }}"
        data-discount="{{ str_replace('£', '', $transaction->discount_amount) }}" data-payable="{{ str_replace('£', '', $transaction->payable_amount) }}"
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
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">${service}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            {{-- <p>{{ $transaction->id }}</p> --}}
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
        const service = $(this).data('service');
        const total = parseFloat($(this).data('total')).toFixed(2);
        const discount = parseFloat($(this).data('discount')).toFixed(2);
        const payable = parseFloat($(this).data('payable')).toFixed(2);

        $('#transactionModalLabel').text(service); // Set modal title

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
