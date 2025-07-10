<div class="d-flex">
    @if ($role != 'Super Admin')
        @can('edit_user')
            <div>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
            </div>
        @endcan
        @can('delete_user')
            <div>
                {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete_form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm delete-btn"
                        onclick="confirmDelete(event)">Delete</button>
                </form> --}}

                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete_form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">Delete</button>
                </form>

                {{-- <script>
                    $(document).on('click', '.delete-btn', function(e) {
                        e.preventDefault(); // prevent the default form submission

                        let form = $(this).closest('form');

                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'This action cannot be undone!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, delete it!',
                            reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // form.submit();
                                console.log('confirm clicked');
                            }
                        });
                    });
                </script> --}}

            </div>
        @endcan
    @endif
</div>
