@extends('customer.layouts.app')

@section('content')

@php
    $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
    $notifications       = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
@endphp

<style>
    .welcome-banner {
        background: linear-gradient(130deg, #1e40af 0%, #2563eb 100%);
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 28px;
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
        right: 28px;
        font-size: 72px;
        opacity: 0.12;
    }
    .welcome-banner h2 { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 4px; }
    .welcome-banner p  { font-size: 13.5px; color: rgba(255,255,255,0.7); }

    .section-title {
        font-size: 15px; font-weight: 800;
        color: var(--ink); margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-title::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    .warehouse-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .warehouse-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.2s;
    }
    .warehouse-card:hover {
        border-color: var(--blue);
        box-shadow: 0 12px 36px rgba(37,99,235,0.1);
        transform: translateY(-3px);
    }

    .card-header-bar {
        background: var(--sky);
        border-bottom: 1px solid var(--sky2);
        padding: 14px 18px;
        display: flex; align-items: center; gap: 10px;
    }
    .wh-icon {
        width: 34px; height: 34px;
        background: var(--blue);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
    }
    .card-header-bar h4 { font-size: 14px; font-weight: 800; color: var(--ink); }

    .card-body { padding: 16px 18px; }

    .card-info { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
    .info-row  { display: flex; justify-content: space-between; font-size: 13px; }
    .info-label { color: var(--slate); }
    .info-value { color: var(--ink); font-weight: 600; }
    .info-value.price { color: var(--blue); font-weight: 800; }

    .card-actions { display: flex; gap: 8px; }

    .btn-book {
        flex: 1;
        background: var(--blue);
        color: #fff;
        padding: 9px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px; font-weight: 700;
        text-align: center;
        transition: background 0.18s, transform 0.1s;
        border: none; cursor: pointer;
        font-family: inherit;
    }
    .btn-book:hover { background: var(--blue2); transform: translateY(-1px); }

    .report-toggle {
        background: transparent;
        border: 1.5px solid var(--border);
        color: var(--slate);
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all 0.18s;
        font-family: inherit;
    }
    .report-toggle:hover { border-color: #ef4444; color: #ef4444; }

    .report-form {
        display: none;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border);
    }
    .report-form.open { display: block; }

    .report-form textarea {
        width: 100%;
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        color: var(--ink);
        padding: 10px 12px;
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        resize: vertical; min-height: 70px;
        margin-bottom: 8px; outline: none;
        transition: border-color 0.18s;
    }
    .report-form textarea:focus { border-color: var(--blue); }

    .btn-report-submit {
        background: #ef4444; color: #fff;
        padding: 8px 16px; border-radius: 8px;
        border: none; font-size: 13px; font-weight: 700;
        cursor: pointer; transition: background 0.18s;
        font-family: inherit;
    }
    .btn-report-submit:hover { background: #dc2626; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--slate); }
    .empty-state .empty-icon { font-size: 44px; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; }
</style>

<div class="welcome-banner">
    <div>
        <h2>Welcome back, {{ auth()->user()->name }} 👋</h2>
        <p>Browse available warehouses and manage your bookings below.</p>
    </div>
</div>

<div class="section-title">🏢 Available Warehouses</div>

@if($warehouses->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🏗️</div>
        <p>No warehouses available right now. Check back soon!</p>
    </div>
@else
    <div class="warehouse-grid">
        @foreach($warehouses as $warehouse)
            <div class="warehouse-card">
                <div class="card-header-bar">
                    <div class="wh-icon">🏢</div>
                    <h4 style="flex:1;">{{ $warehouse->name }}</h4>
                    @if($warehouse->is_active)
                        <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(16,185,129,0.15);color:#065f46;border:1px solid rgba(16,185,129,0.3);border-radius:20px;padding:2px 9px;font-size:11px;font-weight:700;white-space:nowrap;">
                            <span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;"></span> Active
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;background:#f1f5f9;color:#64748b;border:1px solid #e4e9f0;border-radius:20px;padding:2px 9px;font-size:11px;font-weight:700;white-space:nowrap;">
                            <span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;display:inline-block;"></span> Inactive
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="card-info">
                        <div class="info-row">
                            <span class="info-label">📍 Location</span>
                            <span class="info-value">{{ $warehouse->location ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">📐 Size</span>
                            <span class="info-value">{{ $warehouse->size ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">📞 Contact</span>
                            <span class="info-value">{{ $warehouse->contact ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">💰 Price</span>
                            <span class="info-value price">Rs {{ number_format($warehouse->price_per_month) }} / mo</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('customer.booking.create', $warehouse->id) }}" class="btn-book">📦 Book Now</a>
                        <button class="report-toggle" onclick="toggleReport({{ $warehouse->id }})">🚩 Report</button>
                    </div>
                    <div class="report-form" id="report-{{ $warehouse->id }}">
                        <form action="{{ route('customer.report.store', $warehouse->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="">
                            <textarea name="message" placeholder="Describe the issue..." required></textarea>
                            <button type="submit" class="btn-report-submit">Submit Report</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<script>
    function toggleReport(id) {
        document.getElementById('report-' + id).classList.toggle('open');
    }
</script>

@endsection
