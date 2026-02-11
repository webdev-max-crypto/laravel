@extends('layouts.owner')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">My Bookings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->count())
        @php
            $statusClass = [
                'pending' => 'bg-warning text-dark',
                'approved' => 'bg-success',
                'cancelled' => 'bg-danger',
                'active' => 'bg-primary text-white',
                'expired' => 'bg-secondary text-white'
            ];
        @endphp

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Warehouse</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Owner Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->warehouse->name ?? '-' }}</td>
                    <td>{{ $booking->customer->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $statusClass[$booking->status] ?? 'bg-secondary' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>Rs. {{ number_format($booking->total_price, 2) }}</td>
                    <td>Rs. {{ number_format($booking->owner_amount ?? 0, 2) }}</td>
                    <td>{{ ucfirst($booking->payment_method ?? 'N/A') }}</td>
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
                        {{-- Actions for pending bookings --}}
                        @if($booking->status == 'pending')
                            <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm mb-1">Approve</button>
                            </form>

                            <form action="{{ route('owner.bookings.cancel', $booking->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm mb-1">Cancel</button>
                            </form>
                        @else
                            <span class="text-muted">No actions</span>
                        @endif

                        {{-- Optional: Withdraw / payout info --}}
                        @if($booking->payment_status === 'released')
                            <span class="badge bg-success mt-1">Available in Stripe</span>
                        @elseif($booking->payment_status === 'paid')
                            <span class="badge bg-warning mt-1">Waiting Admin Release</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No bookings yet.</p>
    @endif
</div>
@endsection
