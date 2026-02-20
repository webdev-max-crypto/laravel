@extends('admin.layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<h2>Release Payment for Booking #{{ $booking->id }}</h2>

<p><strong>Owner:</strong> {{ $booking->warehouse->owner->name }}</p>
<p><strong>Warehouse:</strong> {{ $booking->warehouse->name }}</p>
<p><strong>Total Amount:</strong> Rs {{ number_format($booking->total_price,2) }}</p>
<p><strong>Payment Method:</strong> {{ ucfirst($booking->warehouse->preferred_payment_method) }}</p>

{{-- JazzCash Flow --}}
@if($booking->warehouse->preferred_payment_method === 'jazzcash')
    <p><strong>JazzCash Number:</strong> {{ $booking->warehouse->jazzcash_number }}</p>
    <p>Send the amount to this JazzCash number and upload proof of payment.</p>

    <form action="{{ route('admin.bookings.confirmRelease', $booking->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="method" value="jazzcash">

        <div class="mb-3">
            <label for="owner_jazzcash" class="form-label">JazzCash Number</label>
            <input type="text" name="owner_jazzcash" id="owner_jazzcash" class="form-control" value="{{ $booking->warehouse->jazzcash_number }}" required>
        </div>

        <div class="mb-3">
            <label for="payment_proof" class="form-label">Upload Payment Proof <span class="text-danger">*</span></label>
            <input type="file" name="payment_proof" id="payment_proof" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Mark as Released</button>
    </form>
@endif

{{-- Stripe Flow --}}
@if($booking->warehouse->preferred_payment_method === 'stripe')
    <p>Pay via Stripe and upload proof of payment.</p>
    <form id="stripeForm" action="{{ route('admin.bookings.confirmRelease', $booking->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="method" value="stripe">

        <div class="mb-3">
            <label for="payment_proof_stripe" class="form-label">Upload Payment Proof <span class="text-danger">*</span></label>
            <input type="file" name="payment_proof" id="payment_proof_stripe" class="form-control" required>
        </div>

        <label>Card Payment via Stripe:</label>
        <div id="card-element" style="padding:10px;border:1px solid #ccc;border-radius:6px;"></div>
        <div id="card-errors" role="alert" style="color:red;margin-top:8px;"></div>

        <button id="payButton" class="btn btn-success mt-2">Pay via Stripe</button>
    </form>
@endif

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ env('STRIPE_KEY') }}");
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

const payButton = document.getElementById('payButton');
@if($booking->warehouse->preferred_payment_method === 'stripe')
payButton.addEventListener('click', async function(e){
    e.preventDefault();

    const paymentProof = document.getElementById('payment_proof_stripe').files[0];
    if(!paymentProof){
        alert('Please upload payment proof!');
        return;
    }

    const {paymentMethod, error} = await stripe.createPaymentMethod({
        type: 'card',
        card: card,
    });

    if(error){
        document.getElementById('card-errors').textContent = error.message;
    } else {
        // Submit Stripe form normally with uploaded file
        const form = document.getElementById('stripeForm');

        // Create a hidden input for Stripe Payment Method ID
        let tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'payment_method_id';
        tokenInput.value = paymentMethod.id;
        form.appendChild(tokenInput);

        form.submit();
    }
});
@endif
</script>

@endsection