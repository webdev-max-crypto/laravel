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

    /* Send refund card */
    .send-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin-bottom: 24px; }
    .card-header { padding: 14px 20px; background: var(--sky); border-bottom: 1px solid var(--sky2); display: flex; align-items: center; gap: 10px; }
    .card-icon { width: 32px; height: 32px; background: var(--blue); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
    .card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .card-body { padding: 20px; }

    .send-grid { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px; align-items: end; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group label { font-size: 12px; font-weight: 700; color: var(--ink); }
    .form-control { width: 100%; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--ink); outline: none; transition: border-color 0.15s; background: var(--white); }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    select.form-control { cursor: pointer; }
    .btn-send { background: var(--blue); color: #fff; padding: 9px 20px; border-radius: 8px; border: none; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap; }
    .btn-send:hover { background: var(--blue2); }

    /* Table */
    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 12px 14px; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 12px 14px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .booking-id { font-weight: 800; color: var(--blue); font-family: monospace; }
    .amount-val { font-weight: 800; color: #065f46; }
    .name-bold  { font-weight: 700; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .badge-pending  { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-approved { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-rejected { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }
    .badge-role     { background: var(--sky); color: var(--blue); border: 1px solid var(--sky2); }

    .action-wrap { display: flex; flex-direction: column; gap: 6px; min-width: 200px; }
    .action-row  { display: flex; gap: 6px; }
    .btn-approve { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.3); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: 'Plus Jakarta Sans', sans-serif; }
    .btn-approve:hover { background: var(--emerald); color: #fff; border-color: var(--emerald); }
    .btn-reject  { background: rgba(239,68,68,0.08); color: #991b1b; border: 1px solid rgba(239,68,68,0.2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: 'Plus Jakarta Sans', sans-serif; }
    .btn-reject:hover { background: #ef4444; color: #fff; border-color: #ef4444; }
    .note-input { flex: 1; padding: 5px 10px; border: 1.5px solid var(--border); border-radius: 7px; font-size: 12px; font-family: 'Plus Jakarta Sans', sans-serif; outline: none; }
    .note-input:focus { border-color: var(--blue); }
    .to-select { padding: 5px 8px; border: 1.5px solid var(--border); border-radius: 7px; font-size: 12px; font-family: 'Plus Jakarta Sans', sans-serif; outline: none; cursor: pointer; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }

    .pagination-wrap { padding: 14px 16px; border-top: 1px solid var(--border); }

    @media(max-width:700px) { .send-grid { grid-template-columns: 1fr 1fr; } }
</style>

<div class="page-header">
    <div>
        <h2>↩️ Refund Management</h2>
        <p>Review, approve, reject, or initiate refunds for all bookings.</p>
    </div>
</div>

@php
    $pending  = $refunds->where('status','pending')->count();
    $approved = $refunds->where('status','approved')->count();
    $rejected = $refunds->where('status','rejected')->count();
@endphp

<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon icon-blue">↩️</div><div><div class="stat-value">{{ $refunds->total() }}</div><div class="stat-label">Total Requests</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-yellow">⏳</div><div><div class="stat-value">{{ $pending }}</div><div class="stat-label">Pending</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-green">✅</div><div><div class="stat-value">{{ $approved }}</div><div class="stat-label">Approved</div></div></div>
    <div class="stat-card"><div class="stat-icon icon-red">✕</div><div><div class="stat-value">{{ $rejected }}</div><div class="stat-label">Rejected</div></div></div>
</div>

{{-- Send Refund Directly --}}
<div class="send-card">
    <div class="card-header">
        <div class="card-icon">💸</div>
        <h3>Send Refund Directly</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.refunds.send') }}" method="POST">
            @csrf
            <div class="send-grid">
                <div class="form-group">
                    <label>Booking ID</label>
                    <input type="number" name="booking_id" class="form-control" placeholder="e.g. 12" required>
                </div>
                <div class="form-group">
                    <label>Send To</label>
                    <select name="to_role" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="customer">Customer</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Amount (PKR)</label>
                    <input type="number" name="amount" class="form-control" placeholder="e.g. 5000" min="1" required>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control" placeholder="Reason for refund" required>
                </div>
            </div>
            <div style="margin-top:12px;text-align:right;">
                <button type="submit" class="btn-send">💸 Send Refund</button>
            </div>
        </form>
    </div>
</div>

{{-- Refunds Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Booking</th>
                <th>From</th>
                <th>To</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($refunds as $r)
            <tr>
                <td><span class="booking-id">#{{ $r->id }}</span></td>
                <td>
                    <span class="booking-id">#{{ $r->booking_id }}</span><br>
                    <small style="color:var(--slate);">{{ optional(optional($r->booking)->warehouse)->name ?? '—' }}</small>
                </td>
                <td>
                    <span class="name-bold">{{ optional($r->requester)->name ?? '—' }}</span><br>
                    <span class="badge badge-role" style="margin-top:3px;">{{ ucfirst($r->from_role) }}</span>
                </td>
                <td>
                    <span class="name-bold">{{ optional($r->receiver)->name ?? '—' }}</span><br>
                    <span class="badge badge-role" style="margin-top:3px;">{{ ucfirst($r->to_role) }}</span>
                </td>
                <td><span class="amount-val">Rs {{ number_format($r->amount, 0) }}</span></td>
                <td style="max-width:160px;font-size:12px;color:var(--slate);">{{ Str::limit($r->reason, 60) }}</td>
                <td>
                    @if($r->status === 'pending')
                        <span class="badge badge-pending">⏳ Pending</span>
                    @elseif($r->status === 'approved')
                        <span class="badge badge-approved">✓ Approved</span>
                    @else
                        <span class="badge badge-rejected">✕ Rejected</span>
                    @endif
                    @if($r->admin_note)
                        <div style="font-size:11px;color:var(--slate);margin-top:3px;">{{ Str::limit($r->admin_note,40) }}</div>
                    @endif
                </td>
                <td style="font-size:12px;color:var(--slate);">{{ $r->created_at->format('d M Y') }}</td>
                <td>
                    @if($r->status === 'pending')
                        <div class="action-wrap">
                            <form action="{{ route('admin.refunds.approve', $r->id) }}" method="POST">
                                @csrf
                                <div class="action-row">
                                    <select name="to_role" class="to-select" required>
                                        <option value="customer">→ Customer</option>
                                        <option value="owner">→ Owner</option>
                                    </select>
                                    <button class="btn-approve">✓</button>
                                </div>
                                <input type="text" name="admin_note" class="note-input" placeholder="Note (optional)" style="width:100%;margin-top:5px;">
                            </form>
                            <form action="{{ route('admin.refunds.reject', $r->id) }}" method="POST">
                                @csrf
                                <div class="action-row">
                                    <input type="text" name="admin_note" class="note-input" placeholder="Rejection reason" required>
                                    <button class="btn-reject">✕</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <span style="color:var(--slate);font-size:12px;">{{ $r->processed_at?->format('d M Y') ?? '—' }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-icon">↩️</div>
                        <p>No refund requests yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($refunds->hasPages())
        <div class="pagination-wrap">{{ $refunds->links() }}</div>
    @endif
</div>

@endsection
