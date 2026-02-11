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

        <form action="{{ route('customer.payment.store', $booking->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
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
                Online payment: Complete using Stripe below.
            </div>

            <div id="stripePayment" style="display:none;margin-top:15px;">
                <!-- Stripe Elements placeholder -->
                <label>Card Details</label>
                <div id="card-element" style="padding:10px;border:1px solid #ccc;border-radius:6px;"></div>
                <div id="card-errors" role="alert" style="color:red;margin-top:8px;"></div>
            </div>

            <button type="submit" style="margin-top:15px;padding:12px;background:#16a34a;color:white;border:none;">
                Complete Order
            </button>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ env('STRIPE_KEY') }}"); // Stripe public key
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

function toggleOnlineNotice(){
    const method = document.getElementById('payment_method').value;
    const notice = document.getElementById('onlineNotice');
    const stripeDiv = document.getElementById('stripePayment');
    if(method === 'online'){
        notice.style.display = 'block';
        stripeDiv.style.display = 'block';
    } else {
        notice.style.display = 'none';
        stripeDiv.style.display = 'none';
    }
}
toggleOnlineNotice();

// Handle form submission for online payments
const form = document.getElementById('paymentForm');
form.addEventListener('submit', async (e) => {
    if(document.getElementById('payment_method').value === 'online'){
        e.preventDefault();
        const {paymentIntent, error} = await stripe.confirmCardPayment("{{ $paymentIntent->client_secret ?? '' }}", {
            payment_method: {
                card: card,
            }
        });
        if(error){
            document.getElementById('card-errors').textContent = error.message;
        } else {
            form.submit(); // continue with Laravel form submission (record payment)
        }
    }
});
</script>
@endsection
