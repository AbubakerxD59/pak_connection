<div class="d-flex">
    @can('edit_settings')
        <div>
            <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-outline-primary btn-sm">
                Edit
            </a>
        </div>
    @endcan
    @can('delete_settings')
        {{-- <div class="mx-1">
            <form action="{{ route('settings.destroy', $setting->id) }}" method="POST" class="delete_form d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </form>
        </div> --}}
    @endcan
</div>
