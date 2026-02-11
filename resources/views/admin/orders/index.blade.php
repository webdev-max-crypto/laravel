@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Bookings / Orders Management</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        // Status badge classes
        $statusClass = [
            'pending' => 'bg-warning text-dark',
            'approved' => 'bg-success',
            'cancelled' => 'bg-danger',
            'active' => 'bg-primary text-white',
            'expired' => 'bg-secondary text-white'
        ];
    @endphp

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Owner</th>
                <th>Total Price</th>
                <th>Owner Amount</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->customer->name ?? '-' }}</td>
                <td>{{ $booking->owner->name ?? '-' }}</td>
                <td>Rs. {{ number_format($booking->total_price, 2) }}</td>
                <td>Rs. {{ number_format($booking->owner_amount ?? 0, 2) }}</td>
                <td>
                    <span class="badge {{ $statusClass[$booking->status] ?? 'bg-secondary' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    @if($booking->payment_status === 'unpaid')
                        <span class="text-danger">Unpaid</span>
                    @elseif($booking->payment_status === 'paid')
                        <span class="text-warning">Pending Release</span>
                    @elseif($booking->payment_status === 'released')
                        <span class="text-success">Released</span>
                    @elseif($booking->payment_status === 'cash')
                        <span class="text-info">Cash</span>
                    @elseif($booking->payment_status === 'escrow')
                        <span class="text-primary">Escrow</span>
                    @endif
                </td>
                <td>
                    {{-- Approve / Cancel Actions --}}
                    @if($booking->status == 'pending')
                        <form action="{{ route('admin.bookings.approved', $booking->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm mb-1">Approve</button>
                        </form>

                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm mb-1">Cancel</button>
                        </form>
                    @endif

                    {{-- Release to Owner --}}
                    @if($booking->payment_status === 'paid')
                        <form action="{{ route('admin.bookings.release', $booking->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm mb-1">Release to Owner</button>
                        </form>
                    @elseif($booking->payment_status === 'released')
                        <span class="text-success">Released</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No bookings found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
