@extends('layouts.owner')

@section('content')
<h1>My Bookings</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($bookings->count())
<table class="table table-striped">
    <thead>
        <tr>
            <th>Warehouse</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->warehouse->name }}</td>
            <td>{{ $booking->customer->name }}</td>
            <td>
                @if($booking->status == 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif($booking->status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($booking->status == 'cancelled')
                    <span class="badge bg-danger">Cancelled</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($booking->end_date)->format('d-m-Y') }}</td>
            <td>${{ number_format($booking->total_price, 2) }}</td>
            <td>
                @if($booking->status == 'pending')
                <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>

                <form action="{{ route('owner.bookings.cancel', $booking->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                </form>
                @else
                <span class="text-muted">No actions</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No bookings yet.</p>
@endif
@endsection
