@extends('customer.layouts.app')

@section('content')
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
    .page-header h2 { font-size:20px; font-weight:800; color:var(--ink); }
    .page-header p  { font-size:13px; color:var(--slate); margin-top:4px; }
    .back-btn { display:inline-flex; align-items:center; gap:6px; color:var(--slate); font-size:13px; font-weight:600; text-decoration:none; transition:color 0.15s; }
    .back-btn:hover { color:var(--blue); }

    .profile-layout { display:grid; grid-template-columns:240px 1fr; gap:20px; align-items:start; max-width:820px; }

    /* Avatar card */
    .avatar-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; padding:28px 20px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,0.04); position:sticky; top:80px; }
    .avatar-circle { width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg,#1e40af,#2563eb); display:flex; align-items:center; justify-content:center; font-size:34px; font-weight:800; color:#fff; margin:0 auto 14px; border:3px solid var(--sky2); }
    .avatar-name { font-size:15px; font-weight:800; color:var(--ink); margin-bottom:4px; }
    .avatar-email { font-size:12px; color:var(--slate); margin-bottom:12px; word-break:break-all; }
    .avatar-badge { display:inline-flex; align-items:center; gap:5px; background:var(--sky); color:var(--blue); border:1px solid var(--sky2); border-radius:20px; padding:3px 12px; font-size:12px; font-weight:700; }

    .danger-zone { margin-top:20px; padding-top:16px; border-top:1px solid var(--border); }
    .danger-title { font-size:11px; font-weight:700; color:var(--slate); text-transform:uppercase; letter-spacing:1px; margin-bottom:10px; }
    .danger-text  { font-size:12px; color:var(--slate); margin-bottom:12px; line-height:1.5; }

    /* Form card */
    .form-card { background:var(--white); border:1.5px solid var(--border); border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:16px; }
    .form-card-header { padding:15px 22px; border-bottom:1px solid var(--border); background:var(--sky); display:flex; align-items:center; gap:10px; }
    .card-icon { width:32px; height:32px; background:var(--blue); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
    .form-card-header h3 { font-size:14px; font-weight:800; color:var(--ink); margin:0; }
    .form-card-header p  { font-size:12px; color:var(--slate); margin:0; }
    .form-card-body { padding:22px; }

    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

    .form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
    .form-group:last-child { margin-bottom:0; }
    .form-group label { font-size:12.5px; font-weight:700; color:var(--ink); }

    .form-control { width:100%; padding:10px 13px; border:1.5px solid var(--border); border-radius:8px; font-size:13.5px; font-family:'Plus Jakarta Sans',sans-serif; color:var(--ink); background:var(--white); outline:none; transition:border-color 0.18s, box-shadow 0.18s; }
    .form-control:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    .form-control:disabled { background:var(--bg); color:var(--slate); cursor:not-allowed; }

    /* Submit bar */
    .submit-bar { display:flex; align-items:center; justify-content:flex-end; gap:12px; padding-top:4px; }
    .btn-submit { background:var(--blue); color:#fff; padding:11px 28px; border-radius:9px; border:none; font-size:13.5px; font-weight:800; cursor:pointer; transition:all 0.18s; font-family:'Plus Jakarta Sans',sans-serif; display:inline-flex; align-items:center; gap:8px; }
    .btn-submit:hover { background:var(--blue2); transform:translateY(-1px); box-shadow:0 6px 18px rgba(37,99,235,0.25); }

    /* Delete button */
    .btn-delete { width:100%; background:rgba(239,68,68,0.06); color:#991b1b; border:1.5px solid rgba(239,68,68,0.2); padding:9px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:all 0.18s; font-family:'Plus Jakarta Sans',sans-serif; }
    .btn-delete:hover { background:#ef4444; color:#fff; border-color:#ef4444; }

    /* Alerts */
    .alert-success { background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.25); color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:18px; font-size:14px; font-weight:500; }
    .alert-error   { background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.25); color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:18px; font-size:13px; }
    .alert-error ul { margin:6px 0 0; padding-left:18px; }
    .alert-error ul li { margin-bottom:3px; }

    @media(max-width:680px) { .profile-layout { grid-template-columns:1fr; } .avatar-card { position:static; } .form-grid-2 { grid-template-columns:1fr; } }
</style>

<div class="page-header">
    <div>
        <h2>⚙️ Edit Profile</h2>
        <p>Update your account information.</p>
    </div>
    <a href="{{ route('customer.dashboard') }}" class="back-btn">← Back to Dashboard</a>
</div>

@if(session('success'))
    <div class="alert-success" style="max-width:820px;">✅ {{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert-error" style="max-width:820px;">
        <strong>Please fix the following:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="profile-layout">

    {{-- Left: Avatar Card --}}
    <div>
        <div class="avatar-card">
            <div class="avatar-circle">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="avatar-name">{{ auth()->user()->name }}</div>
            <div class="avatar-email">{{ auth()->user()->email }}</div>
            <div class="avatar-badge">📦 Customer</div>

            <div class="danger-zone">
                <div class="danger-title">Danger Zone</div>
                <div class="danger-text">Permanently delete your account and all associated data. This action cannot be undone.</div>
                <form action="{{ route('customer.delete') }}" method="POST"
                      onsubmit="return confirm('Are you sure? This will permanently delete your account.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">🗑️ Delete Account</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div>
        <form action="{{ route('customer.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Personal Info --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon">👤</div>
                    <div>
                        <h3>Personal Information</h3>
                        <p>Update your name and contact details</p>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   placeholder="Your full name" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control" value="Customer" disabled>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Info --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="card-icon">📧</div>
                    <div>
                        <h3>Account Details</h3>
                        <p>Update your login email address</p>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label>Email Address <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', auth()->user()->email) }}"
                               placeholder="your@email.com" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Member Since</label>
                        <input type="text" class="form-control"
                               value="{{ auth()->user()->created_at->format('d M Y') }}" disabled>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="submit-bar">
                <button type="submit" class="btn-submit">💾 Save Changes</button>
            </div>

        </form>
    </div>

</div>

@endsection
