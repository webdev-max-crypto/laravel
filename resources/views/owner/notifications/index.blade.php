@extends('layouts.owner') <!-- or owner/customer layout -->

@section('content')
<h3>Notifications</h3>

@foreach($notifications as $notif)
<div class="card mb-2 {{ $notif->is_read ? '' : 'border-primary' }}">
    <div class="card-body">
        {{ $notif->message }}
        <small class="text-muted d-block">{{ $notif->created_at->diffForHumans() }}</small>
        @if(!$notif->is_read)
            <a href="{{ route(auth()->user()->role.'.notifications.read', $notif->id) }}" class="btn btn-sm btn-primary mt-2">Mark as read</a>
        @endif
    </div>
</div>
@endforeach
@endsection
