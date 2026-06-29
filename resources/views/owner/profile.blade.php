@extends('layouts.owner')

@section('content')

<style>
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }
    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--slate); font-size: 13px; font-weight: 600;
        text-decoration: none; transition: color 0.15s;
    }
    .back-btn:hover { color: var(--blue); }

    .profile-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* Left — Avatar card */
    .avatar-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 28px 20px;
        text-align: center;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        position: sticky;
        top: 80px;
    }
    .avatar-wrap {
        position: relative;
        width: 100px; height: 100px;
        margin: 0 auto 14px;
    }
    .avatar-img {
        width: 100px; height: 100px;
        border-radius: 50%; object-fit: cover;
        border: 3px solid var(--sky2);
    }
    .avatar-placeholder {
        width: 100px; height: 100px; border-radius: 50%;
        background: linear-gradient(135deg, #1e40af, #2563eb);
        display: flex; align-items: center; justify-content: center;
        font-size: 36px; font-weight: 800; color: #fff;
        border: 3px solid var(--sky2);
    }
    .avatar-edit-btn {
        position: absolute; bottom: 2px; right: 2px;
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--blue); color: #fff;
        border: 2px solid #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; cursor: pointer;
        transition: background 0.15s;
    }
    .avatar-edit-btn:hover { background: var(--blue2); }
    .avatar-name { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 3px; }
    .avatar-role {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--sky); color: var(--blue);
        border: 1px solid var(--sky2);
        border-radius: 20px; padding: 3px 12px;
        font-size: 12px; font-weight: 700; margin-bottom: 16px;
    }
    .avatar-info { font-size: 12.5px; color: var(--slate); line-height: 1.8; }
    .avatar-info span { display: block; }

    .danger-zone {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }
    .btn-danger-outline {
        display: block; width: 100%;
        background: rgba(239,68,68,0.06);
        color: #991b1b; border: 1.5px solid rgba(239,68,68,0.2);
        padding: 9px; border-radius: 8px;
        font-size: 13px; font-weight: 700;
        text-decoration: none; text-align: center;
        transition: all 0.18s;
    }
    .btn-danger-outline:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    /* Right — Form cards */
    .form-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }
    .form-card-header {
        padding: 15px 22px;
        border-bottom: 1px solid var(--border);
        background: var(--sky);
        display: flex; align-items: center; gap: 10px;
    }
    .card-icon {
        width: 32px; height: 32px; background: var(--blue);
        border-radius: 8px; display: flex; align-items: center;
        justify-content: center; font-size: 15px; flex-shrink: 0;
    }
    .form-card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .form-card-header p  { font-size: 12px; color: var(--slate); margin: 0; }
    .form-card-body { padding: 20px 22px; }

    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
    .form-group:last-child { margin-bottom: 0; }
    .form-group label { font-size: 12.5px; font-weight: 700; color: var(--ink); }

    .form-control {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13.5px; font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--ink); background: var(--white); outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .form-control:disabled { background: var(--bg); color: var(--slate); cursor: not-allowed; }

    /* Doc upload */
    .doc-upload {
        border: 2px dashed var(--border);
        border-radius: 10px; padding: 16px;
        text-align: center; cursor: pointer;
        transition: all 0.18s; position: relative;
    }
    .doc-upload:hover { border-color: var(--blue); background: var(--sky); }
    .doc-upload input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .doc-upload-icon { font-size: 24px; margin-bottom: 5px; }
    .doc-upload-text { font-size: 12.5px; font-weight: 600; color: var(--slate); }
    .doc-upload-sub  { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* Doc preview */
    .doc-preview {
        display: flex; align-items: center; gap: 10px;
        background: var(--sky); border: 1px solid var(--sky2);
        border-radius: 8px; padding: 10px 14px; margin-bottom: 10px;
    }
    .doc-preview img { width: 56px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border); }
    .doc-preview-info { flex: 1; }
    .doc-preview-label { font-size: 12px; font-weight: 700; color: var(--ink); }
    .doc-preview-link  { font-size: 11.5px; color: var(--blue); text-decoration: none; font-weight: 600; }
    .doc-preview-link:hover { color: var(--blue2); }

    /* Submit bar */
    .submit-bar {
        display: flex; align-items: center; justify-content: flex-end;
        gap: 12px; padding-top: 4px;
    }
    .btn-submit {
        background: var(--blue); color: #fff;
        padding: 11px 28px; border-radius: 9px;
        border: none; font-size: 13.5px; font-weight: 800;
        cursor: pointer; transition: all 0.18s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: var(--blue2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.25); }

    @media(max-width: 768px) {
        .profile-layout { grid-template-columns: 1fr; }
        .avatar-card { position: static; }
        .form-grid-2 { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <div>
        <h2>⚙️ Profile Settings</h2>
        <p>Manage your personal information and documents.</p>
    </div>
    <a href="{{ route('owner.dashboard') }}" class="back-btn">← Back to Dashboard</a>
</div>

@if(session('success'))
    <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:14px;font-weight:500;">
        ✅ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.25);color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;">
        <strong>Please fix the following:</strong>
        <ul style="margin:6px 0 0;padding-left:18px;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('owner.profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="profile-layout">

    {{-- LEFT: Avatar Card --}}
    <div>
        <div class="avatar-card">
            <div class="avatar-wrap">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" class="avatar-img" id="avatar-preview" alt="Profile">
                @else
                    <div class="avatar-placeholder" id="avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <label for="profile_photo_input" class="avatar-edit-btn" title="Change photo">✏️</label>
                <input type="file" name="profile_photo" id="profile_photo_input"
                       style="display:none;" accept="image/*"
                       onchange="previewAvatar(this)">
            </div>

            <div class="avatar-name">{{ $user->name }}</div>
            <div class="avatar-role">🏭 Warehouse Owner</div>

            <div class="avatar-info">
                <span>📧 {{ $user->email }}</span>
                @if($user->phone)
                    <span>📞 {{ $user->phone }}</span>
                @endif
                @if($user->is_verified)
                    <span style="color:#065f46;font-weight:700;margin-top:6px;">✅ Verified Owner</span>
                @else
                    <span style="color:#92400e;font-weight:700;margin-top:6px;">⏳ Pending Verification</span>
                @endif
            </div>

            <div class="danger-zone">
                <a href="{{ route('owner.delete.confirm') }}" class="btn-danger-outline">🗑️ Delete Account</a>
            </div>
        </div>
    </div>

    {{-- RIGHT: Form --}}
    <div>

        {{-- Personal Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">👤</div>
                <div>
                    <h3>Personal Information</h3>
                    <p>Update your basic account details</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Full Name <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="03XXXXXXXXX">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" class="form-control" value="Warehouse Owner" disabled>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">📁</div>
                <div>
                    <h3>Identity Documents</h3>
                    <p>CNIC and property ownership documents</p>
                </div>
            </div>
            <div class="form-card-body">

                {{-- CNIC Front --}}
                <div class="form-group">
                    <label>CNIC Front</label>
                    @if($user->cnic_front)
                        <div class="doc-preview">
                            <img src="{{ asset('storage/'.$user->cnic_front) }}" alt="CNIC Front">
                            <div class="doc-preview-info">
                                <div class="doc-preview-label">Current CNIC Front</div>
                                <a href="{{ asset('storage/'.$user->cnic_front) }}" target="_blank" class="doc-preview-link">View full image →</a>
                            </div>
                        </div>
                    @endif
                    <div class="doc-upload">
                        <input type="file" name="cnic_front" accept="image/*" onchange="showFileName(this,'cnic-front-name')">
                        <div class="doc-upload-icon">🪪</div>
                        <div class="doc-upload-text" id="cnic-front-name">{{ $user->cnic_front ? 'Click to replace' : 'Upload CNIC Front' }}</div>
                        <div class="doc-upload-sub">JPG, PNG — max 5MB</div>
                    </div>
                </div>

                {{-- CNIC Back --}}
                <div class="form-group">
                    <label>CNIC Back</label>
                    @if($user->cnic_back)
                        <div class="doc-preview">
                            <img src="{{ asset('storage/'.$user->cnic_back) }}" alt="CNIC Back">
                            <div class="doc-preview-info">
                                <div class="doc-preview-label">Current CNIC Back</div>
                                <a href="{{ asset('storage/'.$user->cnic_back) }}" target="_blank" class="doc-preview-link">View full image →</a>
                            </div>
                        </div>
                    @endif
                    <div class="doc-upload">
                        <input type="file" name="cnic_back" accept="image/*" onchange="showFileName(this,'cnic-back-name')">
                        <div class="doc-upload-icon">🪪</div>
                        <div class="doc-upload-text" id="cnic-back-name">{{ $user->cnic_back ? 'Click to replace' : 'Upload CNIC Back' }}</div>
                        <div class="doc-upload-sub">JPG, PNG — max 5MB</div>
                    </div>
                </div>

                {{-- Property Document --}}
                <div class="form-group">
                    <label>Property Ownership Document</label>
                    @if($user->property_document)
                        <div class="doc-preview">
                            <div style="width:56px;height:40px;background:var(--sky);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:20px;border:1px solid var(--sky2);">📄</div>
                            <div class="doc-preview-info">
                                <div class="doc-preview-label">Property Document Uploaded</div>
                                <a href="{{ asset('storage/'.$user->property_document) }}" target="_blank" class="doc-preview-link">View document →</a>
                            </div>
                        </div>
                    @endif
                    <div class="doc-upload">
                        <input type="file" name="property_document" accept=".jpg,.jpeg,.png,.pdf" onchange="showFileName(this,'prop-doc-name')">
                        <div class="doc-upload-icon">📄</div>
                        <div class="doc-upload-text" id="prop-doc-name">{{ $user->property_document ? 'Click to replace' : 'Upload Property Document' }}</div>
                        <div class="doc-upload-sub">JPG, PNG, PDF — max 8MB</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Submit --}}
        <div class="submit-bar">
            <button type="submit" class="btn-submit">💾 Save Changes</button>
        </div>

    </div>
</div>

</form>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById('avatar-placeholder');
                let img = document.getElementById('avatar-preview');
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.className = 'avatar-img';
                    img.alt = 'Profile';
                    document.querySelector('.avatar-wrap').prepend(img);
                }
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showFileName(input, targetId) {
        if (input.files && input.files[0]) {
            const el = document.getElementById(targetId);
            el.textContent = input.files[0].name;
            el.style.color = 'var(--blue)';
        }
    }
</script>

@endsection
