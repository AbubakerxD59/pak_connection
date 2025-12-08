<div class="chat-container">
    @foreach ($messages as $message)
        @if ($message->sender_type == 'user')
            <div class="message sent">
                <div class="message-box message-box-user">
                    <span class="timestamp">
                        {{ \Carbon\Carbon::parse($message->created_at)->format(setting('time_format', 'h:i')) }}
                    </span>
                    <div class="message-content">
                        {!! $message->content !!}
                    </div>
                </div>
                <img src="{{ asset('images/m-avatar.png') }}" class="avatar" alt="User Avatar">
            </div>
        @else
            <div class="message received">
                <img src="{{ $message->sender_type == 'chatbot' ? asset('images/chat_bot.jpg') : asset('images/agent.webp') }}"
                    class="avatar" alt="User Avatar">
                <div class="message-box">
                    <div class="message-content">
                        {!! $message->content !!}
                    </div>
                    <span class="timestamp">
                        {{ \Carbon\Carbon::parse($message->created_at)->format(setting('time_format', 'h:i')) }}
                    </span>
                </div>
            </div>
        @endif
    @endforeach
    @if ($chat->status != 'closed' && in_array($chat->status, ['pending_agent', 'agent_assigned']))
        <div class="message-input-container">
            <input type="text" class="message-input" id="new_message" placeholder="Type a message...">
            <button class="send-button" data-id="{{ $chat->id }}">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    @endif
</div>
