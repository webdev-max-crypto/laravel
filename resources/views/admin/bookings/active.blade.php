@extends('admin.layouts.app')
@section('content')
<h1>Active Bookings</h1>
@if($bookings->count())
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th><th>Warehouse</th><th>Customer</th><th>Status</th><th>Total</th><th>Payment</th><th>Payment Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($bookings as $b)
    <tr>
        <td>{{ $b->id }}</td>
        <td>{{ $b->warehouse->name ?? '-' }}</td>
        <td>{{ $b->customer->name ?? '-' }}</td>
        <td>{{ ucfirst($b->status) }}</td>
        <td>Rs. {{ number_format($b->total_price,2) }}</td>
        <td>{{ ucfirst($b->payment_method ?? 'N/A') }}</td>
        <td>{{ $b->payment_status==='paid' ? 'Paid' : 'Unpaid' }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
{{ $bookings->links() }}
@else
<p>No active bookings found.</p>
@endif
@endsection
