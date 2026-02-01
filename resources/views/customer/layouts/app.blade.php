<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Panel</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            transition: margin-left 0.3s;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: #111827;
            color: #fff;
            transition: transform 0.3s;
        }

        .sidebar.collapsed {
            transform: translateX(-240px);
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            background: #1f2937;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-btn {
            cursor: pointer;
            font-size: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #1f2937;
        }

        .sidebar-menu li a {
            display: block;
            padding: 14px 20px;
            color: #d1d5db;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #374151;
            color: #fff;
        }

        /* ===== PAGE CONTENT ===== */
        .content-wrapper {
            margin-left: 240px;
            padding: 25px;
            transition: margin-left 0.3s;
        }

        .content-wrapper.expanded {
            margin-left: 0;
        }

        /* HEADER */
        .dashboard-header {
            background: #1f2937;
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .dashboard-header h2 {
            margin: 0;
            font-size: 20px;
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span>Customer</span>
            <span class="menu-btn" id="toggleSidebarBtn">☰</span>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('customer.dashboard') }}"
                   class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('customer.history') }}"
                   class="{{ request()->routeIs('customer.history') ? 'active' : '' }}">
                    📜 History
                </a>
            </li>

            <li>
                <a href="{{ route('customer.support') }}"
                   class="{{ request()->routeIs('customer.support') ? 'active' : '' }}">
                    🆘 Help & Support
                </a>
            </li>

            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="logout">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <!-- DASHBOARD HEADER -->
    <div class="dashboard-header">
        <h2>Customer Panel</h2>
        <span class="menu-btn" id="headerToggleBtn">☰</span>
    </div>

    <!-- PAGE CONTENT -->
    <div class="content-wrapper" id="contentWrapper">
        @yield('content')
    </div>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const contentWrapper = document.getElementById('contentWrapper');
    const toggleBtns = [document.getElementById('toggleSidebarBtn'), document.getElementById('headerToggleBtn')];

    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            contentWrapper.classList.toggle('expanded');
        });
    });
</script>

</body>
</html>
