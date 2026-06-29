@extends('customer.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom:22px; }
    .page-header h2 { font-size:20px; font-weight:800; color:var(--ink); }
    .page-header p  { font-size:13px; color:var(--slate); margin-top:4px; }
    .back-btn { display:inline-flex; align-items:center; gap:6px; color:var(--slate); font-size:13px; font-weight:600; text-decoration:none; transition:color 0.15s; margin-bottom:20px; }
    .back-btn:hover { color:var(--blue); }

    .summary-layout { display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start; max-width:900px; }

    /* Summary card */
    .summary-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
    .card-header { padding:15px 20px; border-bottom:1px solid var(--border); background:var(--sky); display:flex; align-items:center; gap:10px; }
    .card-icon { width:32px; height:32px; background:var(--blue); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
    .card-header h3 { font-size:14px; font-weight:800; color:var(--ink); margin:0; }
    .card-body { padding:20px; }

    .info-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--border); font-size:13.5px; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:var(--slate); font-weight:600; }
    .info-value { font-weight:700; color:var(--ink); }
    .info-value.blue { color:var(--blue); }

    .items-detail-box { background:var(--sky); border:1px solid var(--sky2); border-radius:8px; padding:12px 14px; font-size:13px; color:var(--ink); line-height:1.6; margin-top:12px; }

    /* Price card */
    .price-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); position:sticky; top:80px; }

    .price-row { display:flex; justify-content:space-between; align-items:center; padding:12px 20px; border-bottom:1px solid var(--border); font-size:13.5px; }
    .price-row:last-child { border-bottom:none; }
    .price-row .label { color:var(--slate); font-weight:600; }
    .price-row .value { font-weight:700; color:var(--ink); }
    .price-total { background:var(--sky); padding:16px 20px; display:flex; justify-content:space-between; align-items:center; border-top:2px solid var(--sky2); }
    .price-total .label { font-size:15px; font-weight:800; color:var(--ink); }
    .price-total .value { font-size:20px; font-weight:800; color:var(--blue); }

    /* Delivery toggle */
    .delivery-options { display:flex; gap:10px; margin:16px 20px; }
    .delivery-opt { flex:1; padding:11px; border:1.5px solid var(--border); border-radius:10px; text-align:center; cursor:pointer; transition:all 0.18s; font-size:13px; font-weight:700; color:var(--slate); background:var(--white); }
    .delivery-opt:hover { border-color:var(--blue); color:var(--blue); background:var(--sky); }
    .delivery-opt.selected { border-color:var(--blue); background:var(--sky); color:var(--blue); }
    .delivery-opt .d-icon { font-size:20px; display:block; margin-bottom:4px; }

    .btn-submit { width:calc(100% - 40px); margin:0 20px 20px; background:var(--blue); color:#fff; padding:13px; border-radius:9px; border:none; font-size:14px; font-weight:800; cursor:pointer; transition:all 0.18s; font-family:'Plus Jakarta Sans',sans-serif; display:flex; align-items:center; justify-content:center; gap:8px; }
    .btn-submit:hover { background:var(--blue2); transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.25); }

    @media(max-width:700px) { .summary-layout { grid-template-columns:1fr; } .price-card { position:static; } }
</style>

<a href="{{ url()->previous() }}" class="back-btn">← Back</a>

<div class="page-header">
    <h2>📋 Booking Summary</h2>
    <p>Review your booking details before confirming.</p>
</div>

<div class="summary-layout">

    {{-- Left: Details --}}
    <div>
        <div class="summary-card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="card-icon">🏢</div>
                <h3>Warehouse Details</h3>
            </div>
            <div class="card-body">
                <div class="info-row"><span class="info-label">Warehouse</span><span class="info-value">{{ $warehouse->name }}</span></div>
                <div class="info-row"><span class="info-label">📍 Location</span><span class="info-value">{{ $warehouse->location }}</span></div>
                <div class="info-row"><span class="info-label">💰 Price/Month</span><span class="info-value blue">Rs {{ number_format($warehouse->price_per_month) }}</span></div>
            </div>
        </div>

        <div class="summary-card">
            <div class="card-header">
                <div class="card-icon">📦</div>
                <h3>Storage Requirements</h3>
            </div>
            <div class="card-body">
                <div class="info-row"><span class="info-label">📐 Area Required</span><span class="info-value">{{ $data['area'] }} sq units</span></div>
                <div class="info-row"><span class="info-label">📦 Items Count</span><span class="info-value">{{ $data['items'] }}</span></div>
                <div class="info-row"><span class="info-label">🗓️ Duration</span><span class="info-value">{{ $data['months'] }} month(s)</span></div>
                @if(!empty($data['items_detail']))
                    <div style="margin-top:4px;">
                        <span class="info-label" style="font-size:13px;">📝 Items Detail</span>
                        <div class="items-detail-box">{{ $data['items_detail'] }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Price & Confirm --}}
    <div class="price-card">
        <div class="card-header" style="padding:15px 20px;">
            <div class="card-icon">💳</div>
            <h3>Order Summary</h3>
        </div>

        <div class="price-row"><span class="label">Base Total</span><span class="value">Rs {{ number_format($total) }}</span></div>
        <div class="price-row"><span class="label">Rider Fee</span><span class="value" id="riderFeeDisplay">Rs 0</span></div>

        {{-- Delivery Options --}}
        <div style="padding:0 20px 4px;font-size:12px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.8px;">Delivery Option</div>
        <div class="delivery-options">
            <div class="delivery-opt selected" onclick="selectDelivery('self', this)">
                <span class="d-icon">🚶</span> Self Pickup
            </div>
            <div class="delivery-opt" onclick="selectDelivery('rider', this)">
                <span class="d-icon">🛵</span> Rider (+Rs 200)
            </div>
        </div>

        <div class="price-total">
            <span class="label">Final Total</span>
            <span class="value" id="finalTotalDisplay">Rs {{ number_format($total) }}</span>
        </div>

        <form action="{{ route('customer.warehouses.agreement', $warehouse->id) }}" method="POST">
            @csrf
            @foreach($data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="hidden" name="delivery_option" id="deliveryInput" value="self">
            <input type="hidden" name="total_price" id="finalTotalInput" value="{{ $total }}">
            <button type="submit" class="btn-submit">✅ Agree & Continue →</button>
        </form>
    </div>

</div>

<script>
    const baseTotal   = {{ $total }};
    const riderCharge = 200;

    function selectDelivery(method, el) {
        document.querySelectorAll('.delivery-opt').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('deliveryInput').value = method;
        const rider = method === 'rider' ? riderCharge : 0;
        const final = baseTotal + rider;
        document.getElementById('riderFeeDisplay').textContent  = 'Rs ' + rider;
        document.getElementById('finalTotalDisplay').textContent = 'Rs ' + final.toLocaleString();
        document.getElementById('finalTotalInput').value = final;
    }
</script>
@endsection
