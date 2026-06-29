@extends('customer.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom:22px; }
    .page-header h2 { font-size:20px; font-weight:800; color:var(--ink); }
    .page-header p  { font-size:13px; color:var(--slate); margin-top:4px; }

    .payment-layout { display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; max-width:860px; }

    .pay-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); margin-bottom:16px; }
    .card-header { padding:15px 20px; border-bottom:1px solid var(--border); background:var(--sky); display:flex; align-items:center; gap:10px; }
    .card-icon { width:32px; height:32px; background:var(--blue); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
    .card-header h3 { font-size:14px; font-weight:800; color:var(--ink); margin:0; }
    .card-body { padding:20px; }

    /* Amount banner */
    .amount-banner { background:linear-gradient(130deg,#1e40af,#2563eb); border-radius:14px; padding:22px 24px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 6px 20px rgba(37,99,235,0.2); }
    .amount-label { font-size:13px; color:rgba(255,255,255,0.7); font-weight:600; margin-bottom:4px; }
    .amount-value { font-size:32px; font-weight:800; color:#fff; }
    .amount-icon  { font-size:40px; opacity:0.3; }

    /* Price rows */
    .price-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border); font-size:13.5px; }
    .price-row:last-child { border-bottom:none; }
    .price-row .label { color:var(--slate); font-weight:600; }
    .price-row .value { font-weight:700; color:var(--ink); }

    /* Payment method toggle */
    .method-toggle { display:flex; gap:10px; margin-bottom:16px; }
    .method-opt { flex:1; padding:12px 10px; border:1.5px solid var(--border); border-radius:10px; text-align:center; cursor:pointer; transition:all 0.18s; font-size:13px; font-weight:700; color:var(--slate); background:var(--white); }
    .method-opt:hover { border-color:var(--blue); color:var(--blue); background:var(--sky); }
    .method-opt.selected { border-color:var(--blue); background:var(--sky); color:var(--blue); }
    .method-opt .m-icon { font-size:22px; display:block; margin-bottom:4px; }

    /* Online sub-methods */
    .online-methods { display:flex; gap:10px; margin-bottom:14px; }
    .online-opt { flex:1; padding:10px; border:1.5px solid var(--border); border-radius:9px; text-align:center; cursor:pointer; transition:all 0.18s; font-size:12.5px; font-weight:700; color:var(--slate); background:var(--white); }
    .online-opt:hover { border-color:var(--blue); color:var(--blue); background:var(--sky); }
    .online-opt.selected { border-color:var(--blue); background:var(--sky); color:var(--blue); }

    /* Info boxes */
    .info-box { border-radius:10px; padding:14px 16px; font-size:13px; line-height:1.6; margin-bottom:14px; }
    .info-box.yellow { background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.25); color:#92400e; }
    .info-box.blue   { background:var(--sky); border:1px solid var(--sky2); color:#1e40af; }
    .info-box.red    { background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.2); color:#991b1b; }

    /* File upload */
    .file-upload { border:2px dashed var(--border); border-radius:10px; padding:16px; text-align:center; cursor:pointer; transition:all 0.18s; position:relative; margin-bottom:14px; }
    .file-upload:hover { border-color:var(--blue); background:var(--sky); }
    .file-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .file-upload-icon { font-size:24px; margin-bottom:5px; }
    .file-upload-text { font-size:12.5px; font-weight:600; color:var(--slate); }
    .file-upload-sub  { font-size:11px; color:#94a3b8; margin-top:2px; }

    /* Stripe card element */
    #card-element { padding:12px 14px; border:1.5px solid var(--border); border-radius:8px; background:var(--white); transition:border-color 0.18s; }
    #card-element.focused { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    #card-errors { font-size:12.5px; color:#ef4444; font-weight:600; margin-top:6px; }

    .form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
    .form-group label { font-size:12.5px; font-weight:700; color:var(--ink); }

    .btn-submit { width:100%; background:var(--blue); color:#fff; padding:13px; border-radius:9px; border:none; font-size:14px; font-weight:800; cursor:pointer; transition:all 0.18s; font-family:'Plus Jakarta Sans',sans-serif; display:flex; align-items:center; justify-content:center; gap:8px; margin-top:4px; }
    .btn-submit:hover { background:var(--blue2); transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.25); }

    /* Order summary sticky */
    .order-summary { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); position:sticky; top:80px; }
    .summary-total { background:var(--sky); padding:16px 20px; display:flex; justify-content:space-between; align-items:center; border-top:2px solid var(--sky2); }
    .summary-total .label { font-size:15px; font-weight:800; color:var(--ink); }
    .summary-total .value { font-size:20px; font-weight:800; color:var(--blue); }

    @media(max-width:700px) { .payment-layout { grid-template-columns:1fr; } .order-summary { position:static; } }
</style>

<div class="page-header">
    <h2>💳 Complete Payment</h2>
    <p>Choose your payment method and complete your booking.</p>
</div>

@if($booking->total_price < $minStripePKR)
    <div class="info-box red" style="max-width:860px;margin-bottom:16px;">
        ⚠️ Stripe cannot process payments below PKR {{ number_format($minStripePKR) }}. Please choose Cash or JazzCash.
    </div>
@endif

<div class="payment-layout">

    {{-- Left: Payment Form --}}
    <div>
        {{-- Amount Banner --}}
        <div class="amount-banner">
            <div>
                <div class="amount-label">Final Payable Amount</div>
                <div class="amount-value">Rs {{ number_format($booking->total_price) }}</div>
            </div>
            <div class="amount-icon">💰</div>
        </div>

        <div class="pay-card">
            <div class="card-header">
                <div class="card-icon">💳</div>
                <h3>Select Payment Method</h3>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="info-box blue" style="margin-bottom:14px;">✅ {{ session('success') }}</div>
                @endif

                <form action="{{ route('customer.payment.store', $booking->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                    @csrf

                    {{-- Method Toggle --}}
                    <div class="method-toggle">
                        <div class="method-opt" onclick="selectMethod('cash', this)">
                            <span class="m-icon">💵</span> Cash
                        </div>
                        <div class="method-opt" onclick="selectMethod('online', this)">
                            <span class="m-icon">📱</span> Online
                        </div>
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method" value="">

                    {{-- Cash Info --}}
                    <div id="cashSection" style="display:none;">
                        <div class="info-box yellow">
                            💵 <strong>Cash Payment:</strong> Pay directly at the warehouse. Upload your payment receipt below.
                        </div>
                        <div class="form-group">
                            <label>Upload Payment Slip <span style="color:#ef4444;">*</span></label>
                            <div class="file-upload">
                                <input type="file" name="payment_slip" accept=".jpg,.png,.pdf,.jpeg" id="paymentSlip" onchange="showFileName(this,'slip-name')">
                                <div class="file-upload-icon">📄</div>
                                <div class="file-upload-text" id="slip-name">Click to upload slip</div>
                                <div class="file-upload-sub">JPG, PNG, PDF</div>
                            </div>
                        </div>
                    </div>

                    {{-- Online Section --}}
                    <div id="onlineSection" style="display:none;">
                        <div class="online-methods">
                            <div class="online-opt" onclick="selectOnline('stripe', this)">
                                <span style="font-size:18px;display:block;margin-bottom:3px;">💳</span> Stripe
                            </div>
                            <div class="online-opt" onclick="selectOnline('jazzcash', this)">
                                <span style="font-size:18px;display:block;margin-bottom:3px;">📱</span> JazzCash
                            </div>
                        </div>
                        <input type="hidden" name="online_method" id="online_method" value="">

                        {{-- Stripe --}}
                        <div id="stripeSection" style="display:none;">
                            <div class="form-group">
                                <label>Card Details</label>
                                <div id="card-element"></div>
                                <div id="card-errors"></div>
                            </div>
                        </div>

                        {{-- JazzCash --}}
                        <div id="jazzcashSection" style="display:none;">
                            <div class="info-box yellow">
                                📱 <strong>JazzCash Payment:</strong> Send <strong>Rs {{ number_format($booking->total_price) }}</strong> to <strong>03009650977</strong> then upload your payment slip below.
                            </div>
                            <div class="form-group">
                                <label>Upload Payment Slip <span style="color:#ef4444;">*</span></label>
                                <div class="file-upload">
                                    <input type="file" name="payment_slip" accept=".jpg,.png,.pdf,.jpeg" id="paymentSlipOnline" onchange="showFileName(this,'slip-name-online')">
                                    <div class="file-upload-icon">📄</div>
                                    <div class="file-upload-text" id="slip-name-online">Click to upload slip</div>
                                    <div class="file-upload-sub">JPG, PNG, PDF</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn" style="display:none;">
                        🚀 Complete Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Order Summary --}}
    <div class="order-summary">
        <div class="card-header">
            <div class="card-icon">📋</div>
            <h3>Order Summary</h3>
        </div>
        <div style="padding:16px 20px;">
            <div class="price-row"><span class="label">🏢 Warehouse</span><span class="value" style="font-size:13px;">{{ $booking->warehouse->name }}</span></div>
            <div class="price-row"><span class="label">Base Amount</span><span class="value">Rs {{ number_format($booking->total_price - ($booking->rider_fee ?? 0)) }}</span></div>
            @if($booking->delivery_option == 'rider')
                <div class="price-row"><span class="label">🛵 Rider Fee</span><span class="value">Rs {{ number_format($booking->rider_fee) }}</span></div>
            @endif
        </div>
        <div class="summary-total">
            <span class="label">Total</span>
            <span class="value">Rs {{ number_format($booking->total_price) }}</span>
        </div>
        <div style="padding:14px 20px;">
            <div class="info-box blue" style="margin:0;font-size:12px;">
                🔒 Your payment is held securely in escrow and released to the owner only after you confirm goods arrival.
            </div>
        </div>
    </div>

</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe   = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const card     = elements.create('card', { style: { base: { fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '14px', color: '#0d1117' } } });
    card.mount('#card-element');
    card.on('focus', () => document.getElementById('card-element').classList.add('focused'));
    card.on('blur',  () => document.getElementById('card-element').classList.remove('focused'));

    function selectMethod(method, el) {
        document.querySelectorAll('.method-opt').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('payment_method').value = method;
        document.getElementById('cashSection').style.display   = method === 'cash'   ? 'block' : 'none';
        document.getElementById('onlineSection').style.display = method === 'online' ? 'block' : 'none';
        document.getElementById('submitBtn').style.display = method ? 'flex' : 'none';
    }

    function selectOnline(method, el) {
        document.querySelectorAll('.online-opt').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('online_method').value = method;
        document.getElementById('stripeSection').style.display   = method === 'stripe'   ? 'block' : 'none';
        document.getElementById('jazzcashSection').style.display = method === 'jazzcash' ? 'block' : 'none';
    }

    function showFileName(input, targetId) {
        if (input.files && input.files[0]) {
            const el = document.getElementById(targetId);
            el.textContent = input.files[0].name;
            el.style.color = 'var(--blue)';
        }
    }

    const form = document.getElementById('paymentForm');
    form.addEventListener('submit', async function(e) {
        const paymentMethod = document.getElementById('payment_method').value;
        const onlineMethod  = document.getElementById('online_method').value;
        const slip = document.getElementById('paymentSlip');
        const slipOnline = document.getElementById('paymentSlipOnline');

        if (paymentMethod === 'cash' && !slip.files.length) {
            e.preventDefault();
            alert('Please upload your payment slip.');
            return false;
        }
        if (paymentMethod === 'online' && onlineMethod === 'jazzcash' && !slipOnline.files.length) {
            e.preventDefault();
            alert('Please upload your JazzCash payment slip.');
            return false;
        }
        if (paymentMethod === 'online' && onlineMethod === 'stripe') {
            e.preventDefault();
            const { paymentIntent, error } = await stripe.confirmCardPayment("{{ $paymentIntent->client_secret ?? '' }}", {
                payment_method: { card: card }
            });
            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                form.submit();
            }
        }
    });
</script>
@endsection
