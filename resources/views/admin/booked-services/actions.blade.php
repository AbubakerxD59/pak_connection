<div class="d-flex">
    {{-- @if ($service->depositStatus())
        <div>
            <span class="btn btn-outline-secondary btn-sm deposit-payment-btn" data-id="{{ $service->id }}">
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
    @endif --}}



    @php
        use App\Models\BookService;
        $nextStatus = $service->status + 1;
        $statusLabel = BookService::$status_array[$nextStatus] ?? null;
        $statusText = $statusLabel ? 'Make ' . $statusLabel : null;
        $dataIdText = $statusLabel ? $statusLabel : null;
    @endphp

    {{--  when status is :1. --}}
    @if ($service->depositStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm deposit-payment-btn " data-id="{{ $service->id }}">
                {{ $statusText }} 
            </span>
        </div>
    @endif

    {{-- when status is : 2 --}}
    @if ($service->depositPaidStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm  update-next-status" data-id="{{ $service->id }}"
                data-status="{{ $nextStatus }}" data-status-text="{{ $dataIdText }}">
                {{ $statusText }} 
            </span>
        </div>
    @endif

    {{-- when status is : 3 --}}
    @if ($service->inprogressStatus())
        <div>
            <button type="button" class="btn btn-outline-dark btn-sm update-next-status" data-id="{{ $service->id }}"
                data-status="{{ $nextStatus }}" data-status-text="{{ $dataIdText }}">
                {{ $statusText }} 
            </button>
        </div>
    @endif

    {{-- when status is  4 --}}
    @if ($service->invoiceStatus())
         <div>
            <button type="button" class="btn btn-outline-dark btn-sm generate-invoice-btn" data-bs-toggle="modal"
                data-bs-target="#invoiceModal" data-id="{{ $service->id }}">
                {{ $statusText }} 
            </button>
        </div>
    @endif

    {{-- when status is : 4 --}}
    {{-- @if ($service->inprogressStatus())
        <div>
            <button type="button" class="btn btn-outline-dark btn-sm generate-invoice-btn" data-bs-toggle="modal"
                data-bs-target="#invoiceModal" data-id="{{ $service->id }}">
                {{ $statusText }} - 5
            </button>
        </div>
    @endif --}}

    {{-- when status is : 5 --}}
    @if ($service->fullPaymentStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm confirm-full-payment-btn update-next-status"
                data-id="{{ $service->id }}" data-status="{{ $nextStatus }}"
                data-status-text="{{ $dataIdText }}">
                {{ $statusText }}
            </span>
        </div>
    @endif

    {{-- when status is : 6 --}}
    @if ($service->scheduleStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm update-next-status" data-id="{{ $service->id }}"
                data-status="{{ $nextStatus }}" data-status-text="{{ $dataIdText }}">
                {{ $statusText }} 
            </span>
        </div>
    @endif

    {{-- when status is : 7 --}}
    @if ($service->preArrivalStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm update-next-status" data-id="{{ $service->id }}"
                data-status="{{ $nextStatus }}" data-status-text="{{ $dataIdText }}">
                {{ $statusText }} 
            </span>
        </div>
    @endif

    {{-- when status is : 8 --}}
    @if ($service->arrivalStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm update-next-status" data-id="{{ $service->id }}"
                data-status="{{ $nextStatus }}" data-status-text="{{ $dataIdText }}">
                {{ $statusText }} 
            </span>
        </div>
    @endif

    {{-- when status is : 9 --}}
    @if ($service->completionStatus())
        <div>
            <span class="btn btn-outline-dark btn-sm update-next-status" data-id="{{ $service->id }}"
                data-status="{{ $nextStatus }}" data-status-text="{{ $dataIdText }}">
                {{ $statusText }} 
            </span>
        </div>
    @endif

    {{-- when status is : 10 --}}
    @if ($service->status == 10)
        <div>
            <button class="btn btn-success btn-sm" disabled>
                {{ $statusText }} </>
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
            {{-- <a href="{{ route('users.booked_service.delete', $service->id) }}"
                class="btn btn-outline-danger btn-sm">Delete</a>

            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="delete_form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn"
                    onclick="confirmDelete(event)">Delete</button>
            </form> --}}

            <form action="{{ route('users.booked_service.delete', $service->id) }}" method="POST"
                style="display:inline-block;" onsubmit="return confirmDelete(event)">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
            </form>

        </div>
    @endcan
</div>
