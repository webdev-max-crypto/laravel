@php
    $layout = match(auth()->user()->role) {
        'admin'    => 'admin.layouts.app',
        'owner'    => 'layouts.owner',
        'customer' => 'customer.layouts.app',
        default    => 'layouts.app',
    };
@endphp

@extends($layout)

@section('content')

<style>
    .chat-wrapper {
        max-width: 720px;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 140px);
        min-height: 500px;
    }

    /* Chat header */
    .chat-header {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px 14px 0 0;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .back-link {
        color: var(--slate); text-decoration: none;
        font-size: 18px; line-height: 1;
        transition: color 0.15s; flex-shrink: 0;
    }
    .back-link:hover { color: var(--blue); }

    .header-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--blue);
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; font-weight: 800; color: #fff; flex-shrink: 0;
    }
    .header-info { flex: 1; }
    .header-name { font-size: 15px; font-weight: 800; color: var(--ink); }
    .header-role { font-size: 12px; color: var(--slate); margin-top: 1px; }

    .online-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--emerald);
        box-shadow: 0 0 0 2px #fff, 0 0 0 3px var(--emerald);
    }

    /* Messages area */
    .chat-box {
        flex: 1;
        background: var(--bg);
        border-left: 1.5px solid var(--border);
        border-right: 1.5px solid var(--border);
        padding: 20px 18px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Date divider */
    .date-divider {
        text-align: center;
        font-size: 11px; font-weight: 700;
        color: var(--slate);
        display: flex; align-items: center; gap: 10px;
        margin: 6px 0;
    }
    .date-divider::before, .date-divider::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    /* Message bubbles */
    .msg-item {
        display: flex;
        align-items: flex-end;
        gap: 8px;
    }
    .msg-item.sent     { flex-direction: row-reverse; }
    .msg-item.received { flex-direction: row; }

    .msg-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--sky2);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; color: var(--blue);
        flex-shrink: 0; margin-bottom: 2px;
    }
    .msg-item.sent .msg-avatar { background: var(--blue); color: #fff; }

    .msg-bubble {
        max-width: 68%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 13.5px;
        line-height: 1.55;
        word-break: break-word;
    }
    .msg-item.sent .msg-bubble {
        background: var(--blue);
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    .msg-item.received .msg-bubble {
        background: var(--white);
        color: var(--ink);
        border: 1px solid var(--border);
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .msg-time {
        font-size: 10.5px;
        margin-top: 5px;
        display: block;
    }
    .msg-item.sent     .msg-time { color: rgba(255,255,255,0.65); text-align: right; }
    .msg-item.received .msg-time { color: var(--slate); }

    /* Empty chat */
    .chat-empty {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: var(--slate); text-align: center; gap: 10px;
    }
    .chat-empty .empty-icon { font-size: 44px; }
    .chat-empty p { font-size: 13.5px; }

    /* Input bar */
    .chat-input-bar {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-top: none;
        border-radius: 0 0 14px 14px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .msg-input {
        flex: 1;
        padding: 10px 16px;
        border: 1.5px solid var(--border);
        border-radius: 24px;
        font-size: 13.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--ink);
        background: var(--bg);
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .msg-input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        background: var(--white);
    }
    .send-btn {
        width: 42px; height: 42px;
        background: var(--blue);
        border: none; border-radius: 50%;
        color: #fff; font-size: 16px;
        cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.18s;
    }
    .send-btn:hover { background: var(--blue2); transform: scale(1.08); }
    .send-btn:active { transform: scale(0.95); }
</style>

@php $initials = strtoupper(substr($user->name, 0, 1)); @endphp

<div class="chat-wrapper">

    {{-- Header --}}
    <div class="chat-header">
        <a href="{{ route('chat.inbox') }}" class="back-link">←</a>
        <div class="header-avatar">{{ $initials }}</div>
        <div class="header-info">
            <div class="header-name">{{ $user->name }}</div>
            <div class="header-role">{{ ucfirst($user->role) }}</div>
        </div>
        <div class="online-dot"></div>
    </div>

    {{-- Messages --}}
    <div class="chat-box" id="chat-box">
        @if($messages->isEmpty())
            <div class="chat-empty">
                <div class="empty-icon">👋</div>
                <p>No messages yet. Say hello!</p>
            </div>
        @else
            @php $lastDate = null; @endphp
            @foreach($messages as $msg)
                @php
                    $msgDate = $msg->created_at->format('d M Y');
                    $isSent  = $msg->sender_id == $me->id;
                @endphp

                @if($msgDate !== $lastDate)
                    <div class="date-divider">
                        {{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msgDate) }}
                    </div>
                    @php $lastDate = $msgDate; @endphp
                @endif

                <div class="msg-item {{ $isSent ? 'sent' : 'received' }} msg-item-el" data-id="{{ $msg->id }}">
                    <div class="msg-avatar">{{ $isSent ? strtoupper(substr($me->name,0,1)) : $initials }}</div>
                    <div class="msg-bubble">
                        {{ $msg->body }}
                        <span class="msg-time">{{ $msg->created_at->format('h:i A') }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Input --}}
    <form action="{{ route('chat.send', $user->id) }}" method="POST" class="chat-input-bar" id="chat-form">
        @csrf
        <input type="text" name="body" id="msg-input"
               class="msg-input"
               placeholder="Type a message..."
               autocomplete="off" required>
        <button type="submit" class="send-btn" title="Send">➤</button>
    </form>

</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const userId  = {{ $user->id }};
    const meInitial = "{{ strtoupper(substr($me->name,0,1)) }}";

    function scrollBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    scrollBottom();

    function lastId() {
        const items = chatBox.querySelectorAll('.msg-item-el[data-id]');
        if (!items.length) return 0;
        return parseInt(items[items.length - 1].dataset.id);
    }

    function formatTime(dateStr) {
        const d = new Date(dateStr);
        let h = d.getHours(), m = d.getMinutes();
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return `${h}:${m.toString().padStart(2,'0')} ${ampm}`;
    }

    // Poll every 3 seconds
    setInterval(function () {
        fetch(`/chat/${userId}/poll?after=${lastId()}`)
            .then(r => r.json())
            .then(msgs => {
                // Remove empty state if present
                const empty = chatBox.querySelector('.chat-empty');
                if (empty && msgs.length) empty.remove();

                msgs.forEach(msg => {
                    const wrap = document.createElement('div');
                    wrap.className = 'msg-item received msg-item-el';
                    wrap.dataset.id = msg.id;
                    wrap.innerHTML = `
                        <div class="msg-avatar">${"{{ $initials }}"}</div>
                        <div class="msg-bubble">
                            ${msg.body}
                            <span class="msg-time">${formatTime(msg.created_at)}</span>
                        </div>`;
                    chatBox.appendChild(wrap);
                    scrollBottom();
                });
            }).catch(() => {});
    }, 3000);

    // Send on Enter key
    document.getElementById('msg-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chat-form').submit();
        }
    });
</script>

@endsection
