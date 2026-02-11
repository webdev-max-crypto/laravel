@extends('layouts.customer')

@section('content')
<div style="max-width:800px;margin:auto;padding:25px;background:#fff;border-radius:10px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);">
    <h2>Invoice #{{ $booking->id }}</h2>
    <p><strong>Customer:</strong> {{ $booking->customer->name ?? '-' }}</p>
    <p><strong>Email:</strong> {{ $booking->customer->email ?? '-' }}</p>
    <p><strong>Phone:</strong> {{ $booking->customer->phone ?? '-' }}</p>
    <hr>
    <h4>Booking Details</h4>
    <p><strong>Warehouse:</strong> {{ $booking->warehouse->name ?? '-' }}</p>
    <p><strong>Start Date:</strong> {{ $booking->start_date }}</p>
    <p><strong>End Date:</strong> {{ $booking->end_date }}</p>
    <p><strong>Items:</strong> {{ $booking->items ?? '-' }}</p>
    <p><strong>Total Price:</strong> Rs {{ number_format($booking->total_price,2) }}</p>
    <p><strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}</p>
    <hr>
    @if($booking->payment_slip)
        <p><strong>Payment Slip:</strong> <a href="{{ asset('storage/'.$booking->payment_slip) }}" target="_blank">View</a></p>
    @endif
    <p>Thank you for booking with Smart Warehouse.</p>
</div>
@endsection
