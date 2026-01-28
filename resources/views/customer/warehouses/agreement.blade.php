@extends('layouts.customer')

@section('content')
<div style="max-width:760px;margin:auto;padding:25px;">
<div style="background:white;padding:30px;border-radius:14px;
box-shadow:0 6px 18px rgba(0,0,0,0.12);">

<h2>Warehouse Storage Agreement</h2>

<p style="color:#374151;margin-top:15px;">
By confirming, you agree that:
</p>

<ul style="line-height:1.9;color:#4b5563;">
<li>Stored items are customer responsibility</li>
<li>No illegal or hazardous items allowed</li>
<li>Payment is non-refundable after approval</li>
<li>Warehouse owner is not liable for force majeure</li>
</ul>

<hr>

<form action="{{ route('customer.warehouses.finalConfirm',$warehouse->id) }}" method="POST">
@csrf

@foreach($data as $key => $value)
<input type="hidden" name="{{ $key }}" value="{{ $value }}">
@endforeach

<label style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
<input type="checkbox" required>
I agree to all terms & conditions
</label>

<button style="width:100%;padding:14px;background:#16a34a;
color:white;border:none;border-radius:10px;font-size:17px;">
Confirm Booking
</button>

</form>

</div>
</div>
@endsection
