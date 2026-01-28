<!DOCTYPE html>
<html>
<head>
    <title>Customer Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin:0; padding:0; background:#f3f4f6; }

        .header {
            background: #1f2937;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top:0;
            z-index:1000;
        }

        .header a { margin-left:10px; padding:8px 14px; border-radius:6px; text-decoration:none; color:white; }
        .edit { background:#3b82f6; }
        .logout { background:#dc2626; }

        /* Notification Dropdown */
        .notif-btn { position:relative; background:#f59e0b; border:none; color:white; padding:8px 12px; border-radius:6px; cursor:pointer; }
        .notif-btn .badge { position:absolute; top:-5px; right:-5px; background:red; color:white; padding:2px 6px; border-radius:50%; font-size:12px; }
        .notif-dropdown {
            position:absolute; top:40px; right:0; width:300px; background:white; color:#111; border-radius:6px;
            box-shadow:0 2px 6px rgba(0,0,0,0.2); display:none; z-index:1001;
        }
        .notif-dropdown.active { display:block; }
        .notif-item { padding:10px 15px; border-bottom:1px solid #eee; }
        .notif-item.unread { font-weight:bold; }
        .notif-item small { display:block; color:#888; margin-top:4px; font-size:12px; }
        .notif-footer { text-align:center; padding:10px; background:#050506; }

        .content { padding:30px; }
    </style>
</head>
<body>

@php
$unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
$notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get();
@endphp

<div class="header">
    <h2>Customer Panel</h2>
    <div style="display:flex; align-items:center; gap:10px; position:relative;">

        <!-- Notifications -->
        <button class="notif-btn" id="notifBtn">
            🔔
            @if($unreadNotifications)
                <span class="badge">{{ $unreadNotifications }}</span>
            @endif
        </button>
        <div class="notif-dropdown" id="notifDropdown">
            @forelse($notifications as $notif)
                <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                    {{ $notif->message }}
                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                    @if(!$notif->is_read)
                        <a href="{{ route('customer.notifications.read', $notif->id) }}" style="font-size:12px;color:#3b82f6;">Mark as read</a>
                    @endif
                </div>
            @empty
                <div class="notif-item text-muted">No notifications</div>
            @endforelse
            <div class="notif-footer"><a href="{{ route('customer.notifications.index') }}">View All</a></div>
        </div>

        <!-- Edit Profile -->
        <a href="{{ route('customer.edit') }}" class="edit">Edit Profile</a>

        <!-- Logout -->
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="logout">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</div>

<div class="content">
    @yield('content')
</div>

<script>
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');

    notifBtn.addEventListener('click', () => { notifDropdown.classList.toggle('active'); });

    document.addEventListener('click', function(event) {
        if (!notifBtn.contains(event.target) && !notifDropdown.contains(event.target)) {
            notifDropdown.classList.remove('active');
        }
    });
</script>

</body>
</html>
