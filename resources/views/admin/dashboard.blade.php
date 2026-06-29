@extends('admin.layouts.app')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(130deg, #1e40af 0%, #2563eb 100%);
        border-radius: 14px; padding: 26px 30px; margin-bottom: 26px;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: 0 8px 28px rgba(37,99,235,0.22);
        position: relative; overflow: hidden;
    }
    .welcome-banner::after { content: '🛡️'; position: absolute; right: 28px; font-size: 80px; opacity: 0.1; }
    .welcome-banner h2 { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 3px; }
    .welcome-banner p  { font-size: 13px; color: rgba(255,255,255,0.7); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px; margin-bottom: 26px;
    }
    .stat-card {
        background: var(--white); border: 1.5px solid var(--border);
        border-radius: 14px; padding: 20px 22px;
        display: flex; align-items: center; gap: 14px;
        transition: all 0.18s; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .stat-card:hover { border-color: var(--blue); box-shadow: 0 6px 20px rgba(37,99,235,0.1); transform: translateY(-2px); }
    .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .icon-blue   { background: var(--sky); }
    .icon-green  { background: rgba(16,185,129,0.1); }
    .icon-yellow { background: rgba(245,158,11,0.1); }
    .icon-red    { background: rgba(239,68,68,0.08); }
    .icon-purple { background: rgba(109,40,217,0.08); }
    .stat-value { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--slate); font-weight: 600; margin-top: 3px; }

    .section-title {
        font-size: 15px; font-weight: 800; color: var(--ink);
        margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .actions-grid { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 26px; }
    .action-btn {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 9px;
        font-size: 13px; font-weight: 700;
        text-decoration: none; transition: all 0.18s;
    }
    .action-btn.primary { background: var(--blue); color: #fff; }
    .action-btn.primary:hover { background: var(--blue2); transform: translateY(-1px); color: #fff; }
    .action-btn.outline { background: var(--white); color: var(--blue); border: 1.5px solid var(--sky2); }
    .action-btn.outline:hover { background: var(--sky); border-color: var(--blue); }

    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .table-card-header span { font-size: 14px; font-weight: 800; color: var(--ink); }
    .table-card-header a { font-size: 12px; font-weight: 700; color: var(--blue); text-decoration: none; }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 11px 14px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 11px 14px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-pending   { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-active    { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-cancelled { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }

    .price-val { font-weight: 800; color: var(--blue); }
    .name-bold { font-weight: 700; }

    .empty-state { text-align: center; padding: 30px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 36px; margin-bottom: 8px; }

    @media(max-width:900px) { .two-col { grid-template-columns: 1fr; } }
</style>

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div>
        <h2>Welcome back, {{ auth()->user()->name }} 👋</h2>
        <p>Here's your platform overview for today.</p>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">👥</div>
        <div><div class="stat-value">{{ $users }}</div><div class="stat-label">Total Users</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue">🏢</div>
        <div><div class="stat-value">{{ $warehouses }}</div><div class="stat-label">Total Warehouses</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow">⏳</div>
        <div><div class="stat-value">{{ $pendingWarehouses }}</div><div class="stat-label">Pending Warehouses</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">📦</div>
        <div><div class="stat-value">{{ $activeBookings }}</div><div class="stat-label">Active Bookings</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div><div class="stat-value">{{ $releasedBookings }}</div><div class="stat-label">Released Bookings</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple">💰</div>
        <div>
            <div class="stat-value" style="font-size:18px;">Rs {{ number_format($totalCommission, 0) }}</div>
            <div class="stat-label">Total Commission</div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="section-title">⚡ Quick Actions</div>
<div class="actions-grid">
    <a href="{{ route('admin.warehouses.pending') }}"  class="action-btn primary">🏢 Pending Warehouses</a>
    <a href="{{ route('admin.users.index') }}"         class="action-btn outline">👥 Manage Users</a>
    <a href="{{ route('admin.bookings.index') }}"      class="action-btn outline">📦 All Bookings</a>
    <a href="{{ route('admin.balances') }}"            class="action-btn outline">💰 Balances</a>
    <a href="{{ route('admin.refunds.index') }}"       class="action-btn outline">↩️ Refunds</a>
    <a href="{{ route('admin.reports.index') }}"       class="action-btn outline">📊 Reports</a>
</div>

{{-- Recent Bookings & Pending Warehouses --}}
<div class="two-col">

    {{-- Recent Bookings --}}
    <div class="table-card">
        <div class="table-card-header">
            <span>📦 Recent Bookings</span>
            <a href="{{ route('admin.bookings.index') }}">View All →</a>
        </div>
        @php
            $recentBookings = \App\Models\Booking::with(['customer','warehouse'])
                ->latest()->take(5)->get();
        @endphp
        @if($recentBookings->count())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Warehouse</th>
                    <th>Status</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $b)
                <tr>
                    <td style="font-family:monospace;font-weight:800;color:var(--blue);">#{{ $b->id }}</td>
                    <td class="name-bold">{{ optional($b->customer)->name ?? '—' }}</td>
                    <td style="color:var(--slate);">{{ optional($b->warehouse)->name ?? '—' }}</td>
                    <td>
                        @php $map = ['pending'=>'badge-pending','approved'=>'badge-approved','active'=>'badge-active','cancelled'=>'badge-cancelled']; @endphp
                        <span class="badge {{ $map[$b->status] ?? 'badge-pending' }}">{{ ucfirst($b->status) }}</span>
                    </td>
                    <td><span class="price-val">Rs {{ number_format($b->total_price, 0) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state"><div class="empty-icon">📭</div><p>No bookings yet.</p></div>
        @endif
    </div>

    {{-- Pending Warehouses --}}
    <div class="table-card">
        <div class="table-card-header">
            <span>🏢 Pending Warehouses</span>
            <a href="{{ route('admin.warehouses.pending') }}">View All →</a>
        </div>
        @php
            $pendingWH = \App\Models\Warehouse::with('owner')->where('status','pending')->latest()->take(5)->get();
        @endphp
        @if($pendingWH->count())
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>Location</th>
                    <th>Price/mo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingWH as $w)
                <tr>
                    <td class="name-bold">{{ $w->name }}</td>
                    <td style="color:var(--slate);">{{ optional($w->owner)->name ?? '—' }}</td>
                    <td style="color:var(--slate);font-size:12px;">{{ Str::limit($w->location, 25) }}</td>
                    <td><span class="price-val">Rs {{ number_format($w->price_per_month, 0) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state"><div class="empty-icon">✅</div><p>No pending warehouses.</p></div>
        @endif
    </div>

</div>

@endsection
