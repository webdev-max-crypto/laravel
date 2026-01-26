@extends('layouts.owner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Your Warehouses</h2>
    <a href="{{ route('owner.warehouses.create') }}" class="btn btn-success">Add New Warehouse</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Status</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($warehouses as $w)
        <tr>
            <td>{{ $w->name }}</td>
            <td>{{ substr($w->location, 0, 45) }}...</td>

            <td>
                @if($w->status=='approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($w->status=='pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @else
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </td>

            <td>
                @if($w->image)
                    <img src="{{ asset($w->image) }}" width="90" height="70" class="rounded">
                @endif
            </td>

            <td>
                <a href="{{ route('owner.warehouses.show', $w->id) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('owner.warehouses.edit', $w->id) }}" class="btn btn-sm btn-primary">Edit</a>

                <form action="{{ route('owner.warehouses.destroy', $w->id) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No warehouses yet.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
