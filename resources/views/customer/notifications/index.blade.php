@extends('layouts.customer')

@section('content')
<h3>Notifications</h3>
<a href="{{ route('customer.dashboard') }}" class="btn btn-sm btn-secondary mb-3">⬅ Back to Dashboard</a>

@forelse($notifications as $notif)
    <div class="card mb-2 {{ $notif->is_read ? '' : 'border-primary' }}">
        <div class="card-body">
            {{ $notif->message }}
            <small class="text-muted d-block">{{ $notif->created_at->diffForHumans() }}</small>
            @if(!$notif->is_read)
                <a href="{{ route('customer.notifications.read', $notif->id) }}" class="btn btn-sm btn-primary mt-2">Mark as read</a>
            @endif
        </div>
    </div>
@empty
    <div class="alert alert-info">You have no notifications.</div>
@endforelse
@endsection
