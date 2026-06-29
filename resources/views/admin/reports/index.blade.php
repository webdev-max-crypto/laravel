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
    .icon-blue { background: var(--sky); }
    .icon-red  { background: rgba(239,68,68,0.08); }
    .icon-green{ background: rgba(16,185,129,0.1); }
    .stat-value { font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
    .stat-label { font-size: 11.5px; color: var(--slate); font-weight: 600; margin-top: 3px; }

    .search-bar { margin-bottom: 18px; }
    .search-input { width: 100%; max-width: 320px; padding: 9px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.18s; background: var(--white); }
    .search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 12px 16px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 12px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .report-id { font-family: monospace; font-weight: 800; color: var(--blue); }
    .name-bold  { font-weight: 700; }
    .msg-text   { font-size: 12.5px; color: var(--slate); max-width: 220px; line-height: 1.5; }

    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-active  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-blocked { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }

    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-block  { background: rgba(245,158,11,0.1); color: #92400e; border: 1px solid rgba(245,158,11,0.3); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-block:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
    .btn-delete { background: rgba(239,68,68,0.08); color: #991b1b; border: 1px solid rgba(239,68,68,0.2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-delete:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }

    .pagination-wrap { padding: 14px 16px; border-top: 1px solid var(--border); }
</style>

<div class="page-header">
    <h2>📊 Fraud Reports</h2>
    <p>Review and act on customer-submitted warehouse reports.</p>
</div>

@php
    $total   = $reports->total();
    $blocked = $reports->getCollection()->filter(fn($r) => optional($r->warehouse)->status === 'blocked')->count();
    $active  = $total - $blocked;
@endphp

<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon icon-blue">📊</div><div><div class="stat-value">{{ $total }}</div><div class="stat-label">Total Reports</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-green">✅</div><div><div class="stat-value">{{ $active }}</div><div class="stat-label">Active</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-red">🚫</div><div><div class="stat-value">{{ $blocked }}</div><div class="stat-label">Blocked</div></div></div>
</div>

<div class="search-bar">
    <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by warehouse or reporter...">
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Warehouse</th>
                <th>Reported By</th>
                <th>Message</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr class="report-row">
                <td><span class="report-id">#{{ $report->id }}</span></td>
                <td><span class="name-bold">{{ optional($report->warehouse)->name ?? '—' }}</span></td>
                <td>
                    <span class="name-bold">{{ optional($report->user)->name ?? '—' }}</span><br>
                    <span style="font-size:11.5px;color:var(--slate);">{{ optional($report->user)->email ?? '' }}</span>
                </td>
                <td><div class="msg-text">{{ Str::limit($report->message, 80) }}</div></td>
                <td>
                    @if(optional($report->warehouse)->status === 'blocked')
                        <span class="badge badge-blocked">🚫 Blocked</span>
                    @else
                        <span class="badge badge-active">● Active</span>
                    @endif
                </td>
                <td style="color:var(--slate);font-size:12px;">{{ $report->created_at->format('d M Y') }}</td>
                <td>
                    <div class="action-btns">
                        <form action="{{ route('admin.warehouse.block', optional($report->warehouse)->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn-block">⚠️ Block</button>
                        </form>
                        <form action="{{ route('admin.warehouse.delete', optional($report->warehouse)->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete this warehouse permanently?')">
                            @csrf @method('DELETE')
                            <button class="btn-delete">🗑️ Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><div class="empty-icon">✅</div><p>No fraud reports submitted.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>

    @if($reports->hasPages())
        <div class="pagination-wrap">{{ $reports->links() }}</div>
    @endif
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.report-row').forEach(r => { r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none'; });
    });
</script>
@endsection
