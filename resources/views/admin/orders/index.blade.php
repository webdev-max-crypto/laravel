@extends('admin.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px,1fr)); gap: 14px; margin-bottom: 24px; }
    .stat-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 12px; padding: 16px 18px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); transition: all 0.18s; }
    .stat-card:hover { border-color: var(--blue); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.1); }
    .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .icon-blue   { background: var(--sky); }
    .icon-green  { background: rgba(16,185,129,0.1); }
    .icon-yellow { background: rgba(245,158,11,0.1); }
    .icon-red    { background: rgba(239,68,68,0.08); }
    .icon-purple { background: rgba(109,40,217,0.08); }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
    .stat-label { font-size: 11.5px; color: var(--slate); font-weight: 600; margin-top: 3px; }

    .search-bar { margin-bottom: 18px; }
    .search-input { width: 100%; max-width: 320px; padding: 9px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.18s; background: var(--white); }
    .search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 12px 14px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 12px 14px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .booking-id { font-family: monospace; font-weight: 800; color: var(--blue); }
    .name-bold  { font-weight: 700; }
    .price-val  { font-weight: 800; color: var(--blue); }
    .owner-val  { font-weight: 800; color: #065f46; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-pending   { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-active    { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-cancelled { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-expired   { background: #f1f5f9;                color: var(--slate); border: 1px solid var(--border); }
    .badge-paid      { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-released  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-unpaid    { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-cash      { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-escrow    { background: rgba(109,40,217,0.08); color: #6d28d9; border: 1px solid rgba(109,40,217,0.2); }

    .btn-proof   { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.3); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.15s; }
    .btn-proof:hover { background: var(--emerald); color: #fff; }
    .btn-release { background: var(--blue); color: #fff; border: none; padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; transition: background 0.15s; white-space: nowrap; }
    .btn-release:hover { background: var(--blue2); color: #fff; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <h2>📝 Orders & Bookings</h2>
    <p>Manage all customer bookings and payment releases.</p>
</div>

@php
    $total    = $bookings->count();
    $pending  = $bookings->where('status','pending')->count();
    $active   = $bookings->where('status','active')->count();
    $released = $bookings->where('payment_status','released')->count();
    $totalRev = $bookings->sum('total_price');
@endphp

<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon icon-blue">📝</div><div><div class="stat-value">{{ $total }}</div><div class="stat-label">Total Bookings</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-yellow">⏳</div><div><div class="stat-value">{{ $pending }}</div><div class="stat-label">Pending</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-blue">📦</div><div><div class="stat-value">{{ $active }}</div><div class="stat-label">Active</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-green">✅</div><div><div class="stat-value">{{ $released }}</div><div class="stat-label">Released</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-purple">💰</div><div><div class="stat-value" style="font-size:16px;">Rs {{ number_format($totalRev,0) }}</div><div class="stat-label">Total Revenue</div></div></div>
</div>

<div class="search-bar">
    <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by customer or owner...">
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Owner</th>
                <th>Total Price</th>
                <th>Owner Amount</th>
                <th>Proof</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr class="order-row">
                <td><span class="booking-id">#{{ $booking->id }}</span></td>
                <td><span class="name-bold">{{ optional($booking->customer)->name ?? 'N/A' }}</span></td>
                <td style="color:var(--slate);">{{ optional(optional($booking->warehouse)->owner)->name ?? 'N/A' }}</td>
                <td><span class="price-val">Rs {{ number_format($booking->total_price ?? 0, 0) }}</span></td>
                <td><span class="owner-val">Rs {{ number_format($booking->owner_amount ?? $booking->total_price ?? 0, 0) }}</span></td>
                <td>
                    @if($booking->payment_slip)
                        <a href="{{ asset('storage/'.$booking->payment_slip) }}" target="_blank" class="btn-proof">📄 View</a>
                    @else
                        <span style="color:var(--slate);font-size:12px;">—</span>
                    @endif
                </td>
                <td>
                    @php $smap = ['pending'=>'badge-pending','approved'=>'badge-approved','active'=>'badge-active','cancelled'=>'badge-cancelled','expired'=>'badge-expired']; @endphp
                    <span class="badge {{ $smap[$booking->status] ?? 'badge-expired' }}">{{ ucfirst($booking->status ?? 'N/A') }}</span>
                </td>
                <td>
                    @if($booking->payment_status === 'paid')
                        <span class="badge badge-paid">⏳ Paid</span>
                    @elseif($booking->payment_status === 'released')
                        <span class="badge badge-released">✓ Released</span>
                    @elseif($booking->payment_status === 'unpaid')
                        <span class="badge badge-unpaid">Unpaid</span>
                    @elseif($booking->payment_status === 'cash')
                        <span class="badge badge-cash">💵 Cash</span>
                    @elseif($booking->payment_status === 'escrow')
                        <span class="badge badge-escrow">🔒 Escrow</span>
                    @else
                        <span class="badge badge-expired">{{ ucfirst($booking->payment_status ?? 'N/A') }}</span>
                    @endif
                </td>
                <td>
                    @if($booking->payment_status === 'paid')
                        <a href="{{ route('admin.bookings.releasePage', $booking->id) }}" class="btn-release">💸 Release</a>
                    @elseif($booking->payment_status === 'released')
                        <span style="color:#065f46;font-weight:700;font-size:12px;">✔ Done</span>
                    @else
                        <span style="color:var(--slate);font-size:12px;">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state"><div class="empty-icon">📭</div><p>No bookings found.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.order-row').forEach(r => { r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none'; });
    });
</script>
@endsection
