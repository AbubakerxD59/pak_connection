<div>
    <span>
        <a href="{{ route('users.edit', @$user->id) }}">
            {{ @$user->full_name . ' (' . @$user->membership_id . ')' }}
        </a>
    </span>
    <br>
    <span>{{ @$user->email }}</span>
</div>
