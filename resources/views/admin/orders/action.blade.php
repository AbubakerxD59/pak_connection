<div class="d-flex">
    @can('edit_orders')
        <div>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-outline-primary btn-sm">View</a>
        </div>
    @endcan
    @can('delete_orders')
        <div>
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="delete_form d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">Delete</button>
                {{-- onclick="confirmDelete(event)" --}}
            </form>
        </div>
    @endcan
</div>
