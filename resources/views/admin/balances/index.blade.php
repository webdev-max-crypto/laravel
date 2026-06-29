@extends('admin.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 22px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .banner-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    .banner-card {
        border-radius: 14px; padding: 24px 28px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        position: relative; overflow: hidden;
    }
    .banner-card.blue { background: linear-gradient(130deg,#1e40af,#2563eb); }
    .banner-card.green { background: linear-gradient(130deg,#065f46,#10b981); }
    .banner-card::after { content: attr(data-icon); position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 60px; opacity: 0.12; }
    .banner-label { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 6px; }
    .banner-amount { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 10px; }
    .banner-sub { display: flex; gap: 20px; flex-wrap: wrap; }
    .banner-sub-item { font-size: 12.5px; color: rgba(255,255,255,0.75); }
    .banner-sub-item strong { color: #fff; }

    .section-title { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

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

    .owner-cell { display: flex; align-items: center; gap: 10px; }
    .owner-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .owner-name  { font-size: 13px; font-weight: 700; color: var(--ink); }
    .owner-email { font-size: 11.5px; color: var(--slate); margin-top: 1px; }
    .balance-val { font-weight: 800; color: #065f46; font-size: 14px; }
    .zero-val    { color: var(--slate); font-weight: 600; }

    .empty-state { text-align: center; padding: 50px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }

    @media(max-width:640px) { .banner-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <h2>💰 Financial Dashboard</h2>
    <p>Platform-wide commission and owner balance overview.</p>
</div>

<div class="banner-grid">
    <div class="banner-card blue" data-icon="🛡️">
        <div class="banner-label">Admin Commission</div>
        <div class="banner-amount">Rs {{ number_format($totalAdminCommission, 0) }}</div>
        <div class="banner-sub">
            <div class="banner-sub-item">Pending: <strong>Rs {{ number_format($adminCommissionPending, 0) }}</strong></div>
            <div class="banner-sub-item">Released: <strong>Rs {{ number_format($adminCommissionReleased, 0) }}</strong></div>
        </div>
    </div>
    <div class="banner-card green" data-icon="🏭">
        <div class="banner-label">Owner Balances</div>
        <div class="banner-amount">Rs {{ number_format($totalOwnerBalance, 0) }}</div>
        <div class="banner-sub">
            <div class="banner-sub-item">Pending: <strong>Rs {{ number_format($ownerBalancePending, 0) }}</strong></div>
            <div class="banner-sub-item">Released: <strong>Rs {{ number_format($ownerBalanceReleased, 0) }}</strong></div>
        </div>
    </div>
</div>

<div class="section-title">🏭 Owner Balances</div>

<div class="search-bar">
    <input type="text" class="search-input" id="searchInput" placeholder="🔍 Search by owner name...">
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Owner</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
            <tr class="owner-row">
                <td style="font-family:monospace;font-weight:700;color:var(--blue);">#{{ $owner->id }}</td>
                <td>
                    <div class="owner-cell">
                        <div class="owner-avatar">{{ strtoupper(substr($owner->name,0,1)) }}</div>
                        <div>
                            <div class="owner-name">{{ $owner->name }}</div>
                            <div class="owner-email">{{ $owner->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if(($owner->balance ?? 0) > 0)
                        <span class="balance-val">Rs {{ number_format($owner->balance, 0) }}</span>
                    @else
                        <span class="zero-val">Rs 0</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="3"><div class="empty-state"><div class="empty-icon">💰</div><p>No owner balance data.</p></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.owner-row').forEach(r => { r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none'; });
    });
</script>
@endsection
