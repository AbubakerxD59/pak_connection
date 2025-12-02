<div class="d-flex">
    @if ($role != 'Super Admin')
        @can('edit_user')
            <div>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
            </div>
        @endcan
        @can('view_user')
            @if ($user->latestVerificationDocument)
                <div class="mx-1">
                    <a href="{{ route('verification.user.documents', $user->id) }}" class="btn btn-outline-info btn-sm"
                        title="View Verification Documents">
                        <i class="fa fa-id-card"></i> Documents
                    </a>
                </div>
            @endif
        @endcan
        @can('delete_user')
            <div>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete_form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">Delete</button>
                </form>
            </div>
        @endcan
    @endif
</div>
