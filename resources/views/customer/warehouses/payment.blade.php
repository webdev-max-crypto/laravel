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

        @if($booking->total_price < $minStripePKR)
            <div style="background:#fde68a;padding:10px;margin-bottom:15px;color:#92400e;border-radius:6px;">
                Stripe cannot process payments below PKR {{ $minStripePKR }}. Please choose Cash or JazzCash.
            </div>
        @endif

        <form action="{{ route('customer.payment.store', $booking->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
            @csrf

            <label>Select Payment Method:</label>
            <select name="payment_method" id="payment_method" required onchange="toggleOnlineOptions()">
                <option value="">--Select--</option>
                <option value="cash" {{ old('payment_method')=='cash' ? 'selected' : '' }}>Cash</option>
                <option value="online" {{ old('payment_method')=='online' ? 'selected' : '' }}>Online</option>
            </select>

            <label>Upload Payment Slip:</label>
            <input type="file" name="payment_slip" accept=".jpg,.png,.pdf,.jpeg" id="paymentSlip">

            <div id="onlineOptions" style="display:none;margin-top:10px;padding:10px;background:#fef3c7;color:#92400e;border-radius:6px;">
                <label>Select Online Payment:</label>
                <select id="online_method" name="online_method" onchange="toggleOnlineMethod()">
                    <option value="">--Select--</option>
                    <option value="stripe" {{ old('online_method')=='stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="jazzcash" {{ old('online_method')=='jazzcash' ? 'selected' : '' }}>JazzCash</option>
                </select>

                <div id="stripePayment" style="display:none;margin-top:10px;">
                    <label>Card Details</label>
                    <div id="card-element" style="padding:10px;border:1px solid #ccc;border-radius:6px;"></div>
                    <div id="card-errors" role="alert" style="color:red;margin-top:8px;"></div>
                </div>

                <div id="jazzcashInfo" style="display:none;margin-top:10px;padding:10px;background:#fef3c7;color:#92400e;border-radius:6px;">
                    <strong>Pay via JazzCash:</strong> Send the total amount to <strong>03009650977</strong> (Admin).<br>
                    Upload payment slip above to confirm.
                </div>
            </div>

            <button type="submit" style="margin-top:15px;padding:12px;background:#16a34a;color:white;border:none;">
                Complete Order
            </button>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ env('STRIPE_KEY') }}");
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

function toggleOnlineOptions(){
    const method = document.getElementById('payment_method').value;
    const options = document.getElementById('onlineOptions');
    if(method === 'online'){
        options.style.display = 'block';
        toggleOnlineMethod();
    } else {
        options.style.display = 'none';
        document.getElementById('stripePayment').style.display = 'none';
        document.getElementById('jazzcashInfo').style.display = 'none';
    }
}

function toggleOnlineMethod(){
    const method = document.getElementById('online_method').value;
    document.getElementById('stripePayment').style.display = method==='stripe' ? 'block' : 'none';
    document.getElementById('jazzcashInfo').style.display = method==='jazzcash' ? 'block' : 'none';
}

// Form validation
const form = document.getElementById('paymentForm');
form.addEventListener('submit', async function(e){
    const paymentMethod = document.getElementById('payment_method').value;
    const onlineMethod = document.getElementById('online_method').value;
    const slip = document.getElementById('paymentSlip');

    if(paymentMethod==='cash' || (paymentMethod==='online' && onlineMethod==='jazzcash')){
        if(!slip.files.length){
            e.preventDefault();
            alert('Please upload payment slip to complete the order.');
            slip.focus();
            return false;
        }
    }

    if(paymentMethod==='online' && onlineMethod==='stripe'){
        e.preventDefault();
        if(!{{ $booking->total_price }} || {{ $booking->total_price }} < {{ $minStripePKR }}){
            alert('Stripe cannot process this amount. Please choose JazzCash or Cash.');
            return false;
        }

        const {paymentIntent, error} = await stripe.confirmCardPayment("{{ $paymentIntent->client_secret ?? '' }}", {
            payment_method: { card: card }
        });

        if(error){
            document.getElementById('card-errors').textContent = error.message;
        } else {
            form.submit();
        }
    }
});

document.addEventListener('DOMContentLoaded', function(){
    toggleOnlineOptions();
    document.getElementById('online_method').addEventListener('change', toggleOnlineMethod);
});
</script>
@endsection
