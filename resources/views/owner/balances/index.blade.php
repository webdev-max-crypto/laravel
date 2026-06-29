@extends('layouts.owner')

@section('content')

<style>
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    /* Hero balance banner */
    .balance-banner {
        background: linear-gradient(130deg, #1e40af 0%, #2563eb 100%);
        border-radius: 16px;
        padding: 32px 36px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 8px 28px rgba(37,99,235,0.25);
        position: relative;
        overflow: hidden;
    }
    .balance-banner::after {
        content: '💰';
        position: absolute;
        right: 32px; font-size: 90px; opacity: 0.1;
    }
    .balance-label { font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 6px; }
    .balance-amount { font-size: 42px; font-weight: 800; color: #fff; line-height: 1; }
    .balance-sub { font-size: 12px; color: rgba(255,255,255,0.55); margin-top: 6px; }

    /* Stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 14px;
        margin-bottom: 26px;
    }
    .stat-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex; align-items: center; gap: 14px;
        transition: all 0.18s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .stat-card:hover { border-color: var(--blue); box-shadow: 0 6px 20px rgba(37,99,235,0.1); transform: translateY(-2px); }
    .stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .icon-blue   { background: var(--sky); }
    .icon-green  { background: rgba(16,185,129,0.1); }
    .icon-yellow { background: rgba(245,158,11,0.1); }
    .icon-red    { background: rgba(239,68,68,0.08); }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
    .stat-label { font-size: 11.5px; color: var(--slate); font-weight: 600; margin-top: 3px; }

    /* Section title */
    .section-title {
        font-size: 15px; font-weight: 800; color: var(--ink);
        margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* Table */
    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 13px 16px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 13px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .booking-id { font-weight: 800; color: var(--blue); font-family: monospace; font-size: 13px; }
    .name-bold   { font-weight: 700; }
    .amount-val  { font-weight: 800; color: #065f46; font-size: 14px; }
    .date-val    { color: var(--slate); font-size: 12px; }

    /* Badges */
    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-released { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-paid     { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-pending  { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-escrow   { background: rgba(109,40,217,0.08); color: #6d28d9; border: 1px solid rgba(109,40,217,0.2); }
    .badge-other    { background: #f1f5f9; color: var(--slate); border: 1px solid var(--border); }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <h2>💰 My Balance</h2>
    <p>Your complete financial overview and payment history.</p>
</div>

{{-- Compute stats --}}
@php
    $released     = $payments->where('payment_status', 'released');
    $paid         = $payments->where('payment_status', 'paid');
    $pending      = $payments->whereIn('payment_status', ['unpaid','escrow','pending']);

    $releasedAmt  = $released->sum(fn($p) => $p->owner_amount ?? ($p->total_price * 0.9));
    $pendingAmt   = $paid->sum(fn($p) => $p->owner_amount ?? ($p->total_price * 0.9));
    $pendingCount = $pending->count();
@endphp

{{-- Hero Balance Banner --}}
<div class="balance-banner">
    <div>
        <div class="balance-label">Total Received</div>
        <div class="balance-amount">Rs {{ number_format($totalReceived, 0) }}</div>
        <div class="balance-sub">Across all released & paid bookings</div>
    </div>
    <div style="display:flex;gap:32px;flex-wrap:wrap;">
        <div>
            <div class="balance-label">Released to You</div>
            <div style="font-size:24px;font-weight:800;color:#fff;">Rs {{ number_format($releasedAmt, 0) }}</div>
        </div>
        <div>
            <div class="balance-label">Awaiting Release</div>
            <div style="font-size:24px;font-weight:800;color:#fbbf24;">Rs {{ number_format($pendingAmt, 0) }}</div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">📋</div>
        <div>
            <div class="stat-value">{{ $payments->count() }}</div>
            <div class="stat-label">Total Transactions</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div>
            <div class="stat-value">{{ $released->count() }}</div>
            <div class="stat-label">Released</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue">💳</div>
        <div>
            <div class="stat-value">{{ $paid->count() }}</div>
            <div class="stat-label">Paid (Held)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow">⏳</div>
        <div>
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
</div>

{{-- Payment History Table --}}
<div class="section-title">📜 Payment History</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Warehouse</th>
                <th>Customer</th>
                <th>Your Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td><span class="booking-id">#{{ $payment->id }}</span></td>
                <td><span class="name-bold">{{ optional($payment->warehouse)->name ?? '—' }}</span></td>
                <td>{{ optional($payment->customer)->name ?? '—' }}</td>
                <td><span class="amount-val">Rs {{ number_format($payment->owner_amount ?? ($payment->total_price * 0.9), 0) }}</span></td>
                <td>
                    @if($payment->payment_status == 'released')
                        <span class="badge badge-released">✓ Released</span>
                    @elseif($payment->payment_status == 'paid')
                        <span class="badge badge-paid">💳 Paid</span>
                    @elseif($payment->payment_status == 'escrow')
                        <span class="badge badge-escrow">🔒 Escrow</span>
                    @elseif(in_array($payment->payment_status, ['unpaid','pending']))
                        <span class="badge badge-pending">⏳ Pending</span>
                    @else
                        <span class="badge badge-other">{{ ucfirst($payment->payment_status) }}</span>
                    @endif
                </td>
                <td><span class="date-val">{{ $payment->created_at->format('d M, Y') }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <p>No payment history yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
