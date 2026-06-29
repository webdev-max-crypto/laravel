@extends('admin.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }
    .back-btn { display: inline-flex; align-items: center; gap: 6px; color: var(--slate); font-size: 13px; font-weight: 600; text-decoration: none; transition: color 0.15s; }
    .back-btn:hover { color: var(--blue); }

    .release-layout { display: grid; grid-template-columns: 1fr 380px; gap: 20px; align-items: start; max-width: 900px; }

    /* Info card */
    .info-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 16px; }
    .card-header { padding: 14px 20px; background: var(--sky); border-bottom: 1px solid var(--sky2); display: flex; align-items: center; gap: 10px; }
    .card-icon { width: 32px; height: 32px; background: var(--blue); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .card-body { padding: 20px; }

    .info-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 13.5px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: var(--slate); font-weight: 600; }
    .info-value { font-weight: 700; color: var(--ink); }
    .info-value.price { color: var(--blue); font-size: 18px; font-weight: 800; }
    .info-value.green { color: #065f46; font-weight: 800; }

    /* Amount banner */
    .amount-banner {
        background: linear-gradient(130deg, #1e40af, #2563eb);
        border-radius: 12px; padding: 20px 22px; margin-bottom: 16px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 6px 20px rgba(37,99,235,0.2);
    }
    .amount-label { font-size: 12px; color: rgba(255,255,255,0.7); font-weight: 600; margin-bottom: 4px; }
    .amount-value { font-size: 28px; font-weight: 800; color: #fff; }
    .amount-icon  { font-size: 36px; opacity: 0.2; }

    /* Form card */
    .form-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); position: sticky; top: 80px; }

    .form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
    .form-group label { font-size: 12.5px; font-weight: 700; color: var(--ink); }
    .form-control { width: 100%; padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.18s; background: var(--white); }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    /* File upload */
    .file-upload { border: 2px dashed var(--border); border-radius: 10px; padding: 18px; text-align: center; cursor: pointer; transition: all 0.18s; position: relative; margin-bottom: 14px; }
    .file-upload:hover { border-color: var(--blue); background: var(--sky); }
    .file-upload input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .file-upload-icon { font-size: 26px; margin-bottom: 5px; }
    .file-upload-text { font-size: 12.5px; font-weight: 600; color: var(--slate); }
    .file-upload-sub  { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* Stripe card element */
    #card-element { padding: 12px 14px; border: 1.5px solid var(--border); border-radius: 8px; background: var(--white); margin-bottom: 6px; }
    #card-errors  { font-size: 12.5px; color: #ef4444; font-weight: 600; margin-bottom: 10px; }

    /* Method badge */
    .method-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; }
    .method-jazz   { background: rgba(245,158,11,0.1); color: #92400e; border: 1px solid rgba(245,158,11,0.3); }
    .method-stripe { background: var(--sky); color: var(--blue); border: 1px solid var(--sky2); }

    .info-box { background: var(--sky); border: 1px solid var(--sky2); border-radius: 8px; padding: 12px 14px; font-size: 12.5px; color: #1e40af; margin-bottom: 14px; line-height: 1.6; }

    .btn-submit { width: 100%; background: var(--blue); color: #fff; padding: 13px; border-radius: 9px; border: none; font-size: 14px; font-weight: 800; cursor: pointer; transition: all 0.18s; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: var(--blue2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.25); }

    @media(max-width:700px) { .release-layout { grid-template-columns: 1fr; } .form-card { position: static; } }
</style>

<div class="page-header">
    <div>
        <h2>💸 Release Payment</h2>
        <p>Release the held payment to the warehouse owner.</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="back-btn">← Back to Orders</a>
</div>

<div class="release-layout">

    {{-- Left: Booking Info --}}
    <div>
        {{-- Amount Banner --}}
        <div class="amount-banner">
            <div>
                <div class="amount-label">Amount to Release</div>
                <div class="amount-value">Rs {{ number_format($booking->total_price, 0) }}</div>
            </div>
            <div class="amount-icon">💸</div>
        </div>

        {{-- Booking Details --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">📋</div>
                <h3>Booking Details</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Booking ID</span>
                    <span class="info-value" style="font-family:monospace;color:var(--blue);">#{{ $booking->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Amount</span>
                    <span class="info-value price">Rs {{ number_format($booking->total_price, 0) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method</span>
                    @if($booking->warehouse->preferred_payment_method === 'jazzcash')
                        <span class="method-badge method-jazz">📱 JazzCash</span>
                    @else
                        <span class="method-badge method-stripe">💳 Stripe</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Owner Details --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">🏭</div>
                <h3>Owner & Warehouse</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Owner</span>
                    <span class="info-value">{{ optional($booking->warehouse->owner)->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Warehouse</span>
                    <span class="info-value">{{ optional($booking->warehouse)->name ?? '—' }}</span>
                </div>
                @if($booking->warehouse->preferred_payment_method === 'jazzcash')
                    <div class="info-row">
                        <span class="info-label">JazzCash No.</span>
                        <span class="info-value green">{{ $booking->warehouse->jazzcash_number }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Release Form --}}
    <div class="form-card">
        <div class="card-header">
            <div class="card-icon">💸</div>
            <h3>
                @if($booking->warehouse->preferred_payment_method === 'jazzcash')
                    Release via JazzCash
                @else
                    Release via Stripe
                @endif
            </h3>
        </div>
        <div class="card-body">

            {{-- JazzCash Form --}}
            @if($booking->warehouse->preferred_payment_method === 'jazzcash')
                <div class="info-box">
                    📱 Send <strong>Rs {{ number_format($booking->total_price, 0) }}</strong> to JazzCash number <strong>{{ $booking->warehouse->jazzcash_number }}</strong>, then upload the payment proof below.
                </div>

                <form action="{{ route('admin.bookings.confirmRelease', $booking->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="method" value="jazzcash">

                    <div class="form-group">
                        <label>JazzCash Number</label>
                        <input type="text" name="owner_jazzcash" class="form-control"
                               value="{{ $booking->warehouse->jazzcash_number }}" required>
                    </div>

                    <div class="form-group">
                        <label>Upload Payment Proof <span style="color:#ef4444;">*</span></label>
                        <div class="file-upload">
                            <input type="file" name="payment_proof" required onchange="showFileName(this,'proof-name')">
                            <div class="file-upload-icon">📄</div>
                            <div class="file-upload-text" id="proof-name">Click to upload proof</div>
                            <div class="file-upload-sub">JPG, PNG, PDF</div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">✅ Mark as Released</button>
                </form>
            @endif

            {{-- Stripe Form --}}
            @if($booking->warehouse->preferred_payment_method === 'stripe')
                <div class="info-box">
                    💳 Process the Stripe payment and upload proof to confirm the release.
                </div>

                <form id="stripeForm" action="{{ route('admin.bookings.confirmRelease', $booking->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="method" value="stripe">

                    <div class="form-group">
                        <label>Card Details</label>
                        <div id="card-element"></div>
                        <div id="card-errors"></div>
                    </div>

                    <div class="form-group">
                        <label>Upload Payment Proof <span style="color:#ef4444;">*</span></label>
                        <div class="file-upload">
                            <input type="file" name="payment_proof" id="payment_proof_stripe" required onchange="showFileName(this,'proof-name-stripe')">
                            <div class="file-upload-icon">📄</div>
                            <div class="file-upload-text" id="proof-name-stripe">Click to upload proof</div>
                            <div class="file-upload-sub">JPG, PNG, PDF</div>
                        </div>
                    </div>

                    <button id="payButton" class="btn-submit">💳 Pay via Stripe</button>
                </form>
            @endif

        </div>
    </div>

</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    function showFileName(input, targetId) {
        if (input.files && input.files[0]) {
            const el = document.getElementById(targetId);
            el.textContent = input.files[0].name;
            el.style.color = 'var(--blue)';
        }
    }

    @if($booking->warehouse->preferred_payment_method === 'stripe')
    const stripe   = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const card     = elements.create('card', {
        style: { base: { fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '14px', color: '#0d1117' } }
    });
    card.mount('#card-element');

    document.getElementById('payButton').addEventListener('click', async function(e) {
        e.preventDefault();
        const proof = document.getElementById('payment_proof_stripe').files[0];
        if (!proof) { alert('Please upload payment proof!'); return; }

        const { paymentMethod, error } = await stripe.createPaymentMethod({ type: 'card', card: card });
        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            const form = document.getElementById('stripeForm');
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'payment_method_id'; input.value = paymentMethod.id;
            form.appendChild(input);
            form.submit();
        }
    });
    @endif
</script>

@endsection
