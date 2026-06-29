<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0d1117; --ink2: #1c2b3a;
            --blue: #2563eb; --blue2: #1d4ed8;
            --sky: #eff6ff; --sky2: #dbeafe;
            --gold: #f59e0b; --emerald: #10b981;
            --slate: #64748b; --border: #e4e9f0;
            --bg: #f9fafb; --white: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 250px; height: 100vh;
            background: var(--ink2);
            display: flex; flex-direction: column;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.12);
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; gap: 10px;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: var(--blue); border-radius: 9px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .brand-text { font-size: 14px; font-weight: 800; color: #fff; line-height: 1.2; }
        .brand-sub  { font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 2px; }

        .sidebar-user {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; gap: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px; background: var(--blue);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-weight: 800; font-size: 14px; color: #fff; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 700; color: #fff; }
        .user-role { font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 1px; }

        .sidebar-nav { flex: 1; padding: 14px 10px; overflow-y: auto; }

        .nav-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            padding: 8px 10px 4px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px; font-weight: 600;
            transition: all 0.18s; margin-bottom: 2px;
        }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .nav-item.active { background: var(--blue); color: #fff; box-shadow: 0 2px 10px rgba(37,99,235,0.35); }
        .nav-icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-item.logout-btn { color: rgba(255,100,100,0.75); margin-top: 6px; }
        .nav-item.logout-btn:hover { background: rgba(239,68,68,0.12); color: #f87171; }

        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 11px; color: rgba(255,255,255,0.25); text-align: center;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            position: fixed; top: 0; left: 250px; right: 0; height: 62px;
            background: var(--white); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; z-index: 99;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .page-title  { font-size: 15px; font-weight: 700; color: var(--ink); }
        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .notif-wrapper { position: relative; }
        .notif-btn {
            background: var(--sky); border: 1px solid var(--sky2);
            color: var(--blue); width: 34px; height: 34px;
            border-radius: 8px; cursor: pointer; font-size: 15px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.18s; position: relative;
        }
        .notif-btn:hover { background: var(--sky2); }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: #fff;
            font-size: 10px; font-weight: 700;
            width: 17px; height: 17px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--white);
        }
        .notif-dropdown {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 310px; background: var(--white);
            border: 1px solid var(--border); border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            display: none; z-index: 200; overflow: hidden;
        }
        .notif-dropdown.open { display: block; }
        .notif-header { padding: 12px 16px; font-size: 13px; font-weight: 700; color: var(--ink); border-bottom: 1px solid var(--border); }
        .notif-item { padding: 11px 16px; border-bottom: 1px solid var(--border); font-size: 13px; color: var(--slate); transition: background 0.15s; }
        .notif-item:hover { background: var(--sky); }
        .notif-item.unread { color: var(--ink); font-weight: 600; }
        .notif-item small { display: block; color: #94a3b8; font-size: 11px; margin-top: 2px; }
        .notif-footer { padding: 10px 16px; text-align: center; }
        .notif-footer a { color: var(--blue); font-size: 13px; font-weight: 600; text-decoration: none; }

        .profile-chip {
            display: flex; align-items: center; gap: 8px;
            background: var(--sky); border: 1px solid var(--sky2);
            border-radius: 20px; padding: 5px 14px 5px 6px;
            text-decoration: none; transition: all 0.18s;
        }
        .profile-chip:hover { background: var(--sky2); }
        .chip-avatar {
            width: 26px; height: 26px; background: var(--blue);
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 11px; font-weight: 800; color: #fff;
        }
        .chip-name { font-size: 13px; font-weight: 600; color: var(--ink); }

        /* ===== MAIN CONTENT ===== */
        .main-content { margin-left: 250px; padding-top: 62px; min-height: 100vh; }
        .content-inner { padding: 28px 32px; }

        .flash-success {
            background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.25);
            color: #065f46; padding: 12px 16px; border-radius: 8px;
            margin-bottom: 18px; font-size: 14px; font-weight: 500;
        }
        .flash-error {
            background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25);
            color: #991b1b; padding: 12px 16px; border-radius: 8px;
            margin-bottom: 18px; font-size: 14px; font-weight: 500;
        }
        .flash-warning {
            background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.25);
            color: #92400e; padding: 12px 16px; border-radius: 8px;
            margin-bottom: 18px; font-size: 14px; font-weight: 500;
        }

        .toast {
            position: fixed; top: 76px; right: 24px;
            background: var(--emerald); color: #fff;
            padding: 12px 20px; border-radius: 10px;
            box-shadow: 0 8px 24px rgba(16,185,129,0.3);
            z-index: 9999; font-size: 14px; font-weight: 600;
            animation: slideIn 0.35s ease, fadeOut 0.35s ease 3.2s forwards;
        }
        @keyframes slideIn { from { transform: translateX(110%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { to { transform: translateX(110%); opacity: 0; } }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--sky2); border-radius: 10px; }
    </style>
</head>
<body>

@php
    $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
    $notifications       = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
    $initials            = strtoupper(substr(auth()->user()->name, 0, 1));
@endphp

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                <path d="M3 9.5L12 4l9 5.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z" stroke="#fff" stroke-width="1.7" fill="rgba(255,255,255,.15)"/>
                <rect x="9" y="13" width="6" height="8" rx="1" fill="#fff" opacity=".9"/>
            </svg>
        </div>
        <div>
            <div class="brand-text">WarehouseHub</div>
            <div class="brand-sub">Admin Portal</div>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">{{ $initials }}</div>
        <div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">Administrator</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main Menu</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Users
        </a>
        <a href="{{ route('admin.owners.index') }}" class="nav-item {{ request()->routeIs('admin.owners.*') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> Owners
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <span class="nav-icon">🧑‍🤝‍🧑</span> Customers
        </a>

        <div class="nav-label" style="margin-top:6px;">Warehouses</div>

        <a href="{{ route('admin.warehouses.pending') }}" class="nav-item {{ request()->routeIs('admin.warehouses.*') && !request()->routeIs('admin.warehouses.approved') ? 'active' : '' }}">
            <span class="nav-icon">🏢</span> Warehouses
        </a>
        <a href="{{ route('admin.warehouses.approved') }}" class="nav-item {{ request()->routeIs('admin.warehouses.approved') ? 'active' : '' }}">
            <span class="nav-icon">✅</span> Active Warehouses
        </a>

        <div class="nav-label" style="margin-top:6px;">Finance</div>

        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="nav-icon">📝</span> Orders
        </a>
        <a href="{{ route('admin.balances') }}" class="nav-item {{ request()->routeIs('admin.balances') ? 'active' : '' }}">
            <span class="nav-icon">💰</span> Balances
        </a>
        <a href="{{ route('admin.refunds.index') }}" class="nav-item {{ request()->routeIs('admin.refunds.*') ? 'active' : '' }}">
            <span class="nav-icon">↩️</span> Refunds
        </a>

        <div class="nav-label" style="margin-top:6px;">Support</div>

        <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Reports
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <span class="nav-icon">🔔</span> Notifications
            @if($unreadNotifications)
                <span style="margin-left:auto;background:#ef4444;color:#fff;border-radius:20px;padding:1px 8px;font-size:11px;font-weight:700;">{{ $unreadNotifications }}</span>
            @endif
        </a>
       
        <a href="{{ route('logout') }}" class="nav-item logout-btn"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="nav-icon">🚪</span> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </nav>

    <div class="sidebar-footer">© {{ date('Y') }} WarehouseHub</div>
</div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <span class="page-title">Admin Panel</span>
    </div>
    <div class="topbar-right">
        <div class="notif-wrapper">
            <button class="notif-btn" id="notifBtn">
                🔔
                @if($unreadNotifications)
                    <span class="notif-badge">{{ $unreadNotifications }}</span>
                @endif
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">🔔 Notifications</div>
                @forelse($notifications as $notif)
                    <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                        {{ $notif->message }}
                        <small>{{ $notif->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <div class="notif-item">No new notifications</div>
                @endforelse
                <div class="notif-footer">
                    <a href="{{ route('admin.notifications.index') }}">View All →</a>
                </div>
            </div>
        </div>

        <a href="#" class="profile-chip">
            <div class="chip-avatar">{{ $initials }}</div>
            <span class="chip-name">{{ auth()->user()->name }}</span>
        </a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="content-inner">
        @if(session('success'))
            <div class="flash-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">❌ {{ session('error') }}</div>
        @endif
        @if(!empty($flaggedWarehouses) && $flaggedWarehouses > 0)
            <div class="flash-warning">⚠️ {{ $flaggedWarehouses }} warehouse(s) are inactive.</div>
        @endif

        @yield('content')
    </div>
</div>

@if(session('success'))
    <div class="toast" id="toast">✅ {{ session('success') }}</div>
@endif

<script>
    const notifBtn      = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    notifBtn.addEventListener('click', (e) => { e.stopPropagation(); notifDropdown.classList.toggle('open'); });
    document.addEventListener('click', () => notifDropdown.classList.remove('open'));

    setTimeout(() => { const t = document.getElementById('toast'); if (t) t.remove(); }, 3600);

    (function pollUnreadMessages() {
        fetch('/admin/unread-count')
            .then(r => r.ok ? r.json() : {count: 0})
            .then(data => {
                const badge = document.getElementById('chat-unread-badge');
                if (badge) {
                    badge.textContent   = data.count;
                    badge.style.display = data.count > 0 ? 'inline' : 'none';
                }
            }).catch(() => {});
        setTimeout(pollUnreadMessages, 5000);
    })();
</script>
</body>
</html>
