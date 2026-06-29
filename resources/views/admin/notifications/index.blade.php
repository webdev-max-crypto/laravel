@extends('admin.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .stats-bar { display: flex; gap: 12px; margin-bottom: 22px; flex-wrap: wrap; }
    .stat-chip { background: var(--white); border: 1.5px solid var(--border); border-radius: 10px; padding: 10px 18px; font-size: 13px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 7px; }
    .stat-chip span { color: var(--blue); font-size: 18px; font-weight: 800; }

    .notif-list { display: flex; flex-direction: column; gap: 10px; max-width: 760px; }
    .notif-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 12px; padding: 16px 20px; display: flex; align-items: flex-start; gap: 14px; transition: all 0.18s; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
    .notif-card:hover { border-color: var(--blue); box-shadow: 0 4px 16px rgba(37,99,235,0.08); transform: translateY(-1px); }
    .notif-card.unread { border-color: var(--sky2); background: var(--sky); }
    .notif-card.unread:hover { border-color: var(--blue); }

    .notif-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .notif-icon.unread-icon { background: var(--blue); }
    .notif-icon.read-icon   { background: #f1f5f9; }

    .notif-body { flex: 1; }
    .notif-message { font-size: 14px; font-weight: 600; color: var(--ink); line-height: 1.55; margin-bottom: 5px; }
    .notif-card:not(.unread) .notif-message { font-weight: 500; color: var(--slate); }
    .notif-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .notif-time { font-size: 12px; color: #94a3b8; }
    .badge-unread { font-size: 11px; font-weight: 700; background: var(--blue); color: #fff; padding: 2px 9px; border-radius: 20px; }
    .badge-read   { font-size: 11px; font-weight: 600; background: #f1f5f9; color: var(--slate); padding: 2px 9px; border-radius: 20px; }

    .btn-mark-read { background: var(--blue); color: #fff; padding: 6px 14px; border-radius: 7px; border: none; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none; transition: background 0.18s; font-family: 'Plus Jakarta Sans', sans-serif; display: inline-block; margin-top: 8px; }
    .btn-mark-read:hover { background: var(--blue2); color: #fff; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--slate); max-width: 400px; }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 14px; }
    .empty-state h3 { font-size: 16px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
</style>

<div class="page-header">
    <h2>🔔 Notifications</h2>
    <p>All your platform alerts and updates.</p>
</div>

@php
    $total  = count($notifications);
    $unread = collect($notifications)->where('is_read', false)->count();
@endphp

<div class="stats-bar">
    <div class="stat-chip"><span>{{ $total }}</span> Total</div>
    <div class="stat-chip"><span>{{ $unread }}</span> Unread</div>
    <div class="stat-chip"><span>{{ $total - $unread }}</span> Read</div>
</div>

<div class="notif-list">
    @forelse($notifications as $notif)
        <div class="notif-card {{ $notif->is_read ? '' : 'unread' }}">
            <div class="notif-icon {{ $notif->is_read ? 'read-icon' : 'unread-icon' }}">
                {{ $notif->is_read ? '📩' : '🔔' }}
            </div>
            <div class="notif-body">
                <div class="notif-message">{{ $notif->message }}</div>
                <div class="notif-meta">
                    <span class="notif-time">🕐 {{ $notif->created_at->diffForHumans() }}</span>
                    @if($notif->is_read)
                        <span class="badge-read">✓ Read</span>
                    @else
                        <span class="badge-unread">● New</span>
                    @endif
                </div>
                @if(!$notif->is_read)
                    <a href="{{ route('admin.notifications.read', $notif->id) }}" class="btn-mark-read">Mark as Read</a>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">🔕</div>
            <h3>No Notifications</h3>
            <p>You're all caught up!</p>
        </div>
    @endforelse
</div>
@endsection
