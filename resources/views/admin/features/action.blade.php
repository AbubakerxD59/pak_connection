<div class="d-flex">
    @can('edit_feature')
        <div>
            <a href="{{ route('features.edit', $feature->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        </div>
    @endcan
    @can('delete_feature')
        <div>
            <form action="{{ route('features.destroy', $feature->id) }}" method="POST" class="delete_form d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">Delete</button>
                {{-- onclick="confirmDelete(event)" --}}
            </form>
        </div>
    @endcan
</div>
