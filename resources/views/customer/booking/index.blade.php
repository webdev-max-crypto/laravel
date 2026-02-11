@extends('layouts.customer')

@section('content')
@foreach($bookings as $b)
<div class="card p-3 mb-3">
    <h5>{{ $b->warehouse->name }}</h5>
    <p>Status: <strong>{{ ucfirst($b->status) }}</strong></p>
    <p>Total: Rs {{ $b->total_price }}</p>

    <a href="{{ route('customer.booking.invoice', $b->id) }}" target="_blank">
        View Invoice
    </a>
</div>
@endforeach
@endsection
