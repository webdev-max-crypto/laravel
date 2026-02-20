@extends('admin.layouts.app')

@section('content')
<h2>Release Payment for Booking #{{ $booking->id }}</h2>

<p><strong>Owner:</strong> {{ $booking->warehouse->owner->name }}</p>
<p><strong>Warehouse:</strong> {{ $booking->warehouse->name }}</p>
<p><strong>Total Amount:</strong> Rs {{ number_format($booking->total_price,2) }}</p>
<p><strong>Payment Method:</strong> {{ ucfirst($booking->warehouse->preferred_payment_method) }}</p>

@if($booking->warehouse->preferred_payment_method == 'jazzcash')
    <p><strong>JazzCash Number:</strong> {{ $booking->warehouse->jazzcash_number }}</p>
    <p>Send the amount to this JazzCash number and then mark as released.</p>
    <form action="{{ route('admin.bookings.release', $booking->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Mark as Released</button>
    </form>
@endif

@if($booking->warehouse->preferred_payment_method == 'stripe')
    <label>Card Payment via Stripe:</label>
    <div id="card-element" style="padding:10px;border:1px solid #ccc;border-radius:6px;"></div>
    <div id="card-errors" role="alert" style="color:red;margin-top:8px;"></div>
    <button id="payButton" class="btn btn-success mt-2">Pay via Stripe</button>
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

    const {paymentMethod, error} = await stripe.createPaymentMethod({
        type: 'card',
        card: card,
    });

    if(error){
        document.getElementById('card-errors').textContent = error.message;
    } else {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.bookings.pay-stripe', $booking->id) }}";

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'payment_method_id';
        tokenInput.value = paymentMethod.id;
        form.appendChild(tokenInput);

        const intentInput = document.createElement('input');
        intentInput.type = 'hidden';
        intentInput.name = 'payment_intent_id';
        intentInput.value = "{{ $stripePaymentIntent->id }}";
        form.appendChild(intentInput);

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = "_token";
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
});
@endif
</script>
@endsection
