<!-- resources/views/dashboard.blade.php -->
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
        .header {
            background: #1f2937;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header a, .header button {
            margin-left: 10px;
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }
        .header a.edit { background: #3b82f6; }
        .header button.delete { background: #ef4444; }
        .header a.logout { background: #dc2626; }
        .content {
            padding: 30px;
        }
    </style>
</head>
<body>

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


    <div class="content">
        <h3>Welcome, {{ auth()->user()->name }} 👋</h3>
        <p>You are logged in as a Customer.</p>
    </div>

</body>
</html>
