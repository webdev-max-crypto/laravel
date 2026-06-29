@extends('admin.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .back-btn { display: inline-flex; align-items: center; gap: 6px; color: var(--slate); font-size: 13px; font-weight: 600; text-decoration: none; transition: color 0.15s; }
    .back-btn:hover { color: var(--blue); }

    .view-layout { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }

    /* Cards */
    .info-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 16px; }
    .card-header { padding: 14px 20px; background: var(--sky); border-bottom: 1px solid var(--sky2); display: flex; align-items: center; gap: 10px; }
    .card-icon { width: 32px; height: 32px; background: var(--blue); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .card-body { padding: 20px; }

    /* Warehouse image */
    .wh-image { width: 100%; max-height: 280px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); display: block; margin-bottom: 16px; cursor: pointer; transition: transform 0.15s; }
    .wh-image:hover { transform: scale(1.01); }
    .no-image { width: 100%; height: 180px; background: var(--sky); border-radius: 10px; border: 1.5px dashed var(--sky2); display: flex; align-items: center; justify-content: center; font-size: 48px; margin-bottom: 16px; }

    /* Info rows */
    .info-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 13.5px; gap: 12px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: var(--slate); font-weight: 600; flex-shrink: 0; }
    .info-value { font-weight: 700; color: var(--ink); text-align: right; }
    .info-value.price { color: var(--blue); font-size: 16px; font-weight: 800; }

    /* Badges */
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-pending  { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-rejected { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-jazz     { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-stripe   { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }

    /* CNIC images */
    .doc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .doc-item { border: 1.5px solid var(--border); border-radius: 10px; overflow: hidden; }
    .doc-item img { width: 100%; height: 110px; object-fit: cover; display: block; }
    .doc-label { padding: 6px 10px; font-size: 11.5px; font-weight: 700; color: var(--slate); background: var(--bg); border-top: 1px solid var(--border); }

    /* Doc link */
    .doc-link { display: inline-flex; align-items: center; gap: 6px; background: var(--sky); color: var(--blue); border: 1px solid var(--sky2); padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.15s; }
    .doc-link:hover { background: var(--sky2); }

    /* Action buttons */
    .action-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); position: sticky; top: 80px; }
    .btn-approve { width: 100%; background: rgba(16,185,129,0.1); color: #065f46; border: 1.5px solid rgba(16,185,129,0.3); padding: 12px; border-radius: 9px; font-size: 14px; font-weight: 800; cursor: pointer; transition: all 0.18s; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px; }
    .btn-approve:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); }
    .btn-reject  { width: 100%; background: rgba(239,68,68,0.08); color: #991b1b; border: 1.5px solid rgba(239,68,68,0.2); padding: 12px; border-radius: 9px; font-size: 14px; font-weight: 800; cursor: pointer; transition: all 0.18s; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-reject:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    /* Owner info */
    .owner-cell { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid var(--border); }
    .owner-avatar { width: 42px; height: 42px; border-radius: 50%; background: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .owner-name  { font-size: 14px; font-weight: 800; color: var(--ink); }
    .owner-email { font-size: 12px; color: var(--slate); margin-top: 2px; }

    @media(max-width:768px) { .view-layout { grid-template-columns: 1fr; } .action-card { position: static; } .doc-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div>
        <h2>🏢 Warehouse Details</h2>
        <p style="font-size:13px;color:var(--slate);margin-top:4px;">Full information and documents for this warehouse.</p>
    </div>
    <a href="{{ route('admin.warehouses.pending') }}" class="back-btn">← Back to Warehouses</a>
</div>

<div class="view-layout">

    {{-- LEFT: Main Info --}}
    <div>

        {{-- Image --}}
        @if($warehouse->image)
            <a href="{{ asset('storage/'.$warehouse->image) }}" target="_blank">
                <img src="{{ asset('storage/'.$warehouse->image) }}" class="wh-image" alt="{{ $warehouse->name }}">
            </a>
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
                <div class="info-row"><span class="info-label">Name</span><span class="info-value">{{ $warehouse->name }}</span></div>
                <div class="info-row"><span class="info-label">Country</span><span class="info-value">{{ $warehouse->location }}</span></div>
                <div class="info-row"><span class="info-label">Address</span><span class="info-value">{{ $warehouse->address ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Size</span><span class="info-value">{{ $warehouse->size }} sq ft</span></div>
                <div class="info-row"><span class="info-label">Contact</span><span class="info-value">{{ $warehouse->contact }}</span></div>
                <div class="info-row"><span class="info-label">Description</span><span class="info-value" style="text-align:left;max-width:300px;">{{ $warehouse->description ?? '—' }}</span></div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span>
                        @if($warehouse->status === 'pending')
                            <span class="badge badge-pending">⏳ Pending</span>
                        @elseif($warehouse->status === 'approved')
                            <span class="badge badge-approved">✓ Approved</span>
                        @else
                            <span class="badge badge-rejected">✕ Rejected</span>
                        @endif
                    </span>
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

        {{-- Payment Settings --}}
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
                @if($warehouse->preferred_payment_method === 'jazzcash')
                    <div class="info-row"><span class="info-label">JazzCash No.</span><span class="info-value" style="color:#065f46;">{{ $warehouse->jazzcash_number ?? '—' }}</span></div>
                @else
                    <div class="info-row"><span class="info-label">Stripe Account</span><span class="info-value" style="font-size:12px;font-family:monospace;">{{ $warehouse->stripe_account_id ?? '—' }}</span></div>
                @endif
            </div>
        </div>

        {{-- Documents --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">📁</div>
                <h3>Documents</h3>
            </div>
            <div class="card-body">
                @if($warehouse->property_doc)
                    <div style="margin-bottom:16px;">
                        <p style="font-size:13px;font-weight:700;color:var(--ink);margin-bottom:8px;">Property Document</p>
                        <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="doc-link">📄 View Property Document</a>
                    </div>
                @endif

                @if($warehouse->owner->cnic_front || $warehouse->owner->cnic_back)
                    <p style="font-size:13px;font-weight:700;color:var(--ink);margin-bottom:10px;">Owner CNIC</p>
                    <div class="doc-grid">
                        @if($warehouse->owner->cnic_front)
                            <div class="doc-item">
                                <a href="{{ asset('storage/'.$warehouse->owner->cnic_front) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$warehouse->owner->cnic_front) }}" alt="CNIC Front">
                                </a>
                                <div class="doc-label">CNIC Front</div>
                            </div>
                        @endif
                        @if($warehouse->owner->cnic_back)
                            <div class="doc-item">
                                <a href="{{ asset('storage/'.$warehouse->owner->cnic_back) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$warehouse->owner->cnic_back) }}" alt="CNIC Back">
                                </a>
                                <div class="doc-label">CNIC Back</div>
                            </div>
                        @endif
                    </div>
                @endif

                @if(!$warehouse->property_doc && !$warehouse->owner->cnic_front && !$warehouse->owner->cnic_back)
                    <p style="color:var(--slate);font-size:13px;">No documents uploaded.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- RIGHT: Owner + Actions --}}
    <div>

        {{-- Owner Info --}}
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">👤</div>
                <h3>Owner Details</h3>
            </div>
            <div class="card-body">
                <div class="owner-cell">
                    <div class="owner-avatar">{{ strtoupper(substr($warehouse->owner->name, 0, 1)) }}</div>
                    <div>
                        <div class="owner-name">{{ $warehouse->owner->name }}</div>
                        <div class="owner-email">{{ $warehouse->owner->email }}</div>
                    </div>
                </div>
                <div class="info-row"><span class="info-label">Phone</span><span class="info-value">{{ $warehouse->owner->phone ?? '—' }}</span></div>
                <div class="info-row">
                    <span class="info-label">Verified</span>
                    @if($warehouse->owner->is_verified)
                        <span class="badge badge-approved">✓ Verified</span>
                    @else
                        <span class="badge badge-pending">⏳ Unverified</span>
                    @endif
                </div>
                <div class="info-row"><span class="info-label">Joined</span><span class="info-value" style="font-size:12px;">{{ $warehouse->owner->created_at->format('d M Y') }}</span></div>
            </div>
        </div>

        {{-- Actions --}}
        @if($warehouse->status === 'pending')
        <div class="action-card">
            <div class="card-header">
                <div class="card-icon">⚡</div>
                <h3>Actions</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.warehouses.approve', $warehouse->id) }}" method="POST" style="margin-bottom:10px;">
                    @csrf
                    <button class="btn-approve">✓ Approve Warehouse</button>
                </form>
                <form action="{{ route('admin.warehouses.reject', $warehouse->id) }}" method="POST"
                      onsubmit="return confirm('Reject this warehouse?')">
                    @csrf
                    <button class="btn-reject">✕ Reject Warehouse</button>
                </form>
            </div>
        </div>
        @else
        <div class="info-card">
            <div class="card-body" style="text-align:center;padding:20px;">
                @if($warehouse->status === 'approved')
                    <span class="badge badge-approved" style="font-size:14px;padding:8px 18px;">✓ Already Approved</span>
                @else
                    <span class="badge badge-rejected" style="font-size:14px;padding:8px 18px;">✕ Already Rejected</span>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
