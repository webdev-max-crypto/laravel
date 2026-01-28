

@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Notifications</h1>

@if(count($notifications) > 0)
    @foreach($notifications as $notif)
        <div class="notification">
            <p>{{ $notif->message }}</p>
            @if(!$notif->read_at)
                <a href="{{ route(auth()->user()->role.'.notifications.read', $notif->id) }}" class="btn">Mark as read</a>
            @endif
        </div>
    @endforeach
@else
    <p>No notifications available.</p>
@endif
@endsection
