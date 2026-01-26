<!DOCTYPE html>
<html>
<head>
    <title>Customer Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f4f6;
        }

        /* HEADER */
        .header {
            background: #1f2937;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header a {
            margin-left: 10px;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
        }

        .edit { background: #3b82f6; }
        .logout { background: #dc2626; }

        /* CONTENT */
        .content {
            padding: 30px;
        }

        /* WAREHOUSE CARDS */
        .warehouse-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .warehouse-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .warehouse-card h4 {
            margin-top: 0;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn-book { background: #16a34a; }
        .btn-report { background: #ef4444; margin-left: 10px; }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>Customer Panel</h2>
    <div>
        <a href="{{ route('customer.edit') }}" class="edit">Edit Profile</a>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="logout">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <h3>Welcome, {{ auth()->user()->name }} 👋</h3>
    <p>Available Warehouses for Booking</p>

    @if($warehouses->isEmpty())
        <p>No warehouses available right now.</p>
    @endif

    <div class="warehouse-grid">
        @foreach($warehouses as $warehouse)
            <div class="warehouse-card">
    <h4>{{ $warehouse->name }}</h4>
    <p><strong>Location:</strong> {{ $warehouse->location }}</p>
    <p><strong>Size:</strong> {{ $warehouse->size }}</p>
    <p><strong>Contact:</strong> {{ $warehouse->contact }}</p>
    <p><strong>Price per Month:</strong> <span style="color:#16a34a;font-weight:600;">
    {{ $warehouse->price_per_month }} / month
</span></p>


    <a href="{{ route('customer.booking.create', $warehouse->id) }}" class="btn btn-book">Book Area</a>
    <a href="#" class="btn btn-report">Report</a>
</div>
        @endforeach
    </div>

</div>

</body>
</html>
