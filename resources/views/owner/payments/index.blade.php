@extends('layouts.owner')

@section('content')
<h2>Owner Payments</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Warehouse</th>
            <th>Customer</th>
            <th>Total Price (PKR)</th>
            <th>Owner Amount</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->warehouse->name }}</td>
            <td>{{ $booking->customer->name }}</td>
            <td>{{ number_format($booking->total_price,2) }}</td>
            <td>{{ number_format($booking->owner_amount,2) }}</td>
            <td>{{ ucfirst($booking->warehouse->preferred_payment_method) }}</td>
            <td>
                @if($booking->payment_status == 'paid')
                    <span class="badge bg-success">Received</span>
                @elseif($booking->payment_status == 'unpaid' || $booking->payment_status == 'escrow')
                    <span class="badge bg-warning">Pending from Admin</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($booking->payment_status) }}</span>
                @endif
            </td>
            <td>
                @if($booking->payment_status != 'paid')
                    @if($booking->warehouse->preferred_payment_method == 'stripe')
                        <form method="POST" action="{{ route('owner.payments.stripe', $booking->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-primary">Pay via Stripe</button>
                        </form>
                    @elseif($booking->warehouse->preferred_payment_method == 'jazzcash')
                        <form method="POST" action="{{ route('owner.payments.jazzcash', $booking->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-info">Pay via JazzCash</button>
                        </form>
                    @endif
                @else
                    <span class="text-success">Completed</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
