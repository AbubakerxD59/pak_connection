<div class="d-flex">
    @can('edit_transactions')
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
                    {{-- onclick="confirmDelete(event)" --}}
            </form>
        </div>
    @endcan
</div>
