@extends('customer.layouts.app')

@section('content')
<style>
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .agreement-wrapper { max-width: 680px; }

    /* Progress steps */
    .steps-bar {
        display: flex; align-items: center;
        margin-bottom: 28px; gap: 0;
    }
    .step-item {
        display: flex; align-items: center; gap: 8px;
        font-size: 12.5px; font-weight: 700;
    }
    .step-circle {
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800; flex-shrink: 0;
    }
    .step-circle.done   { background: var(--emerald); color: #fff; }
    .step-circle.active { background: var(--blue); color: #fff; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }
    .step-circle.next   { background: #f1f5f9; color: var(--slate); }
    .step-label.done    { color: var(--emerald); }
    .step-label.active  { color: var(--blue); }
    .step-label.next    { color: var(--slate); }
    .step-line { flex: 1; height: 2px; background: var(--border); margin: 0 8px; min-width: 24px; }
    .step-line.done { background: var(--emerald); }

    /* Agreement card */
    .agreement-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        margin-bottom: 16px;
    }
    .agreement-header {
        padding: 18px 24px;
        background: var(--sky);
        border-bottom: 1.5px solid var(--sky2);
        display: flex; align-items: center; gap: 12px;
    }
    .header-icon {
        width: 40px; height: 40px; background: var(--blue);
        border-radius: 10px; display: flex; align-items: center;
        justify-content: center; font-size: 18px; flex-shrink: 0;
    }
    .agreement-header h3 { font-size: 16px; font-weight: 800; color: var(--ink); margin: 0; }
    .agreement-header p  { font-size: 12px; color: var(--slate); margin: 2px 0 0; }

    .agreement-body { padding: 24px; }

    /* Terms list */
    .terms-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
    .term-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 13px 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        transition: border-color 0.15s;
    }
    .term-item:hover { border-color: var(--sky2); background: var(--sky); }
    .term-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; flex-shrink: 0; margin-top: 1px;
    }
    .term-icon.blue   { background: var(--sky); }
    .term-icon.yellow { background: rgba(245,158,11,0.1); }
    .term-icon.red    { background: rgba(239,68,68,0.08); }
    .term-icon.green  { background: rgba(16,185,129,0.1); }
    .term-title { font-size: 13.5px; font-weight: 700; color: var(--ink); margin-bottom: 2px; }
    .term-desc  { font-size: 12px; color: var(--slate); line-height: 1.5; }

    /* Divider */
    .divider { height: 1px; background: var(--border); margin: 20px 0; }

    /* Checkbox */
    .agree-checkbox {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 16px;
        background: var(--sky);
        border: 1.5px solid var(--sky2);
        border-radius: 10px;
        margin-bottom: 20px;
        cursor: pointer;
    }
    .agree-checkbox input[type="checkbox"] {
        width: 18px; height: 18px; margin-top: 1px;
        accent-color: var(--blue); cursor: pointer; flex-shrink: 0;
    }
    .agree-text { font-size: 13.5px; font-weight: 600; color: var(--ink); line-height: 1.5; }
    .agree-text span { color: var(--blue); }

    /* Submit button */
    .btn-confirm {
        width: 100%; background: var(--blue); color: #fff;
        padding: 14px; border-radius: 10px; border: none;
        font-size: 15px; font-weight: 800; cursor: pointer;
        transition: all 0.18s; font-family: 'Plus Jakarta Sans', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-confirm:hover { background: var(--blue2); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(37,99,235,0.3); }
    .btn-confirm:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

    /* Security note */
    .security-note {
        display: flex; align-items: center; gap: 10px;
        background: rgba(16,185,129,0.06);
        border: 1px solid rgba(16,185,129,0.2);
        border-radius: 10px; padding: 12px 16px;
        font-size: 12.5px; color: #065f46; font-weight: 500;
        margin-top: 14px;
    }
</style>

<div class="agreement-wrapper">

    <div class="page-header">
        <h2>📜 Booking Agreement</h2>
        <p>Please read and accept the terms before confirming your booking.</p>
    </div>

    {{-- Progress Steps --}}
    <div class="steps-bar">
        <div class="step-item">
            <div class="step-circle done">✓</div>
            <span class="step-label done">Details</span>
        </div>
        <div class="step-line done"></div>
        <div class="step-item">
            <div class="step-circle done">✓</div>
            <span class="step-label done">Summary</span>
        </div>
        <div class="step-line done"></div>
        <div class="step-item">
            <div class="step-circle active">3</div>
            <span class="step-label active">Agreement</span>
        </div>
        <div class="step-line"></div>
        <div class="step-item">
            <div class="step-circle next">4</div>
            <span class="step-label next">Payment</span>
        </div>
    </div>

    {{-- Agreement Card --}}
    <div class="agreement-card">
        <div class="agreement-header">
            <div class="header-icon">📜</div>
            <div>
                <h3>Warehouse Storage Agreement</h3>
                <p>Read all terms carefully before proceeding</p>
            </div>
        </div>
        <div class="agreement-body">

            <div class="terms-list">
                <div class="term-item">
                    <div class="term-icon blue">📦</div>
                    <div>
                        <div class="term-title">Customer Responsibility</div>
                        <div class="term-desc">All stored items are the sole responsibility of the customer. The warehouse owner is not liable for any damage, loss, or theft of stored goods.</div>
                    </div>
                </div>
                <div class="term-item">
                    <div class="term-icon red">🚫</div>
                    <div>
                        <div class="term-title">Prohibited Items</div>
                        <div class="term-desc">No illegal, hazardous, flammable, or perishable items are allowed. Violation may result in immediate termination of the booking without refund.</div>
                    </div>
                </div>
                <div class="term-item">
                    <div class="term-icon yellow">💳</div>
                    <div>
                        <div class="term-title">Payment Policy</div>
                        <div class="term-desc">Payment is held in escrow and released to the owner after you confirm goods arrival. Refunds are only issued in case of a verified dispute.</div>
                    </div>
                </div>
                <div class="term-item">
                    <div class="term-icon green">🛡️</div>
                    <div>
                        <div class="term-title">Force Majeure</div>
                        <div class="term-desc">The warehouse owner is not liable for events beyond reasonable control including natural disasters, floods, fires, or government actions.</div>
                    </div>
                </div>
                <div class="term-item">
                    <div class="term-icon blue">🔒</div>
                    <div>
                        <div class="term-title">Access & Security</div>
                        <div class="term-desc">Access to the warehouse is only permitted during agreed hours. The customer must follow all security protocols set by the warehouse owner.</div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <form action="{{ route('customer.warehouses.finalConfirm', $warehouse->id) }}" method="POST">
                @csrf

                @foreach($data as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <label class="agree-checkbox">
                    <input type="checkbox" id="agreeCheck" required onchange="toggleBtn()">
                    <span class="agree-text">
                        I have read and agree to all the <span>Terms & Conditions</span> of this Warehouse Storage Agreement. I understand my responsibilities as a customer.
                    </span>
                </label>

                <button type="submit" class="btn-confirm" id="confirmBtn" disabled>
                    ✅ Confirm Booking & Proceed to Payment
                </button>
            </form>

            <div class="security-note">
                🔒 Your payment is protected by escrow — funds are only released after you confirm safe goods arrival.
            </div>

        </div>
    </div>

</div>

<script>
    function toggleBtn() {
        const checked = document.getElementById('agreeCheck').checked;
        document.getElementById('confirmBtn').disabled = !checked;
    }
</script>

@endsection
