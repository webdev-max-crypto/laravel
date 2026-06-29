@extends('customer.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 22px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .page-header h2 { font-size:20px; font-weight:800; color:var(--ink); }
    .back-btn { display:inline-flex; align-items:center; gap:6px; color:var(--slate); font-size:13px; font-weight:600; text-decoration:none; transition:color 0.15s; }
    .back-btn:hover { color:var(--blue); }

    .booking-layout { display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start; }

    /* Warehouse info card */
    .wh-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
    .wh-img { width:100%; height:200px; object-fit:cover; display:block; }
    .wh-img-placeholder { width:100%; height:160px; background:var(--sky); display:flex; align-items:center; justify-content:center; font-size:48px; }
    .wh-body { padding:20px; }
    .wh-name { font-size:18px; font-weight:800; color:var(--ink); margin-bottom:12px; }
    .wh-info { display:flex; flex-direction:column; gap:8px; margin-bottom:14px; }
    .wh-row  { display:flex; justify-content:space-between; font-size:13px; }
    .wh-label { color:var(--slate); }
    .wh-value { font-weight:600; color:var(--ink); }
    .wh-price { font-weight:800; color:var(--blue); font-size:15px; }
    .doc-link { display:inline-flex; align-items:center; gap:5px; color:var(--blue); font-size:13px; font-weight:600; text-decoration:none; }
    .doc-link:hover { color:var(--blue2); }

    /* Form card */
    .form-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
    .form-card-header { padding:15px 20px; border-bottom:1px solid var(--border); background:var(--sky); display:flex; align-items:center; gap:10px; }
    .card-icon { width:32px; height:32px; background:var(--blue); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
    .form-card-header h3 { font-size:14px; font-weight:800; color:var(--ink); margin:0; }
    .form-card-body { padding:20px; }

    .form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
    .form-group label { font-size:12.5px; font-weight:700; color:var(--ink); }
    .form-control { width:100%; padding:10px 13px; border:1.5px solid var(--border); border-radius:8px; font-size:13.5px; font-family:'Plus Jakarta Sans',sans-serif; color:var(--ink); background:var(--white); outline:none; transition:border-color 0.18s, box-shadow 0.18s; }
    .form-control:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    textarea.form-control { resize:vertical; min-height:90px; }
    .field-hint { font-size:11.5px; color:var(--slate); }

    .btn-submit { width:100%; background:var(--blue); color:#fff; padding:12px; border-radius:9px; border:none; font-size:14px; font-weight:800; cursor:pointer; transition:all 0.18s; font-family:'Plus Jakarta Sans',sans-serif; display:flex; align-items:center; justify-content:center; gap:8px; }
    .btn-submit:hover { background:var(--blue2); transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.25); }

    @media(max-width:700px) { .booking-layout { grid-template-columns:1fr; } }
</style>

<div class="page-header">
    <div>
        <h2>📦 Book Warehouse</h2>
        <p style="font-size:13px;color:var(--slate);margin-top:4px;">Fill in your storage requirements below.</p>
    </div>
    <a href="{{ route('customer.dashboard') }}" class="back-btn">← Back to Dashboard</a>
</div>

<div class="booking-layout">

    {{-- Warehouse Info --}}
    <div class="wh-card">
        @if($warehouse->image)
            <img src="{{ asset('storage/'.$warehouse->image) }}" class="wh-img" alt="{{ $warehouse->name }}">
        @else
            <div class="wh-img-placeholder">🏢</div>
        @endif
        <div class="wh-body">
            <div class="wh-name">{{ $warehouse->name }}</div>
            <div class="wh-info">
                <div class="wh-row"><span class="wh-label">📍 Location</span><span class="wh-value">{{ $warehouse->location }}</span></div>
                <div class="wh-row"><span class="wh-label">📐 Size</span><span class="wh-value">{{ $warehouse->size }} sq ft</span></div>
                <div class="wh-row"><span class="wh-label">📞 Contact</span><span class="wh-value">{{ $warehouse->contact }}</span></div>
                <div class="wh-row"><span class="wh-label">💰 Price/Month</span><span class="wh-value wh-price">Rs {{ number_format($warehouse->price_per_month) }}</span></div>
            </div>
            @if($warehouse->description)
                <p style="font-size:13px;color:var(--slate);line-height:1.6;margin-bottom:12px;">{{ $warehouse->description }}</p>
            @endif
            @if($warehouse->property_doc)
                <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="doc-link">📄 View Property Document →</a>
            @endif
        </div>
    </div>

    {{-- Booking Form --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="card-icon">📋</div>
            <h3>Storage Requirements</h3>
        </div>
        <div class="form-card-body">
            <form action="{{ route('customer.warehouses.calculate', $warehouse->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Area Required (sq units) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="area" class="form-control" required min="1" step="1"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           placeholder="e.g. 100">
                </div>
                <div class="form-group">
                    <label>Number of Items <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="items" class="form-control" required min="1" step="1"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           placeholder="e.g. 50">
                </div>
                <div class="form-group">
                    <label>Items / Boxes Details</label>
                    <textarea name="items_detail" class="form-control"
                              oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')"
                              placeholder="e.g. cartons, fragile items, electronics, pallets..."></textarea>
                    <span class="field-hint">Letters only — describe what you're storing</span>
                </div>
                <div class="form-group" style="margin-bottom:20px;">
                    <label>Storage Duration (Months) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="months" class="form-control" required min="1" step="1"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           placeholder="e.g. 3">
                </div>
                <button type="submit" class="btn-submit">🧮 Calculate Total →</button>
            </form>
        </div>
    </div>

</div>
@endsection
