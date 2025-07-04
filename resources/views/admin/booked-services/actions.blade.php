<div class="d-flex">
    @if ($service->depositStatus())
        <div>
            <span class="btn btn-outline-secondary btn-sm view_booked_service deposit-payment-btn"
                data-id="{{ $service->id }}">
                Deposit Payment
            </span>
        </div>
    @endif
    @if ($service->invoiceStatus())
        <div>
            <button type="button" class="btn btn-outline-secondary btn-sm generate-invoice-btn" data-bs-toggle="modal"
                data-bs-target="#invoiceModal" data-id="{{ $service->id }}">
                Generate Invoice
            </button>

        </div>
    @endif
    <div>
        <span class="btn btn-outline-success btn-sm view_booked_service" data-id="{{ $service->id }}">View</span>
    </div>
    @can('edit_booked_services')
        <div>
            <a href="{{ route('users.booked_service.edit', $service->id) }}"
                class="btn btn-outline-primary btn-sm">Edit</a>
        </div>
    @endcan
    @can('delete_booked_services')
        <div>
            <a href="{{ route('users.booked_service.delete', $service->id) }}"
                class="btn btn-outline-danger btn-sm">Delete</a>
        </div>
    @endcan
</div>
