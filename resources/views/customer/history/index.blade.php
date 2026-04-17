@extends('customer.layouts.app')

@section('content')

<div style="max-width:900px;margin:auto;padding:25px;">

<h2>📜 Your Booking History</h2>

{{-- Payment Success Message --}}
@if(session('success'))
<div style="background:#d1fae5;padding:10px;margin-bottom:15px;color:#065f46;">
    {{ session('success') }}
</div>
@endif

{{-- Message after Online Payment --}}
@if(session('payment_done'))
<div style="background:#e0f2fe;padding:12px;margin-bottom:15px;color:#075985;font-weight:bold;">
    ✅ Payment received. Please check the <b>Goods Confirm</b> button from history when your goods are safely arrived to warehouse.
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
{{--<th style="padding:10px;border:1px solid #ddd;">QR Code</th>--}}
<th style="padding:10px;border:1px solid #ddd;">Booking Status</th>
<th style="padding:10px;border:1px solid #ddd;">Goods Confirm</th>
</tr>
</thead>

<tbody>

@foreach($bookings as $b)

<tr>

<td style="padding:10px;border:1px solid #ddd;">{{ $b->id }}</td>

<td style="padding:10px;border:1px solid #ddd;">
{{ $b->warehouse->name }}
</td>

<td style="padding:10px;border:1px solid #ddd;">
Rs {{ number_format($b->total_price) }}
</td>

<td style="padding:10px;border:1px solid #ddd;">

@if($b->payment_status == 'cash')
<span style="color:#16a34a;font-weight:bold;">Cash</span>

@elseif($b->payment_status == 'pending')

<span style="color:#d97706;font-weight:bold;">Pending</span>
<br>

<small style="color:#92400e;">
Please complete online payment to JazzCash: 
<strong>03167630754</strong> within 24 hours
</small>

@elseif($b->payment_status == 'paid')

<span style="color:#065f46;font-weight:bold;">Paid</span>

@endif

</td>

{{--<td style="padding:10px;border:1px solid #ddd;">

@if($b->qr_code)
<img src="{{ asset('storage/' . $b->qr_code) }}" width="100">
@else
-
@endif

</td>--}}

<td style="padding:10px;border:1px solid #ddd;">
{{ ucfirst($b->status) }}
</td>

<td style="padding:10px;border:1px solid #ddd;">

@if($b->goods_confirmed)

<span style="color:green;font-weight:bold;">
✅ Confirmed
</span>

@elseif($b->payment_status == 'paid')

<form action="{{ route('customer.goods.confirm',$b->id) }}" method="POST">
@csrf
<button style="background:#2563eb;color:white;padding:6px 12px;border:none;border-radius:4px;">
Confirm Goods
</button>
</form>

@else

-

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