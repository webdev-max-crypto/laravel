@extends('admin.layouts.app')
@section('content')

<h2 class="mb-4">Approved Warehouses</h2>

<table class="table table-bordered table-striped">
    <thead class="table-success">
        <tr>
            <th>Owner</th>
            <th>Warehouse</th>
            <th>Location</th>
            <th>Price / Month</th>
            <th>Image</th>
            <th>Status</th>
            <th>Last Active</th>
        </tr>
    </thead>

    <tbody>
    @forelse($warehouses as $w)
        <tr>
            <td>{{ $w->owner->name }} <br> <small>{{ $w->owner->email }}</small></td>
            <td>{{ $w->name }}</td>
            <td>{{ \Illuminate\Support\Str::limit($w->location, 40) }}</td>
            <td>Rs. {{ number_format($w->price_per_month, 2) }}</td>
            <td>
                @if($w->image)
                    <img src="{{ asset('storage/'.$w->image) }}" width="80" class="rounded">
                @endif
            </td>
            <td>
                @if($w->active_status === 'active')
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </td>
            <td>{{ $w->updated_at->diffForHumans() }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">No approved warehouses found</td>
        </tr>
    @endforelse
    </tbody>
</table>

@endsection
