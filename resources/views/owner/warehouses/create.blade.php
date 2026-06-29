@extends('layouts.owner')

@section('content')

<style>
    .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .back-btn {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--slate); font-size: 13px; font-weight: 600;
        text-decoration: none; transition: color 0.15s;
    }
    .back-btn:hover { color: var(--blue); }

    /* Form layout */
    .form-wrapper { max-width: 780px; }

    .form-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .form-card-header {
        padding: 16px 22px;
        border-bottom: 1px solid var(--border);
        background: var(--sky);
        display: flex; align-items: center; gap: 10px;
    }
    .form-card-header .card-icon {
        width: 34px; height: 34px; background: var(--blue);
        border-radius: 8px; display: flex; align-items: center;
        justify-content: center; font-size: 16px; flex-shrink: 0;
    }
    .form-card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .form-card-header p  { font-size: 12px; color: var(--slate); margin: 0; }

    .form-card-body { padding: 22px; }

    /* Grid layouts */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

    /* Form fields */
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group label {
        font-size: 12.5px; font-weight: 700; color: var(--ink);
        display: flex; align-items: center; gap: 5px;
    }
    .form-group label .req { color: #ef4444; }

    .form-control {
        width: 100%;
        padding: 10px 13px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 13.5px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--ink);
        background: var(--white);
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .form-control:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .form-control.is-invalid { border-color: #ef4444; }
    .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

    textarea.form-control { resize: vertical; min-height: 90px; }
    select.form-control { cursor: pointer; }

    .invalid-feedback {
        font-size: 12px; color: #ef4444; font-weight: 600;
        display: flex; align-items: center; gap: 4px;
    }
    .field-hint { font-size: 11.5px; color: var(--slate); }

    /* File upload */
    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.18s;
        position: relative;
    }
    .file-upload-area:hover { border-color: var(--blue); background: var(--sky); }
    .file-upload-area input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .file-upload-icon { font-size: 28px; margin-bottom: 6px; }
    .file-upload-text { font-size: 13px; font-weight: 600; color: var(--slate); }
    .file-upload-sub  { font-size: 11.5px; color: #94a3b8; margin-top: 3px; }

    /* Payment toggle */
    .payment-toggle { display: flex; gap: 10px; margin-bottom: 16px; }
    .pay-option {
        flex: 1; padding: 12px; border: 1.5px solid var(--border);
        border-radius: 10px; text-align: center; cursor: pointer;
        transition: all 0.18s; font-size: 13px; font-weight: 700;
        color: var(--slate); background: var(--white);
    }
    .pay-option:hover { border-color: var(--blue); color: var(--blue); background: var(--sky); }
    .pay-option.selected { border-color: var(--blue); background: var(--sky); color: var(--blue); }
    .pay-option .pay-icon { font-size: 22px; display: block; margin-bottom: 4px; }

    /* Submit button */
    .submit-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
    }
    .submit-note { font-size: 12.5px; color: var(--slate); display: flex; align-items: center; gap: 6px; }
    .btn-submit {
        background: var(--blue); color: #fff;
        padding: 12px 28px; border-radius: 9px;
        border: none; font-size: 14px; font-weight: 800;
        cursor: pointer; transition: all 0.18s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: var(--blue2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.25); }

    /* Error list */
    .error-card {
        background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.25);
        border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;
    }
    .error-card p { font-size: 13px; font-weight: 700; color: #991b1b; margin-bottom: 8px; }
    .error-card ul { margin: 0; padding-left: 18px; }
    .error-card ul li { font-size: 13px; color: #991b1b; margin-bottom: 3px; }

    @media(max-width: 640px) {
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
    }
</style>

<div class="form-wrapper">

    <div class="page-header">
        <div>
            <h2>🏢 Add New Warehouse</h2>
            <p>Fill in the details below to submit your warehouse for admin approval.</p>
        </div>
        <a href="{{ route('owner.warehouses.index') }}" class="back-btn">← Back to Warehouses</a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="error-card">
            <p>⚠️ Please fix the following errors:</p>
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.warehouses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Basic Info --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">🏢</div>
                <div>
                    <h3>Basic Information</h3>
                    <p>General details about your warehouse</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="form-grid-2" style="margin-bottom:16px;">
                    <div class="form-group">
                        <label>Warehouse Name <span class="req">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               pattern="^[A-Za-z\s]+$"
                               placeholder="e.g. City Storage Hub">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Country <span class="req">*</span></label>
                        <select id="country" name="location"
                                class="form-control @error('location') is-invalid @enderror">
                            @foreach($countries as $code => $cname)
                                <option value="{{ $cname }}" {{ old('location') == $cname ? 'selected' : '' }}>{{ $cname }}</option>
                            @endforeach
                        </select>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-grid-2" style="margin-bottom:16px;">
                    <div class="form-group">
                        <label>Size (sq ft) <span class="req">*</span></label>
                        <input type="text" id="size" name="size"
                               class="form-control @error('size') is-invalid @enderror"
                               value="{{ old('size') }}"
                               pattern="^[0-9]+(\.[0-9]+)?$"
                               placeholder="e.g. 5000">
                        @error('size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Contact Number <span class="req">*</span></label>
                        <input type="text" id="contact" name="contact"
                               class="form-control @error('contact') is-invalid @enderror"
                               value="{{ old('contact') }}"
                               pattern="^[0-9\s\-\+\(\)]+$"
                               placeholder="e.g. 03001234567">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Description</label>
                    <textarea id="description" name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe your warehouse — features, security, access hours...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Full Address <span class="req">*</span></label>
                    <textarea id="address" name="address"
                              class="form-control @error('address') is-invalid @enderror"
                              placeholder="Street, area, city...">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Capacity & Pricing --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">📦</div>
                <div>
                    <h3>Capacity & Pricing</h3>
                    <p>Set your storage units and monthly rate</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label>Total Units <span class="req">*</span></label>
                        <input type="number" name="total_space"
                               class="form-control @error('total_space') is-invalid @enderror"
                               value="{{ old('total_space') }}" placeholder="e.g. 50" min="1">
                        @error('total_space')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Available Units <span class="req">*</span></label>
                        <input type="number" name="available_space"
                               class="form-control @error('available_space') is-invalid @enderror"
                               value="{{ old('available_space') }}" placeholder="e.g. 30" min="0">
                        @error('available_space')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Price / Month (PKR) <span class="req">*</span></label>
                        <input type="number" step="0.01" name="price_per_month"
                               class="form-control @error('price_per_month') is-invalid @enderror"
                               value="{{ old('price_per_month') }}" placeholder="2500 – 5000" min="2500" max="5000">
                        <span class="field-hint">Min Rs 2,500 — Max Rs 5,000</span>
                        @error('price_per_month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Settings --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">💳</div>
                <div>
                    <h3>Payment Settings</h3>
                    <p>Choose how customers will pay you</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="form-group" style="margin-bottom:16px;">
                    <label>Preferred Payment Method <span class="req">*</span></label>
                    <div class="payment-toggle">
                        <div class="pay-option {{ old('preferred_payment_method','stripe') == 'stripe' ? 'selected' : '' }}"
                             onclick="selectPayment('stripe')">
                            <span class="pay-icon">💳</span> Stripe
                        </div>
                        <div class="pay-option {{ old('preferred_payment_method') == 'jazzcash' ? 'selected' : '' }}"
                             onclick="selectPayment('jazzcash')">
                            <span class="pay-icon">📱</span> JazzCash
                        </div>
                    </div>
                    <select name="preferred_payment_method" id="paymentSelect" class="form-control" style="display:none;">
                        <option value="stripe"   {{ old('preferred_payment_method','stripe') == 'stripe'   ? 'selected' : '' }}>Stripe</option>
                        <option value="jazzcash" {{ old('preferred_payment_method') == 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                    </select>
                </div>

                <div id="stripeField" class="form-group">
                    <label>Stripe Account ID</label>
                    <input type="text" id="stripe" name="stripe_account_id"
                           class="form-control @error('stripe_account_id') is-invalid @enderror"
                           value="{{ old('stripe_account_id') }}"
                           pattern="^[A-Za-z0-9_]+$"
                           placeholder="acct_xxxxxxxxxxxxxxxxx">
                    @error('stripe_account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="jazzcashField" class="form-group" style="display:none;">
                    <label>JazzCash Number</label>
                    <input type="text" id="jazzcash" name="jazzcash_number"
                           class="form-control @error('jazzcash_number') is-invalid @enderror"
                           value="{{ old('jazzcash_number') }}"
                           pattern="^[0-9]+$"
                           placeholder="03XXXXXXXXX">
                    @error('jazzcash_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="card-icon">📁</div>
                <div>
                    <h3>Images & Documents</h3>
                    <p>Upload warehouse photo and ownership proof</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Main Image</label>
                        <div class="file-upload-area">
                            <input type="file" name="image" accept="image/*" onchange="showFileName(this,'img-name')">
                            <div class="file-upload-icon">🖼️</div>
                            <div class="file-upload-text" id="img-name">Click to upload image</div>
                            <div class="file-upload-sub">JPG, PNG — max 5MB</div>
                        </div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Property Document</label>
                        <div class="file-upload-area">
                            <input type="file" name="property_doc" accept=".jpg,.jpeg,.png,.pdf" onchange="showFileName(this,'doc-name')">
                            <div class="file-upload-icon">📄</div>
                            <div class="file-upload-text" id="doc-name">Click to upload document</div>
                            <div class="file-upload-sub">JPG, PNG, PDF — max 8MB</div>
                        </div>
                        @error('property_doc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="submit-bar">
            <p class="submit-note">🛡️ Your warehouse will be reviewed by admin before going live.</p>
            <button type="submit" class="btn-submit">
                🚀 Submit for Approval
            </button>
        </div>

    </form>
</div>

<script>
function selectPayment(method) {
    document.getElementById('paymentSelect').value = method;
    document.querySelectorAll('.pay-option').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('jazzcashField').style.display = method === 'jazzcash' ? 'flex' : 'none';
    document.getElementById('stripeField').style.display   = method === 'stripe'   ? 'flex' : 'none';
}

function showFileName(input, targetId) {
    const el = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        el.textContent = input.files[0].name;
        el.style.color = 'var(--blue)';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Init payment fields
    const method = document.getElementById('paymentSelect').value;
    document.getElementById('jazzcashField').style.display = method === 'jazzcash' ? 'flex' : 'none';
    document.getElementById('stripeField').style.display   = method === 'stripe'   ? 'flex' : 'none';

    function validateField(field, message) {
        const pattern = new RegExp(field.getAttribute('pattern'));
        let errorDiv = field.nextElementSibling;
        if (!pattern.test(field.value) && field.value !== '') {
            field.classList.add('is-invalid');
            if (!errorDiv || !errorDiv.classList.contains('live-error')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback live-error';
                field.after(errorDiv);
            }
            errorDiv.innerText = message;
        } else {
            field.classList.remove('is-invalid');
            if (errorDiv && errorDiv.classList.contains('live-error')) errorDiv.remove();
        }
    }

    document.getElementById('name').addEventListener('input',        function(){ validateField(this, 'Only alphabets allowed'); });
    document.getElementById('size').addEventListener('input',        function(){ validateField(this, 'Only numbers allowed'); });
    document.getElementById('contact').addEventListener('input',     function(){ validateField(this, 'Invalid contact format'); });
    document.getElementById('description').addEventListener('input', function(){ validateField(this, 'Only alphabets allowed'); });
    document.getElementById('address').addEventListener('input',     function(){ validateField(this, 'Invalid address format'); });
    document.getElementById('jazzcash').addEventListener('input',    function(){ validateField(this, 'Only numbers allowed'); });
    document.getElementById('stripe').addEventListener('input',      function(){ validateField(this, 'Only letters & numbers allowed'); });

    document.querySelector('[name="price_per_month"]').addEventListener('input', function () {
        const v = parseFloat(this.value);
        this.classList.toggle('is-invalid', v < 2500 || v > 5000);
    });
});
</script>

@endsection
