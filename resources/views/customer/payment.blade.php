@extends('layouts.customer')

@section('content')
<div style="max-width:650px;margin:auto;padding:25px;">
<div style="background:white;padding:30px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.15);">

<h2>Complete Payment</h2>

<p><strong>Warehouse:</strong> {{ $booking->warehouse->name }}</p>
<p><strong>Total Amount:</strong>
<span style="color:#16a34a;font-size:18px;">
Rs {{ $booking->total_price }}
</span></p>

<hr>

<form method="POST" enctype="multipart/form-data" action="{{ route('customer.payment.store',$booking->id) }}">
@csrf

<label class="mb-2">Upload Payment Slip</label>
<input type="file" name="payment_slip" class="form-control mb-3" required>

<button style="width:100%;padding:14px;background:#16a34a;color:white;border:none;border-radius:10px;font-size:17px;">
Submit Payment
</button>
</form>

@if($booking->payment_slip)
<p class="mt-3">
<a href="{{ asset('storage/'.$booking->payment_slip) }}" target="_blank">View Existing Payment Slip</a>
</p>
@endif

</div>
</div>
@endsection
