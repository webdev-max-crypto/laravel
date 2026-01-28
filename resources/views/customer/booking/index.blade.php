@extends('layouts.customer')

@section('content')
@foreach($bookings as $b)
<div class="card p-3 mb-3">
<h5>{{ $b->warehouse->name }}</h5>
<p>Status: <strong>Confirmed</strong></p>
<p>Total: Rs {{ $b->total_price }}</p>

@if($b->payment_slip)
<a href="{{ asset('storage/'.$b->payment_slip) }}" target="_blank">View Payment Slip</a>
@endif
</div>
@endforeach
@endsection
