@extends('customer.layouts.app')

@section('content')
<h2>📜 Booking History</h2>

@if($bookings->isEmpty())
    <p>No bookings yet.</p>
@else
<table>
    <thead>


@section('content')
<div style="max-width:900px;margin:auto;padding:25px;">

    <h2>Your Booking History</h2>

    @if(session('success'))
        <div style="background:#d1fae5;padding:10px;margin-bottom:15px;color:#065f46;">
            {{ session('success') }}
        </div>
    @endif

    @if($bookings->count() > 0)
        <table style="width:100%;border-collapse:collapse;margin-top:20px;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:10px;border:1px solid #ddd;">Booking ID</th>
                    <th style="padding:10px;border:1px solid #ddd;">Warehouse</th>
                    <th style="padding:10px;border:1px solid #ddd;">Total Price</th>
                    <th style="padding:10px;border:1px solid #ddd;">Payment Status</th>
                    <th style="padding:10px;border:1px solid #ddd;">QR Code</th>
                    <th style="padding:10px;border:1px solid #ddd;">Booking Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                <tr>
                    <td style="padding:10px;border:1px solid #ddd;">{{ $b->id }}</td>
                    <td style="padding:10px;border:1px solid #ddd;">{{ $b->warehouse->name }}</td>
                    <td style="padding:10px;border:1px solid #ddd;">Rs {{ number_format($b->total_price) }}</td>
                    <td style="padding:10px;border:1px solid #ddd;">
                        @if($b->payment_status == 'cash')
                            <span style="color:#16a34a;font-weight:bold;">Cash</span>
                        @elseif($b->payment_status == 'pending')
                            <span style="color:#d97706;font-weight:bold;">Pending</span>
                            <br>
                            <small style="color:#92400e;">Please complete online payment to JazzCash: <strong>03167630754</strong> within 24 hours</small>
                        @elseif($b->payment_status == 'paid')
                            <span style="color:#065f46;font-weight:bold;">Paid</span>
                        @endif
                    </td>
                    <td style="padding:10px;border:1px solid #ddd;">
                        @if($b->qr_code)
                            <img src="{{ asset('storage/' . $b->qr_code) }}" alt="QR Code" width="100">
                        @else
                            -
                        @endif
                    </td>
                    <td style="padding:10px;border:1px solid #ddd;">{{ ucfirst($b->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No bookings yet.</p>
    @endif

</div>
@endsection
            <th>Booking ID</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
            <tr>
                <td>#{{ $booking->id }}</td>
                <td>{{ $booking->warehouse->name }}</td>
                <td>{{ $booking->created_at->format('d M Y') }}</td>
                <td>{{ $booking->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
