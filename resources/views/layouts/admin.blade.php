<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { display: flex; margin: 0; font-family: Arial, sans-serif; }
        .sidebar { width: 220px; background: #2c3e50; color: white; min-height: 100vh; padding: 20px; }
        .sidebar a { color: white; display: block; padding: 10px; text-decoration: none; margin-bottom: 5px; }
        .sidebar a:hover { background: #34495e; }
        .main-content { flex: 1; padding: 20px; }
        .notification { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .btn { padding: 5px 10px; background: #3490dc; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .btn:hover { background: #2779bd; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.reports.index') }}">Reports</a>
        <a href="{{ route('admin.notifications.index') }}">Notifications</a>
        <a href="{{ route('admin.settings.index') }}">Settings</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="btn">Logout</button>
        </form>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>
