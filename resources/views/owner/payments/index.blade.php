@extends('layouts.owner')

@section('content')

<style>
    .page-header { margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    /* Summary cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 14px;
        margin-bottom: 26px;
    }
    .summary-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex; align-items: center; gap: 14px;
        transition: all 0.18s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .summary-card:hover { border-color: var(--blue); box-shadow: 0 6px 20px rgba(37,99,235,0.1); transform: translateY(-2px); }
    .s-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .s-icon.blue   { background: var(--sky); }
    .s-icon.green  { background: rgba(16,185,129,0.1); }
    .s-icon.yellow { background: rgba(245,158,11,0.1); }
    .s-icon.purple { background: rgba(109,40,217,0.08); }
    .s-value { font-size: 20px; font-weight: 800; color: var(--ink); line-height: 1; }
    .s-label { font-size: 11.5px; color: var(--slate); font-weight: 600; margin-top: 3px; }

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
    .name-bold   { font-weight: 700; color: var(--ink); }
    .price-val   { font-weight: 800; color: var(--blue); }
    .owner-val   { font-weight: 800; color: #065f46; }

    /* Badges */
    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-received { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-released { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-pending  { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-other    { background: #f1f5f9;                color: var(--slate); border: 1px solid var(--border); }

    /* Proof button */
    .btn-proof {
        background: rgba(16,185,129,0.1); color: #065f46;
        border: 1px solid rgba(16,185,129,0.3);
        padding: 5px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 700;
        text-decoration: none; transition: all 0.15s; display: inline-block;
    }
    .btn-proof:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); }

    /* Action buttons */
    .btn-stripe {
        background: var(--blue); color: #fff;
        border: none; padding: 6px 14px; border-radius: 7px;
        font-size: 12px; font-weight: 700; cursor: pointer;
        transition: background 0.15s; font-family: inherit;
    }
    .btn-stripe:hover { background: var(--blue2); }

    .btn-jazz {
        background: rgba(109,40,217,0.1); color: #6d28d9;
        border: 1px solid rgba(109,40,217,0.25); padding: 6px 14px; border-radius: 7px;
        font-size: 12px; font-weight: 700; cursor: pointer;
        transition: all 0.15s; font-family: inherit;
    }
    .btn-jazz:hover { background: #6d28d9; color: #fff; border-color: #6d28d9; }

    .completed-text { color: #065f46; font-weight: 700; font-size: 13px; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <h2>💸 Payments</h2>
    <p>Track all booking payments and your earnings.</p>
</div>

{{-- Summary Cards --}}
@php
    $totalReceived = $bookings->whereIn('payment_status', ['paid','released'])->sum('total_price');
    $ownerTotal    = $bookings->whereIn('payment_status', ['paid','released'])->sum(fn($b) => $b->owner_amount ?? ($b->total_price * 0.9));
    $pendingCount  = $bookings->whereIn('payment_status', ['unpaid','escrow','pending'])->count();
    $releasedCount = $bookings->where('payment_status', 'released')->count();
@endphp

<div class="summary-grid">
    <div class="summary-card">
        <div class="s-icon blue">📋</div>
        <div>
            <div class="s-value">{{ $bookings->count() }}</div>
            <div class="s-label">Total Bookings</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="s-icon green">💰</div>
        <div>
            <div class="s-value" style="font-size:16px;">Rs {{ number_format($totalReceived, 0) }}</div>
            <div class="s-label">Total Received</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="s-icon green">🏦</div>
        <div>
            <div class="s-value" style="font-size:16px;">Rs {{ number_format($ownerTotal, 0) }}</div>
            <div class="s-label">Your Earnings</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="s-icon yellow">⏳</div>
        <div>
            <div class="s-value">{{ $pendingCount }}</div>
            <div class="s-label">Pending Payments</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="s-icon blue">✅</div>
        <div>
            <div class="s-value">{{ $releasedCount }}</div>
            <div class="s-label">Released</div>
        </div>
    </div>
</div>

{{-- Payments Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Warehouse</th>
                <th>Customer</th>
                <th>Total (PKR)</th>
                <th>Your Amount</th>
                <th>Proof</th>
                <th>Method</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td><span class="booking-id">#{{ $booking->id }}</span></td>
                <td><span class="name-bold">{{ optional($booking->warehouse)->name ?? '—' }}</span></td>
                <td>{{ optional($booking->customer)->name ?? '—' }}</td>
                <td><span class="price-val">Rs {{ number_format($booking->total_price ?? 0, 0) }}</span></td>
                <td><span class="owner-val">Rs {{ number_format($booking->owner_amount ?? ($booking->total_price * 0.9), 0) }}</span></td>

                <td>
                    @if($booking->payment_proof)
                        <a href="{{ asset('storage/'.$booking->payment_proof) }}" target="_blank" class="btn-proof">📄 View</a>
                    @elseif($booking->payment_slip)
                        <a href="{{ asset('storage/'.$booking->payment_slip) }}" target="_blank" class="btn-proof">📄 View</a>
                    @else
                        <span style="color:var(--slate);font-size:12px;">—</span>
                    @endif
                </td>

                <td style="color:var(--slate);">{{ ucfirst(optional($booking->warehouse)->preferred_payment_method ?? 'N/A') }}</td>

                <td>
                    @if($booking->payment_status == 'paid')
                        <span class="badge badge-received">✓ Received</span>
                    @elseif($booking->payment_status == 'released')
                        <span class="badge badge-released">🏦 Released</span>
                    @elseif(in_array($booking->payment_status, ['unpaid','escrow','pending']))
                        <span class="badge badge-pending">⏳ Pending</span>
                    @else
                        <span class="badge badge-other">{{ ucfirst($booking->payment_status) }}</span>
                    @endif
                </td>

                <td>
                    @if($booking->payment_status != 'paid' && $booking->payment_status != 'released')
                        @if(optional($booking->warehouse)->preferred_payment_method == 'stripe')
                            <form method="POST" action="{{ route('owner.payments.stripe', $booking->id) }}">
                                @csrf
                                <button class="btn-stripe">💳 Stripe</button>
                            </form>
                        @elseif(optional($booking->warehouse)->preferred_payment_method == 'jazzcash')
                            <form method="POST" action="{{ route('owner.payments.jazzcash', $booking->id) }}">
                                @csrf
                                <button class="btn-jazz">📱 JazzCash</button>
                            </form>
                        @else
                            <span style="color:var(--slate);font-size:12px;">—</span>
                        @endif
                    @else
                        <span class="completed-text">✓ Done</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon">💳</div>
                        <p>No payment records found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
