@extends('customer.layouts.app')

@section('content')
<h2>📜 Booking History</h2>

@if($bookings->isEmpty())
    <p>No bookings yet.</p>
@else
<table>
    <thead>
        <tr>
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
