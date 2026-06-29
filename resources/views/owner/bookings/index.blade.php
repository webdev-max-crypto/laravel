@extends('layouts.owner')

@section('content')
<style>
    .page-header { margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 13px 16px; font-size: 11.5px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 13px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; }
    .badge-pending   { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-active    { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-cancelled { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-expired   { background: #f1f5f9;                color: var(--slate); border: 1px solid var(--border); }

    .pay-unpaid  { color: #991b1b; font-weight: 700; }
    .pay-pending { color: #92400e; font-weight: 700; }
    .pay-released{ color: #065f46; font-weight: 700; }
    .pay-cash    { color: var(--blue); font-weight: 700; }
    .pay-escrow  { color: #6d28d9; font-weight: 700; }

    .price-val { font-weight: 800; color: var(--blue); }

    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-approve { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.3); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-approve:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); }
    .btn-cancel  { background: rgba(239,68,68,0.08); color: #991b1b; border: 1px solid rgba(239,68,68,0.2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-cancel:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <h2>📦 My Bookings</h2>
    <p>Manage and respond to customer booking requests.</p>
</div>

@if($bookings->count())
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Warehouse</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Owner Amount</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td style="font-weight:700;">{{ $booking->warehouse->name ?? '—' }}</td>
                <td>{{ $booking->customer->name ?? '—' }}</td>
                <td>
                    @php
                        $map = ['pending'=>'badge-pending','approved'=>'badge-approved','active'=>'badge-active','cancelled'=>'badge-cancelled','expired'=>'badge-expired'];
                        $cls = $map[$booking->status] ?? 'badge-expired';
                    @endphp
                    <span class="badge {{ $cls }}">{{ ucfirst($booking->status) }}</span>
                </td>
                <td><span class="price-val">Rs {{ number_format($booking->total_price, 0) }}</span></td>
                <td><span class="price-val">Rs {{ number_format($booking->owner_amount ?? 0, 0) }}</span></td>
                <td style="color:var(--slate);">{{ ucfirst($booking->payment_method ?? 'N/A') }}</td>
                <td>
                    @if($booking->payment_status === 'unpaid')
                        <span class="pay-unpaid">Unpaid</span>
                    @elseif($booking->payment_status === 'paid')
                        <span class="pay-pending">⏳ Pending Release</span>
                    @elseif($booking->payment_status === 'released')
                        <span class="pay-released">✓ Released</span>
                    @elseif($booking->payment_status === 'cash')
                        <span class="pay-cash">💵 Cash</span>
                    @elseif($booking->payment_status === 'escrow')
                        <span class="pay-escrow">🔒 Escrow</span>
                    @endif
                </td>
                <td>
                    @if($booking->status == 'pending')
                        <div class="action-btns">
                            <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
                                @csrf <button class="btn-approve">✓ Approve</button>
                            </form>
                            <form action="{{ route('owner.bookings.cancel', $booking->id) }}" method="POST">
                                @csrf <button class="btn-cancel">✕ Cancel</button>
                            </form>
                        </div>
                    @else
                        <span style="color:var(--slate);font-size:12px;">—</span>
                        @if($booking->payment_status === 'released')
                            <span class="badge badge-approved" style="margin-top:4px;display:inline-flex;">In Stripe</span>
                        @elseif($booking->payment_status === 'paid')
                            <span class="badge badge-pending" style="margin-top:4px;display:inline-flex;">Awaiting Release</span>
                        @endif
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
        <p>No bookings yet.</p>
    </div>
@endif
@endsection
