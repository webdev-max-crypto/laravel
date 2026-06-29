@extends('layouts.owner')

@section('content')
<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .btn-primary-custom {
        background: var(--blue); color: #fff;
        padding: 9px 18px; border-radius: 8px;
        text-decoration: none; font-size: 13px; font-weight: 700;
        transition: background 0.18s, transform 0.1s; border: none; cursor: pointer;
        font-family: inherit;
    }
    .btn-primary-custom:hover { background: var(--blue2); transform: translateY(-1px); color: #fff; }

    .table-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead tr { background: var(--sky); }
    .table-card thead th { padding: 13px 16px; font-size: 11.5px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; border-bottom: 1.5px solid var(--sky2); white-space: nowrap; }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .table-card tbody tr:hover { background: var(--sky); }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody td { padding: 13px 16px; font-size: 13px; color: var(--ink); vertical-align: middle; }

    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; white-space: nowrap; }
    .badge-approved  { background: rgba(16,185,129,0.1);  color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .badge-pending   { background: rgba(245,158,11,0.1);  color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .badge-rejected  { background: rgba(239,68,68,0.08);  color: #991b1b; border: 1px solid rgba(239,68,68,0.2); }

    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-view   { background: var(--sky);  color: var(--blue); border: 1px solid var(--sky2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.15s; }
    .btn-view:hover { background: var(--sky2); }
    .btn-edit   { background: var(--blue); color: #fff; border: none; padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; transition: background 0.15s; }
    .btn-edit:hover { background: var(--blue2); color: #fff; }
    .btn-delete { background: rgba(239,68,68,0.08); color: #991b1b; border: 1px solid rgba(239,68,68,0.2); padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-delete:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
</style>

<div class="page-header">
    <h2>🏢 My Warehouses</h2>
    <a href="{{ route('owner.warehouses.create') }}" class="btn-primary-custom">+ Add New Warehouse</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($warehouses as $w)
            <tr>
                <td style="font-weight:700;">{{ $w->name }}</td>
                <td style="color:var(--slate);">{{ Str::limit($w->location, 45) }}</td>
                <td>
                    @if($w->status == 'approved')
                        <span class="badge badge-approved">✓ Approved</span>
                    @elseif($w->status == 'pending')
                        <span class="badge badge-pending">⏳ Pending</span>
                    @else
                        <span class="badge badge-rejected">✕ Rejected</span>
                    @endif
                </td>
                <td>
                    @if($w->image)
                        <img src="{{ asset($w->image) }}" width="80" height="56" style="border-radius:8px;object-fit:cover;border:1px solid var(--border);">
                    @else
                        <span style="color:var(--slate);font-size:12px;">No image</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('owner.warehouses.show', $w->id) }}" class="btn-view">View</a>
                        <a href="{{ route('owner.warehouses.edit', $w->id) }}" class="btn-edit">Edit</a>
                        <form action="{{ route('owner.warehouses.destroy', $w->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this warehouse?')">
                            @csrf @method('DELETE')
                            <button class="btn-delete">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">🏗️</div>
                        <p>No warehouses yet. Add your first one!</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
