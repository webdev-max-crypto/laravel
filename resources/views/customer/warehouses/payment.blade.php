@extends('layouts.customer')

@section('content')
<div style="max-width:760px;margin:auto;padding:25px;">
    <div style="background:white;padding:30px;border-radius:14px;
        box-shadow:0 6px 18px rgba(0,0,0,0.12);">

        <h2>Payment for Warehouse: {{ $booking->warehouse->name }}</h2>
        <p>Total Amount: Rs {{ number_format($booking->total_price) }}</p>

        <form action="{{ route('customer.payment.store', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>Select Payment Method:</label>
            <select name="payment_method" required>
                <option value="">--Select--</option>
                <option value="cash">Cash</option>
                <option value="online">Online</option>
            </select>

            <label>Upload Payment Slip (optional for cash):</label>
            <input type="file" name="payment_slip" accept=".jpg,.png,.pdf">

            <button type="submit" style="margin-top:15px;padding:12px;background:#16a34a;color:white;border:none;">
                Submit Payment
            </button>
        </form>
    </div>
</div>
@endsection
