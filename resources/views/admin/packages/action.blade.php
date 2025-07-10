<div class="d-flex">
    @can('edit_package')
        <div>
            <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        </div>
    @endcan
    @can('delete_package')
        <div>
            <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="delete_form d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">Delete</button>
                {{-- onclick="confirmDelete(event)" --}}
            </form>
        </div>
    @endcan
</div>
