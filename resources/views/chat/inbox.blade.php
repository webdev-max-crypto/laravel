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
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); display: flex; align-items: center; gap: 8px; }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .section-title {
        font-size: 12px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: var(--slate);
        margin: 20px 0 10px; display: flex; align-items: center; gap: 8px;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .chat-list { display: flex; flex-direction: column; gap: 8px; max-width: 680px; }

    .chat-item {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 14px 18px;
        display: flex; align-items: center; gap: 14px;
        text-decoration: none;
        transition: all 0.18s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .chat-item:hover {
        border-color: var(--blue);
        box-shadow: 0 4px 16px rgba(37,99,235,0.1);
        transform: translateX(3px);
    }
    .chat-item.has-unread { border-color: var(--sky2); background: var(--sky); }
    .chat-item.has-unread:hover { border-color: var(--blue); }

    .chat-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--blue);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; font-weight: 800; color: #fff;
        flex-shrink: 0;
    }
    .chat-avatar.new-contact { background: #f1f5f9; color: var(--slate); border: 1.5px dashed var(--border); }

    .chat-info { flex: 1; min-width: 0; }
    .chat-name { font-size: 14px; font-weight: 700; color: var(--ink); }
    .chat-role { font-size: 12px; color: var(--slate); margin-top: 2px; }

    .chat-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .unread-badge {
        background: #ef4444; color: #fff;
        border-radius: 20px; padding: 2px 9px;
        font-size: 11px; font-weight: 800;
    }
    .new-label {
        background: var(--sky); color: var(--blue);
        border: 1px solid var(--sky2);
        border-radius: 20px; padding: 2px 9px;
        font-size: 11px; font-weight: 700;
    }
    .chat-arrow { color: var(--slate); font-size: 16px; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); max-width: 400px; }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }
</style>

<div class="page-header">
    <h2>💬 Messages</h2>
    <p>Your conversations with {{ auth()->user()->role === 'owner' ? 'admin and customers' : (auth()->user()->role === 'admin' ? 'warehouse owners' : 'warehouse owners') }}.</p>
</div>

{{-- Existing Conversations --}}
@if($contacts->count())
    <div class="section-title">Conversations</div>
    <div class="chat-list">
        @foreach($contacts as $contact)
            @php
                $unread = \App\Models\Message::where('sender_id', $contact->id)
                    ->where('receiver_id', auth()->id())
                    ->where('is_read', false)->count();
                $initials = strtoupper(substr($contact->name, 0, 1));
            @endphp
            <a href="{{ route('chat.show', $contact->id) }}" class="chat-item {{ $unread ? 'has-unread' : '' }}">
                <div class="chat-avatar">{{ $initials }}</div>
                <div class="chat-info">
                    <div class="chat-name">{{ $contact->name }}</div>
                    <div class="chat-role">{{ ucfirst($contact->role) }}</div>
                </div>
                <div class="chat-meta">
                    @if($unread)
                        <span class="unread-badge">{{ $unread }} new</span>
                    @endif
                    <span class="chat-arrow">›</span>
                </div>
            </a>
        @endforeach
    </div>
@endif

{{-- New Contacts --}}
@if($newContacts->count())
    <div class="section-title" style="{{ $contacts->count() ? 'margin-top:28px;' : '' }}">Start New Chat</div>
    <div class="chat-list">
        @foreach($newContacts as $contact)
            @php $initials = strtoupper(substr($contact->name, 0, 1)); @endphp
            <a href="{{ route('chat.show', $contact->id) }}" class="chat-item">
                <div class="chat-avatar new-contact">{{ $initials }}</div>
                <div class="chat-info">
                    <div class="chat-name">{{ $contact->name }}</div>
                    <div class="chat-role">{{ ucfirst($contact->role) }}</div>
                </div>
                <div class="chat-meta">
                    <span class="new-label">Start chat</span>
                    <span class="chat-arrow">›</span>
                </div>
            </a>
        @endforeach
    </div>
@endif

@if($contacts->isEmpty() && $newContacts->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">💬</div>
        <p>No contacts available to chat with yet.</p>
    </div>
@endif

@endsection
