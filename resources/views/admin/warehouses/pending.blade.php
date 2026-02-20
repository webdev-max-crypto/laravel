@extends('admin.layouts.app')
@section('content')
<h2 class="mb-4">Warehouses Management</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Owner</th>
            <th>Warehouse</th>
            <th>Location</th>
            <th>Price / Month</th>
            <th>Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($warehouses as $w)
        <tr>
            <td>{{ $w->owner->name }} <br><small>{{ $w->owner->email }}</small></td>
            <td>{{ $w->name }}</td>
            <td>{{ \Illuminate\Support\Str::limit($w->location, 40) }}</td>
            <td>{{ $w->price_per_month }}</td>
            <td>
                @if($w->image)
                    <a href="{{ asset('storage/'.$w->image) }}" target="_blank">
                        <img src="{{ asset('storage/'.$w->image) }}" width="80" class="rounded">
                    </a>
                @endif
            </td>
            <td>
                @if($w->status === 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($w->status === 'approved')
                    <span class="badge bg-success">
                        Active
                        @if($w->active_frequency)
                            ({{ $w->active_frequency }} times booked)
                        @endif
                    </span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($w->status) }}</span>
                @endif
            </td>
            <td>
                @if($w->status === 'pending')
                    <form action="{{ route('admin.warehouses.approve', $w->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="{{ route('admin.warehouses.reject', $w->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </form>
                    <a href="{{ route('admin.warehouses.show', $w->id) }}" class="btn btn-sm btn-info">View Details</a>
                @else
                    <span class="text-muted">No actions</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">No warehouses found</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection
