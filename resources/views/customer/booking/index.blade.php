@extends('customer.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom:22px; }
    .page-header h2 { font-size:20px; font-weight:800; color:var(--ink); }
    .page-header p  { font-size:13px; color:var(--slate); margin-top:4px; }

    .bookings-list { display:flex; flex-direction:column; gap:14px; max-width:760px; }

    .booking-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); transition:all 0.18s; }
    .booking-card:hover { border-color:var(--blue); box-shadow:0 6px 20px rgba(37,99,235,0.1); transform:translateY(-2px); }

    .booking-card-header { padding:14px 18px; background:var(--sky); border-bottom:1px solid var(--sky2); display:flex; align-items:center; justify-content:space-between; }
    .booking-id { font-size:13px; font-weight:800; color:var(--blue); font-family:monospace; }
    .booking-wh { font-size:14px; font-weight:800; color:var(--ink); }

    .booking-card-body { padding:16px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
    .booking-meta { display:flex; gap:20px; flex-wrap:wrap; }
    .meta-item { font-size:13px; }
    .meta-label { color:var(--slate); font-weight:600; display:block; font-size:11.5px; margin-bottom:2px; }
    .meta-value { font-weight:700; color:var(--ink); }
    .meta-value.price { color:var(--blue); }

    .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:700; white-space:nowrap; }
    .badge-pending   { background:rgba(245,158,11,0.1);  color:#92400e; border:1px solid rgba(245,158,11,0.25); }
    .badge-approved  { background:rgba(16,185,129,0.1);  color:#065f46; border:1px solid rgba(16,185,129,0.25); }
    .badge-active    { background:var(--sky);             color:var(--blue); border:1px solid var(--sky2); }
    .badge-cancelled { background:rgba(239,68,68,0.08);  color:#991b1b; border:1px solid rgba(239,68,68,0.2); }
    .badge-expired   { background:#f1f5f9;                color:var(--slate); border:1px solid var(--border); }

    .invoice-btn { display:inline-flex; align-items:center; gap:6px; background:var(--sky); color:var(--blue); border:1px solid var(--sky2); padding:7px 14px; border-radius:8px; font-size:12.5px; font-weight:700; text-decoration:none; transition:all 0.15s; }
    .invoice-btn:hover { background:var(--sky2); color:var(--blue2); }

    .empty-state { text-align:center; padding:60px 20px; color:var(--slate); }
    .empty-state .empty-icon { font-size:44px; margin-bottom:12px; }
    .empty-state p { font-size:14px; }
    .btn-primary { display:inline-flex; align-items:center; gap:6px; background:var(--blue); color:#fff; padding:10px 20px; border-radius:9px; text-decoration:none; font-size:13px; font-weight:700; transition:all 0.18s; margin-top:14px; }
    .btn-primary:hover { background:var(--blue2); color:#fff; }
</style>

<div class="page-header">
    <h2>📦 My Bookings</h2>
    <p>All your warehouse booking records.</p>
</div>

@if($bookings->count())
    <div class="bookings-list">
        @foreach($bookings as $b)
        <div class="booking-card">
            <div class="booking-card-header">
                <div>
                    <div class="booking-id">#{{ $b->id }}</div>
                    <div class="booking-wh">{{ optional($b->warehouse)->name ?? 'N/A' }}</div>
                </div>
                @php
                    $map = ['pending'=>'badge-pending','approved'=>'badge-approved','active'=>'badge-active','cancelled'=>'badge-cancelled','expired'=>'badge-expired'];
                @endphp
                <span class="badge {{ $map[strtolower($b->status)] ?? 'badge-expired' }}">{{ ucfirst($b->status) }}</span>
            </div>
            <div class="booking-card-body">
                <div class="booking-meta">
                    <div class="meta-item">
                        <span class="meta-label">Total Price</span>
                        <span class="meta-value price">Rs {{ number_format($b->total_price) }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Payment</span>
                        <span class="meta-value">{{ ucfirst($b->payment_status ?? 'N/A') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Date</span>
                        <span class="meta-value">{{ $b->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                <a href="{{ route('customer.booking.invoice', $b->id) }}" target="_blank" class="invoice-btn">
                    📄 Invoice
                </a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>No bookings yet. Start by booking a warehouse!</p>
        <a href="{{ route('customer.dashboard') }}" class="btn-primary">🏢 Browse Warehouses</a>
    </div>
@endif
@endsection
