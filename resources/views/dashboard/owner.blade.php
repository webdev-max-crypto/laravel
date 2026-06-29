@extends('layouts.owner')

@section('content')

@php
    $total    = \App\Models\Warehouse::where('owner_id', auth()->id())->count();
    $pending  = \App\Models\Warehouse::where('owner_id', auth()->id())->where('status','pending')->count();
    $approved = \App\Models\Warehouse::where('owner_id', auth()->id())->where('status','approved')->count();

    $totalBookings  = $bookings->count();
    $pendingBookings = $bookings->where('status','pending')->count();

    $totalEarnings = \App\Models\Booking::whereIn('warehouse_id',
        \App\Models\Warehouse::where('owner_id', auth()->id())->pluck('id')
    )->where('payment_status','released')->sum('owner_amount');
@endphp

<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(130deg, #1e40af 0%, #2563eb 100%);
        border-radius: 14px;
        padding: 26px 30px;
        margin-bottom: 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 28px rgba(37,99,235,0.22);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '🏭';
        position: absolute;
        right: 28px; font-size: 80px; opacity: 0.1;
    }
    .welcome-left { display: flex; align-items: center; gap: 16px; }
    .owner-avatar {
        width: 54px; height: 54px; border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
    }
    .owner-avatar-placeholder {
        width: 54px; height: 54px; border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; font-weight: 800; color: #fff;
        border: 3px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
    }
    .welcome-banner h2 { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 3px; }
    .welcome-banner p  { font-size: 13px; color: rgba(255,255,255,0.7); }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 26px;
    }
    .stat-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 20px 22px;
        display: flex; align-items: center; gap: 14px;
        transition: all 0.18s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .stat-card:hover {
        border-color: var(--blue);
        box-shadow: 0 6px 20px rgba(37,99,235,0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 46px; height: 46px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .icon-blue     { background: var(--sky); }
    .icon-green    { background: rgba(16,185,129,0.1); }
    .icon-yellow   { background: rgba(245,158,11,0.1); }
    .icon-purple   { background: rgba(109,40,217,0.08); }
    .icon-red      { background: rgba(239,68,68,0.08); }

    .stat-info { flex: 1; }
    .stat-value { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--slate); font-weight: 600; margin-top: 3px; }

    /* Section title */
    .section-title {
        font-size: 15px; font-weight: 800; color: var(--ink);
        margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* Quick Actions */
    .actions-grid {
        display: flex; gap: 12px; flex-wrap: wrap;
        margin-bottom: 26px;
    }
    .action-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 9px;
        font-size: 13px; font-weight: 700;
        text-decoration: none; transition: all 0.18s;
        border: none; cursor: pointer; font-family: inherit;
    }
    .action-btn.primary { background: var(--blue); color: #fff; }
    .action-btn.primary:hover { background: var(--blue2); transform: translateY(-1px); color: #fff; }
    .action-btn.outline { background: var(--white); color: var(--blue); border: 1.5px solid var(--sky2); }
    .action-btn.outline:hover { background: var(--sky); border-color: var(--blue); }
    .action-btn.danger  { background: rgba(239,68,68,0.08); color: #991b1b; border: 1.5px solid rgba(239,68,68,0.2); }
    .action-btn.danger:hover  { background: #ef4444; color: #fff; border-color: #ef4444; }

    /* Recent Bookings Table */
    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 26px; }
    .table-card-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .table-card-header span { font-size: 14px; font-weight: 800; color: var(--ink); }
    .table-card-header a { font-size: 12px; font-weight: 700; color: var(--blue); text-decoration: none; }
    .table-card-header a:hover { color: var(--blue2); }

    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 11px 16px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 12px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-pending   { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-active    { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-cancelled { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-expired   { background: #f1f5f9;                color: var(--slate); border: 1px solid var(--border); }

    .price-val { font-weight: 800; color: var(--blue); }

    .empty-state { text-align: center; padding: 40px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 40px; margin-bottom: 10px; }

    /* Approval alert */
    .alert-approved {
        background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25);
        color: #065f46; padding: 12px 16px; border-radius: 8px;
        margin-bottom: 20px; font-size: 14px; font-weight: 500;
    }
    .alert-approved a { color: var(--blue); font-weight: 700; }
</style>

{{-- Approval alert --}}
@if(session('warehouse_approved'))
    <div class="alert-approved">
        🎉 Your warehouse has been approved!
        <a href="{{ route('owner.warehouses.index') }}">View Warehouses →</a>
    </div>
@endif

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="welcome-left">
        @if($user->profile_photo)
            <img src="{{ asset('storage/'.$user->profile_photo) }}" class="owner-avatar" alt="avatar">
        @else
            <div class="owner-avatar-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
        @endif
        <div>
            <h2>Welcome back, {{ $user->name }} 👋</h2>
            <p>Here's an overview of your warehouse business today.</p>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">🏢</div>
        <div class="stat-info">
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-label">Total Warehouses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div class="stat-info">
            <div class="stat-value">{{ $approved }}</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow">⏳</div>
        <div class="stat-info">
            <div class="stat-value">{{ $pending }}</div>
            <div class="stat-label">Pending Approval</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue">📦</div>
        <div class="stat-info">
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-label">Recent Bookings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow">🔔</div>
        <div class="stat-info">
            <div class="stat-value">{{ $pendingBookings }}</div>
            <div class="stat-label">Pending Bookings</div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="section-title">⚡ Quick Actions</div>
<div class="actions-grid">
    <a href="{{ route('owner.warehouses.create') }}" class="action-btn primary">🏢 Add New Warehouse</a>
    <a href="{{ route('owner.warehouses.index') }}" class="action-btn outline">📋 My Warehouses</a>
    <a href="{{ route('owner.bookings') }}"          class="action-btn outline">📦 View Bookings</a>
    <a href="{{ route('owner.payments') }}"          class="action-btn outline">💸 Payments</a>
    <a href="{{ route('owner.profile') }}"           class="action-btn outline">⚙️ Edit Profile</a>
    <a href="{{ route('owner.delete.confirm') }}"    class="action-btn danger">🗑️ Delete Account</a>
</div>

{{-- Recent Bookings --}}
<div class="table-card">
    <div class="table-card-header">
        <span>📦 Recent Bookings</span>
        <a href="{{ route('owner.bookings') }}">View All →</a>
    </div>

    @if($bookings->count())
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Warehouse</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
            <tr>
                <td style="font-weight:700;">{{ $b->customer->name ?? '—' }}</td>
                <td style="color:var(--slate);">{{ $b->warehouse->name ?? '—' }}</td>
                <td>
                    @php
                        $map = ['pending'=>'badge-pending','approved'=>'badge-approved','active'=>'badge-active','cancelled'=>'badge-cancelled','expired'=>'badge-expired'];
                    @endphp
                    <span class="badge {{ $map[$b->status] ?? 'badge-expired' }}">{{ ucfirst($b->status) }}</span>
                </td>
                <td><span class="price-val">Rs {{ number_format($b->total_price,0) }}</span></td>
                <td>
                    @if($b->payment_status === 'paid')
                        <span class="badge badge-approved">✓ Paid</span>
                    @elseif($b->payment_status === 'pending')
                        <span class="badge badge-pending">⏳ Pending</span>
                    @elseif($b->payment_status === 'cash')
                        <span class="badge badge-active">💵 Cash</span>
                    @else
                        <span class="badge badge-expired">{{ ucfirst($b->payment_status) }}</span>
                    @endif
                </td>
                <td style="color:var(--slate);font-size:12px;">{{ $b->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p>No recent bookings yet.</p>
        </div>
    @endif
</div>

@endsection
