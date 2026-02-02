@extends('layouts.customer')

@section('content')
<div style="max-width:760px;margin:auto;padding:25px;">
    <div style="background:white;padding:30px;border-radius:14px;
        box-shadow:0 6px 18px rgba(0,0,0,0.12);">

        <h2>Payment for Warehouse: {{ $booking->warehouse->name }}</h2>
        <p>Total Amount: Rs {{ number_format($booking->total_price) }}</p>

        @if(session('success'))
            <div style="background:#d1fae5;padding:10px;margin-bottom:15px;color:#065f46;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('customer.payment.store', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>Select Payment Method:</label>
            <select name="payment_method" id="payment_method" required onchange="toggleOnlineNotice()">
                <option value="">--Select--</option>
                <option value="cash" {{ old('payment_method')=='cash' ? 'selected' : '' }}>Cash</option>
                <option value="online" {{ old('payment_method')=='online' ? 'selected' : '' }}>Online</option>
            </select>

            <label>Upload Payment Slip (optional for cash):</label>
            <input type="file" name="payment_slip" accept=".jpg,.png,.pdf">

            <div id="onlineNotice" style="display:none;margin-top:10px;padding:10px;background:#fef3c7;color:#92400e;border-radius:6px;">
                Online payment account details: <br>
                JazzCash Account No: <strong>03167630754</strong> <br>
                Please complete the payment within 24 hours, otherwise your parcel cannot be picked up.
            </div>

            <button type="submit" style="margin-top:15px;padding:12px;background:#16a34a;color:white;border:none;">
                Complete Order
            </button>
        </form>
    </div>
</div>

<script>
    function toggleOnlineNotice(){
        const method = document.getElementById('payment_method').value;
        const notice = document.getElementById('onlineNotice');
        notice.style.display = method === 'online' ? 'block' : 'none';
    }
    // Call once on page load in case old input exists
    toggleOnlineNotice();
</script>
@endsection
