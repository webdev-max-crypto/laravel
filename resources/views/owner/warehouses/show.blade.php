@extends('layouts.owner')

@section('content')
<style>
    .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .back-btn { display: inline-flex; align-items: center; gap: 6px; color: var(--slate); font-size: 13px; font-weight: 600; text-decoration: none; transition: color 0.15s; }
    .back-btn:hover { color: var(--blue); }

    .view-layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }

    .info-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 16px; }
    .card-header { padding: 14px 20px; background: var(--sky); border-bottom: 1px solid var(--sky2); display: flex; align-items: center; gap: 10px; }
    .card-icon { width: 32px; height: 32px; background: var(--blue); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .card-body { padding: 20px; }

    .wh-image { width: 100%; max-height: 260px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); display: block; margin-bottom: 16px; }
    .no-image  { width: 100%; height: 160px; background: var(--sky); border-radius: 10px; border: 1.5px dashed var(--sky2); display: flex; align-items: center; justify-content: center; font-size: 48px; margin-bottom: 16px; }

    .info-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 13.5px; gap: 12px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: var(--slate); font-weight: 600; flex-shrink: 0; }
    .info-value { font-weight: 700; color: var(--ink); text-align: right; }
    .info-value.price { color: var(--blue); font-size: 16px; font-weight: 800; }

    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-pending  { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-rejected { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-jazz     { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-stripe   { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }

    .doc-link { display: inline-flex; align-items: center; gap: 6px; background: var(--sky); color: var(--blue); border: 1px solid var(--sky2); padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.15s; }
    .doc-link:hover { background: var(--sky2); }

    .quick-actions { display: flex; flex-direction: column; gap: 10px; }
    .btn-edit { display: flex; align-items: center; justify-content: center; gap: 8px; background: var(--blue); color: #fff; padding: 11px; border-radius: 9px; font-size: 13.5px; font-weight: 800; text-decoration: none; transition: all 0.18s; }
    .btn-edit:hover { background: var(--blue2); transform: translateY(-1px); color: #fff; }
    .btn-back { display: flex; align-items: center; justify-content: center; gap: 8px; background: var(--white); color: var(--blue); border: 1.5px solid var(--sky2); padding: 11px; border-radius: 9px; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: all 0.18s; }
    .btn-back:hover { background: var(--sky); }

    @media(max-width:700px) { .view-layout { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div>
        <h2>🏢 {{ $warehouse->name }}</h2>
        <p style="font-size:13px;color:var(--slate);margin-top:4px;">Warehouse details and documents.</p>
    </div>
    <a href="{{ route('owner.warehouses.index') }}" class="back-btn">← Back to Warehouses</a>
</div>

<div class="view-layout">

    {{-- LEFT --}}
    <div>
        @if($warehouse->image)
            <img src="{{ asset('storage/'.$warehouse->image) }}" class="wh-image" alt="{{ $warehouse->name }}">
        @else
            <div class="no-image">🏢</div>
        @endif

        {{-- Basic Info --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">🏢</div>
                <h3>Warehouse Information</h3>
            </div>
            <div class="card-body">
                <div class="info-row"><span class="info-label">Location</span><span class="info-value">{{ $warehouse->location }}</span></div>
                <div class="info-row"><span class="info-label">Address</span><span class="info-value">{{ $warehouse->address ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Size</span><span class="info-value">{{ $warehouse->size ?? '—' }} sq ft</span></div>
                <div class="info-row"><span class="info-label">Contact</span><span class="info-value">{{ $warehouse->contact }}</span></div>
                @if($warehouse->description)
                    <div class="info-row"><span class="info-label">Description</span><span class="info-value" style="text-align:left;max-width:260px;font-size:13px;">{{ $warehouse->description }}</span></div>
                @endif
                <div class="info-row">
                    <span class="info-label">Status</span>
                    @if($warehouse->status === 'pending')
                        <span class="badge badge-pending">⏳ Pending Approval</span>
                    @elseif($warehouse->status === 'approved')
                        <span class="badge badge-approved">✓ Approved</span>
                    @else
                        <span class="badge badge-rejected">✕ Rejected</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Capacity & Pricing --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">📦</div>
                <h3>Capacity & Pricing</h3>
            </div>
            <div class="card-body">
                <div class="info-row"><span class="info-label">Total Units</span><span class="info-value">{{ $warehouse->total_space }}</span></div>
                <div class="info-row"><span class="info-label">Available Units</span><span class="info-value">{{ $warehouse->available_space }}</span></div>
                <div class="info-row"><span class="info-label">Price / Month</span><span class="info-value price">Rs {{ number_format($warehouse->price_per_month, 0) }}</span></div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">💳</div>
                <h3>Payment Settings</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Method</span>
                    @if($warehouse->preferred_payment_method === 'jazzcash')
                        <span class="badge badge-jazz">📱 JazzCash</span>
                    @else
                        <span class="badge badge-stripe">💳 Stripe</span>
                    @endif
                </div>
                @if($warehouse->preferred_payment_method === 'jazzcash' && $warehouse->jazzcash_number)
                    <div class="info-row"><span class="info-label">JazzCash No.</span><span class="info-value" style="color:#065f46;">{{ $warehouse->jazzcash_number }}</span></div>
                @elseif($warehouse->preferred_payment_method === 'stripe' && $warehouse->stripe_account_id)
                    <div class="info-row"><span class="info-label">Stripe Account</span><span class="info-value" style="font-size:12px;font-family:monospace;">{{ $warehouse->stripe_account_id }}</span></div>
                @endif
            </div>
        </div>

        {{-- Property Doc --}}
        @if($warehouse->property_doc)
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">📁</div>
                <h3>Property Document</h3>
            </div>
            <div class="card-body">
                <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="doc-link">📄 View Property Document</a>
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT: Quick Actions --}}
    <div>
        <div class="info-card" style="position:sticky;top:80px;">
            <div class="card-header">
                <div class="card-icon">⚡</div>
                <h3>Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('owner.warehouses.edit', $warehouse->id) }}" class="btn-edit">✏️ Edit Warehouse</a>
                    <a href="{{ route('owner.warehouses.index') }}" class="btn-back">← Back to List</a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
