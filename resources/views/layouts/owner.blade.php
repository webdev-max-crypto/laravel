<!DOCTYPE html>
<html>
<head>
    <title>Owner Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar Styling */
        .sidebar {
            background: #0d1117;
            height: 100vh;
            position: fixed;
            width: 230px;
            padding-top: 20px;
            color: #fff;
            border-right: 1px solid #222;
        }

        .sidebar .nav-link {
            color: #c9d1d9;
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 6px;
            transition: 0.3s ease;
            font-size: 15px;
        }

        .sidebar .nav-link:hover {
            background: #21262d;
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar .active-link {
            background: #238636 !important;
            color: #fff !important;
        }

        /* Main Content shifted right */
        .main-content {
            margin-left: 230px;
            padding: 30px;
        }

        /* Top Navbar */
        .topbar {
            margin-left: 230px;
        }
    </style>
</head>

<body class="bg-light">

@php
// Fetch last 5 notifications for owner
$unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
    ->where('is_read', false)->count();

$notifications = \App\Models\Notification::where('user_id', auth()->id())
    ->latest()->take(5)->get();
@endphp

<!-- TOP NAVBAR -->
<nav class="navbar navbar-dark bg-dark w-100 fixed-top">
    <a class="navbar-brand">Owner Panel</a>

    <div class="d-flex align-items-center">

        <!-- NOTIFICATIONS DROPDOWN -->
        <div class="dropdown me-3">
            <button class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                🔔
                @if($unreadNotifications)
                    <span class="badge bg-danger">{{ $unreadNotifications }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2" style="width:300px;">
                @forelse($notifications as $notif)
                    <li class="dropdown-item {{ $notif->is_read ? '' : 'fw-bold' }}">
                        {{ $notif->message }}
                        <small class="d-block text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        @if(!$notif->is_read)
                            <a href="{{ route('owner.notifications.read', $notif->id) }}" class="btn btn-sm btn-primary mt-1">Mark as read</a>
                        @endif
                    </li>
                    <li><hr class="dropdown-divider"></li>
                @empty
                    <li class="dropdown-item text-muted">No notifications</li>
                @endforelse
                <li><a href="{{ route('owner.notifications.index') }}" class="dropdown-item text-center">View All</a></li>
            </ul>
        </div>

        <!-- USER DROPDOWN -->
        <div class="dropdown me-3">
            <button class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown">
                {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('owner.profile') }}">Profile Settings</a></li>
                <li><a class="dropdown-item text-danger" href="{{ route('owner.delete.confirm') }}">Delete Account</a></li>
            </ul>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
</nav>

<!-- LEFT SIDEBAR -->
<div class="sidebar mt-5">
    <ul class="nav flex-column px-2">
        <li><a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active-link' : '' }}">📊 Dashboard</a></li>
        <li><a href="{{ route('owner.profile') }}" class="nav-link {{ request()->routeIs('owner.profile') ? 'active-link' : '' }}">👤 Profile Settings</a></li>
        <li><a href="{{ route('owner.warehouses.index') }}" class="nav-link {{ request()->routeIs('owner.warehouses.*') ? 'active-link' : '' }}">🏢 My Warehouses</a></li>
        <li><a href="{{ route('owner.bookings') }}" class="nav-link {{ request()->routeIs('owner.bookings') ? 'active-link' : '' }}">📦 My Bookings</a></li>
        <li><a href="{{ route('owner.payments') }}" class="nav-link {{ request()->routeIs('owner.payments') ? 'active-link' : '' }}">💸 Payments</a></li>
        <li><a href="{{ route('owner.help') }}" class="nav-link {{ request()->routeIs('owner.help') ? 'active-link' : '' }}">🆘 Help & Support</a></li>
    </ul>
</div>

<!-- MAIN CONTENT -->
<div class="main-content mt-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
