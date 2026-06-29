@extends('customer.layouts.app')

@section('content')

<style>
    .page-header { margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); display: flex; align-items: center; gap: 8px; }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .alert-box { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13.5px; font-weight: 500; }
    .alert-success { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25); color: #065f46; }
    .alert-info    { background: var(--sky); border: 1px solid var(--sky2); color: #1e40af; }

    .table-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-card table { width: 100%; border-collapse: collapse; }

    .table-card thead tr { background: var(--sky); }
    .table-card thead th {
        padding: 13px 16px;
        font-size: 11.5px; font-weight: 700;
        color: var(--blue);
        text-transform: uppercase; letter-spacing: 0.8px;
        text-align: left; white-space: nowrap;
        border-bottom: 1.5px solid var(--sky2);
    }

    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 13px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .booking-id { font-weight: 800; color: var(--blue); font-family: monospace; font-size: 13.5px; }
    .wh-name    { font-weight: 700; color: var(--ink); }
    .price-val  { font-weight: 800; color: var(--blue); }

    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 12px; font-weight: 700; white-space: nowrap;
    }
    .badge-cash    { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-paid    { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-pending { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }

    .badge-status-pending   { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-status-approved  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-status-active    { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-status-cancelled { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-status-expired   { background: #f1f5f9;                color: var(--slate); border: 1px solid var(--border); }

    .pending-note {
        font-size: 11px; color: #92400e;
        background: rgba(245,158,11,0.08);
        border-radius: 6px; padding: 3px 8px;
        margin-top: 4px; display: block;
    }

    .btn-confirm {
        background: var(--blue); color: #fff;
        padding: 7px 14px; border-radius: 8px;
        border: none; font-size: 12px; font-weight: 700;
        cursor: pointer; transition: background 0.18s, transform 0.1s;
        font-family: inherit;
    }
    .btn-confirm:hover { background: var(--blue2); transform: translateY(-1px); }

    .confirmed-text { color: #065f46; font-weight: 700; font-size: 13px; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }
</style>

<div class="page-header">
    <h2>📜 Booking History</h2>
    <p>All your warehouse bookings in one place.</p>
</div>

@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('payment_done'))
    <div class="alert-box alert-info">
        ✅ Payment received. Please check the <strong>Goods Confirm</strong> button when your goods safely arrive at the warehouse.
    </div>
@endif

@if($bookings->count() > 0)
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Warehouse</th>
                    <th>Total Price</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Goods Confirm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                <tr>
                    <td><span class="booking-id">#{{ $b->id }}</span></td>
                    <td><span class="wh-name">{{ optional($b->warehouse)->name ?? 'N/A' }}</span></td>
                    <td><span class="price-val">Rs {{ number_format($b->total_price) }}</span></td>
                    <td>
                        @if($b->payment_status == 'cash')
                            <span class="badge badge-cash">💵 Cash</span>
                        @elseif($b->payment_status == 'pending')
                            <span class="badge badge-pending">⏳ Pending</span>
                            <span class="pending-note">Pay via JazzCash: <strong>03167630754</strong> within 24h</span>
                        @elseif($b->payment_status == 'paid')
                            <span class="badge badge-paid">✅ Paid</span>
                        @else
                            <span class="badge badge-status-expired">{{ ucfirst($b->payment_status) }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusMap = [
                                'pending'   => 'badge-status-pending',
                                'approved'  => 'badge-status-approved',
                                'active'    => 'badge-status-active',
                                'cancelled' => 'badge-status-cancelled',
                                'expired'   => 'badge-status-expired',
                            ];
                            $cls = $statusMap[strtolower($b->status)] ?? 'badge-status-pending';
                        @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($b->status) }}</span>
                    </td>
                    <td>
                        @if($b->goods_confirmed)
                            <span class="confirmed-text">✅ Confirmed</span>
                        @elseif($b->payment_status == 'paid')
                            <form action="{{ route('customer.goods.confirm', $b->id) }}" method="POST">
                                @csrf
                                <button class="btn-confirm">Confirm Goods</button>
                            </form>
                        @else
                            <span style="color:#cbd5e1;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <p>No bookings yet. Start by booking a warehouse!</p>
    </div>
@endif

@endsection
