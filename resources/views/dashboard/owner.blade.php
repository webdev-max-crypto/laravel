@extends('layouts.owner')

@section('content')
<div class="container">

    @if(session('warehouse_approved'))
        <div class="alert alert-success">
            🎉 Your warehouse has been approved!  
            <a href="{{ route('owner.warehouses.index') }}">View Warehouses</a>
        </div>
    @endif

    <!-- Fetch Dashboard Stats -->
    @php
        $total = \App\Models\Warehouse::where('owner_id', auth()->id())->count();
        $pending = \App\Models\Warehouse::where('owner_id', auth()->id())->where('status','pending')->count();
        $approved = \App\Models\Warehouse::where('owner_id', auth()->id())->where('status','approved')->count();
    @endphp

    <!-- Welcome Section -->
    <div class="card shadow p-4 mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" 
                 class="rounded-circle me-3" width="80" height="80">

            <div>
                <h2 class="mb-0">Welcome, {{ auth()->user()->name }} 👋</h2>
                <p class="text-muted mb-0">Role: <strong>Owner</strong></p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row">

        <div class="col-md-4">
            <div class="card shadow text-center p-4">
                <h4>Total Warehouses</h4>
                <h2 class="text-primary">{{ $total }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow text-center p-4">
                <h4>Pending Approvals</h4>
                <h2 class="text-warning">{{ $pending }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow text-center p-4">
                <h4>Approved Warehouses</h4>
                <h2 class="text-success">{{ $approved }}</h2>
            </div>
        </div>

    </div>

    <!-- Actions -->
    <div class="card shadow p-4 mt-4">
        <h3>Notifications</h3>
        <ul>
    @forelse($notifications as $notification)
        <li>{{ $notification->message }} - <small>{{ $notification->created_at->diffForHumans() }}</small></li>
    @empty
        <li>No notifications</li>
    @endforelse
        </ul>

        <h4>Quick Actions</h4>

        <div class="d-flex gap-3 mt-3">

            <a href="{{ route('owner.profile') }}" class="btn btn-outline-primary">
                Update Profile
            </a>

            <a href="{{ route('owner.warehouses.create') }}" class="btn btn-success">
                Add New Warehouse
            </a>

            <a href="{{ route('owner.delete.confirm') }}" class="btn btn-outline-danger">
                Delete Account
            </a>
            


        </div>
    </div>

</div>
@endsection
