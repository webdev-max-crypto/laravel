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

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .user-name  { font-size: 13.5px; font-weight: 700; color: var(--ink); }
    .user-email { font-size: 11.5px; color: var(--slate); margin-top: 1px; }
    .user-phone { font-size: 11.5px; color: var(--slate); margin-top: 1px; }

    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-verified   { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-unverified { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-active     { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-blocked    { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-accepted   { background: var(--sky);             color: var(--blue); border: 1px solid var(--sky2); }
    .badge-pending    { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }

    .wh-count { display: inline-flex; align-items: center; gap: 5px; background: var(--sky); color: var(--blue); border: 1px solid var(--sky2); border-radius: 20px; padding: 3px 10px; font-size: 12px; font-weight: 700; }

    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-view  { background: var(--sky); color: var(--blue); border: 1px solid var(--sky2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.15s; white-space: nowrap; }
    .btn-view:hover { background: var(--sky2); }
    .btn-verify { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.3); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-verify:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); }
    .btn-block  { background: rgba(239,68,68,0.08); color: #991b1b; border: 1px solid rgba(239,68,68,0.2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-block:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <h2>🏭 Owners List</h2>
    <p>Manage all registered warehouse owners.</p>
</div>

@php
    $total      = $owners->count();
    $verified   = $owners->where('is_verified', true)->count();
    $unverified = $owners->where('is_verified', false)->count();
    $blocked    = $owners->where('is_blocked', true)->count();
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">🏭</div>
        <div><div class="stat-value">{{ $total }}</div><div class="stat-label">Total Owners</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green">✅</div>
        <div><div class="stat-value">{{ $verified }}</div><div class="stat-label">Verified</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow">⏳</div>
        <div><div class="stat-value">{{ $unverified }}</div><div class="stat-label">Unverified</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-red">🚫</div>
        <div><div class="stat-value">{{ $blocked }}</div><div class="stat-label">Blocked</div></div>
    </div>
</div>

<div class="search-bar">
    <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by name or email...">
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>Owner</th>
                <th>Phone</th>
                <th>Warehouses</th>
                <th>Verified</th>
                <th>Agreement</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
            <tr class="owner-row">
                <td style="font-family:monospace;font-weight:700;color:var(--blue);">#{{ $owner->id }}</td>

                <td>
                    <div class="user-cell">
                        <div class="user-avatar">{{ strtoupper(substr($owner->name,0,1)) }}</div>
                        <div>
                            <div class="user-name">{{ $owner->name }}</div>
                            <div class="user-email">{{ $owner->email }}</div>
                        </div>
                    </div>
                </td>

                <td style="color:var(--slate);font-size:12.5px;">{{ $owner->phone ?? '—' }}</td>

                <td>
                    <span class="wh-count">🏢 {{ $owner->warehouses->count() }}</span>
                </td>

                <td>
                    @if($owner->is_verified)
                        <span class="badge badge-verified">✓ Verified</span>
                    @else
                        <span class="badge badge-unverified">⏳ Pending</span>
                    @endif
                </td>

                <td>
                    @if($owner->agreement_accepted)
                        <span class="badge badge-accepted">✓ Accepted</span>
                    @else
                        <span class="badge badge-pending">⏳ Pending</span>
                    @endif
                </td>

                <td>
                    @if($owner->is_blocked)
                        <span class="badge badge-blocked">🚫 Blocked</span>
                    @else
                        <span class="badge badge-active">● Active</span>
                    @endif
                </td>

                <td style="color:var(--slate);font-size:12px;">{{ $owner->created_at->format('d M Y') }}</td>

                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.users.verifyView', $owner->id) }}" class="btn-view">📄 Docs</a>

                        @if(!$owner->is_verified)
                            <form action="{{ route('admin.users.verifyFinal', $owner->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-verify">✓ Verify</button>
                            </form>
                        @endif

                        @if(!$owner->is_blocked)
                            <form action="{{ route('admin.users.block', $owner->id) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Block {{ $owner->name }}?')">
                                @csrf
                                <button class="btn-block">🚫 Block</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon">🏭</div>
                        <p>No owners registered yet.</p>
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
        document.querySelectorAll('.owner-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>

@endsection
