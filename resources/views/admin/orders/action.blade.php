<div class="d-flex">
    @can('edit_orders')
        <div>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        </div>
    @endcan
    @can('delete_orders')
        <div>
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="delete_form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn"
                    onclick="confirmDelete(event)">Delete</button>
            </form>
        </div>
    @endcan
</div>
