@extends('admin.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
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
    .stat-value { font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
    .stat-label { font-size: 11.5px; color: var(--slate); font-weight: 600; margin-top: 3px; }

    .search-bar { margin-bottom: 18px; }
    .search-input { width: 100%; max-width: 320px; padding: 9px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.18s; background: var(--white); }
    .search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 13px 16px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 13px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .owner-cell { display: flex; align-items: center; gap: 10px; }
    .owner-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .owner-name  { font-size: 13px; font-weight: 700; color: var(--ink); }
    .owner-email { font-size: 11.5px; color: var(--slate); margin-top: 1px; }

    .wh-name  { font-weight: 700; color: var(--ink); }
    .price-val { font-weight: 800; color: var(--blue); }

    .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-active   { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-inactive { background: #f1f5f9; color: var(--slate); border: 1px solid var(--border); }

    .active-dot   { width: 7px; height: 7px; border-radius: 50%; background: #10b981; display: inline-block; }
    .inactive-dot { width: 7px; height: 7px; border-radius: 50%; background: #94a3b8; display: inline-block; }

    .wh-img { width: 72px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); cursor: pointer; transition: transform 0.15s; }
    .wh-img:hover { transform: scale(1.05); }
    .no-img { width: 72px; height: 50px; background: var(--sky); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 22px; border: 1px solid var(--sky2); }

    .last-active { font-size: 12px; color: var(--slate); }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <div>
        <h2>✅ Active Warehouses</h2>
        <p>All approved and live warehouses on the platform.</p>
    </div>
    <a href="{{ route('admin.warehouses.pending') }}"
       style="display:inline-flex;align-items:center;gap:6px;background:var(--sky);color:var(--blue);border:1px solid var(--sky2);padding:8px 16px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">
        ⏳ Pending Warehouses →
    </a>
</div>

@php
    $total    = $warehouses->count();
    $active   = $warehouses->where('is_active', true)->count();
    $inactive = $warehouses->where('is_active', false)->count();
    $avgPrice = $warehouses->avg('price_per_month');
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">🏢</div>
        <div><div class="stat-value">{{ $total }}</div><div class="stat-label">Total Approved</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">🟢</div>
        <div><div class="stat-value">{{ $active }}</div><div class="stat-label">Active</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow">⚫</div>
        <div><div class="stat-value">{{ $inactive }}</div><div class="stat-label">Inactive</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue">💰</div>
        <div>
            <div class="stat-value" style="font-size:16px;">Rs {{ number_format($avgPrice, 0) }}</div>
            <div class="stat-label">Avg Price/Month</div>
        </div>
    </div>
</div>

<div class="search-bar">
    <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by name or owner...">
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Owner</th>
                <th>Warehouse</th>
                <th>Location</th>
                <th>Price / Month</th>
                <th>Image</th>
                <th>Active Status</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($warehouses as $w)
            <tr class="wh-row">
                <td>
                    <div class="owner-cell">
                        <div class="owner-avatar">{{ strtoupper(substr(optional($w->owner)->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <div class="owner-name">{{ optional($w->owner)->name ?? '—' }}</div>
                            <div class="owner-email">{{ optional($w->owner)->email ?? '—' }}</div>
                        </div>
                    </div>
                </td>

                <td><span class="wh-name">{{ $w->name }}</span></td>
                <td style="color:var(--slate);font-size:12.5px;">{{ Str::limit($w->location, 35) }}</td>
                <td><span class="price-val">Rs {{ number_format($w->price_per_month, 0) }}</span></td>

                <td>
                    @if($w->image)
                        <a href="{{ asset('storage/'.$w->image) }}" target="_blank">
                            <img src="{{ asset('storage/'.$w->image) }}" class="wh-img" alt="warehouse">
                        </a>
                    @else
                        <div class="no-img">🏢</div>
                    @endif
                </td>

                <td>
                    @if($w->is_active)
                        <span class="badge badge-active"><span class="active-dot"></span> Active</span>
                    @else
                        <span class="badge badge-inactive"><span class="inactive-dot"></span> Inactive</span>
                    @endif
                </td>

                <td><span class="last-active">{{ $w->updated_at->diffForHumans() }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon">🏗️</div>
                        <p>No approved warehouses found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.wh-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>

@endsection
