<div>
    @if ($chat->status == 'open')
        <span class="btn btn-sm btn-warning">Open</span>
    @elseif($chat->status == 'pending_agent')
        <span class="btn btn-sm btn-info">Pending Agent</span>
    @elseif($chat->status == 'agent_assigned')
        <span class="btn btn-sm btn-success">Agent Assigned</span>
    @elseif($chat->status == 'closed')
        <span class="btn btn-sm btn-danger">Closed</span>
    @else
        <span class="btn btn-sm btn-danger">Something went wrong!</span>
    @endif
</div>
