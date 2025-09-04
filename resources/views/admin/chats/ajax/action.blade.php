<div class="row">
    @can('response_chat')
        <div>
            <button type="button" class="btn btn-outline-success btn-sm view_chat" data-id="{{ $chat->id }}">View</button>
        </div>
    @endcan
    @can('edit_chats')
        <div>
            <a href="{{ route('chats.edit', $chat->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
        </div>
    @endcan
</div>
