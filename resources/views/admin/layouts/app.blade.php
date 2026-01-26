<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Sidebar Styling (like Owner panel) */
        .col-2.bg-light {
            background: #0d1117 !important;
            height: 100vh;
            color: #fff;
            border-right: 1px solid #222;
            padding-top: 20px;
        }

        .col-2.bg-light .nav-link {
            color: #c9d1d9;
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 6px;
            transition: 0.3s ease;
            font-size: 15px;
        }

        .col-2.bg-light .nav-link:hover {
            background: #21262d;
            color: #fff;
            transform: translateX(4px);
        }

        .col-2.bg-light .active-link {
            background: #238636 !important;
            color: #fff !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
    <div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-sm btn-danger">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid mt-4">
  <div class="row">
    <!-- SIDEBAR -->
    <div class="col-2 bg-light p-3">

      <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}" href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active-link' : '' }}" href="{{ route('admin.users.index') }}">👥 Users</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.warehouses.*') ? 'active-link' : '' }}" href="{{ route('admin.warehouses.pending') }}">🏢 Warehouses</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active-link' : '' }}" href="{{ route('admin.payments.escrow') }}">💸 Payments</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.fraud.*') ? 'active-link' : '' }}" href="{{ route('admin.fraud.index') }}">⚠️ Fraud Reports</a>
        </li>

      </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="col-10">
      @if(session('success')) 
          <div class="alert alert-success">{{ session('success') }}</div> 
      @endif

      @if(session('error')) 
          <div class="alert alert-danger">{{ session('error') }}</div> 
      @endif

      @yield('content')
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
