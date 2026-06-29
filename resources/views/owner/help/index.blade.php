@extends('layouts.owner')

@section('content')

<style>
    .page-header { margin-bottom: 24px; }
    .page-header h2 { font-size: 20px; font-weight: 800; color: var(--ink); }
    .page-header p  { font-size: 13px; color: var(--slate); margin-top: 4px; }

    .help-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    /* Contact cards */
    .contact-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 20px 22px;
        display: flex; align-items: center; gap: 16px;
        transition: all 0.18s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        text-decoration: none;
    }
    .contact-card:hover {
        border-color: var(--blue);
        box-shadow: 0 6px 20px rgba(37,99,235,0.1);
        transform: translateY(-2px);
    }
    .contact-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: var(--sky);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .contact-label { font-size: 11.5px; font-weight: 700; color: var(--slate); text-transform: uppercase; letter-spacing: 0.8px; }
    .contact-value { font-size: 14px; font-weight: 700; color: var(--ink); margin-top: 3px; }

    /* FAQ */
    .faq-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .faq-header {
        padding: 16px 22px;
        border-bottom: 1px solid var(--border);
        background: var(--sky);
        display: flex; align-items: center; gap: 10px;
    }
    .faq-header-icon {
        width: 34px; height: 34px; background: var(--blue);
        border-radius: 8px; display: flex; align-items: center;
        justify-content: center; font-size: 16px;
    }
    .faq-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }

    .faq-item {
        border-bottom: 1px solid var(--border);
        overflow: hidden;
    }
    .faq-item:last-child { border-bottom: none; }

    .faq-question {
        padding: 14px 22px;
        font-size: 13.5px; font-weight: 700; color: var(--ink);
        cursor: pointer;
        display: flex; align-items: center; justify-content: space-between;
        transition: background 0.15s;
        user-select: none;
    }
    .faq-question:hover { background: var(--sky); }
    .faq-arrow { font-size: 12px; color: var(--slate); transition: transform 0.2s; }
    .faq-item.open .faq-arrow { transform: rotate(180deg); }

    .faq-answer {
        display: none;
        padding: 0 22px 14px;
        font-size: 13px; color: var(--slate); line-height: 1.7;
    }
    .faq-item.open .faq-answer { display: block; }

    /* Ticket form */
    .form-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .form-card-header {
        padding: 16px 22px;
        border-bottom: 1px solid var(--border);
        background: var(--sky);
        display: flex; align-items: center; gap: 10px;
    }
    .form-card-icon {
        width: 34px; height: 34px; background: var(--blue);
        border-radius: 8px; display: flex; align-items: center;
        justify-content: center; font-size: 16px;
    }
    .form-card-header h3 { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .form-card-header p  { font-size: 12px; color: var(--slate); margin: 0; }

    .form-card-body { padding: 22px; }

    .form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
    .form-group label { font-size: 12.5px; font-weight: 700; color: var(--ink); }

    .form-control {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid var(--border); border-radius: 8px;
        font-size: 13.5px; font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--ink); background: var(--white); outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    textarea.form-control { resize: vertical; min-height: 120px; }
    select.form-control { cursor: pointer; }

    .btn-submit {
        background: var(--blue); color: #fff;
        padding: 11px 26px; border-radius: 9px;
        border: none; font-size: 13.5px; font-weight: 800;
        cursor: pointer; transition: all 0.18s;
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-submit:hover { background: var(--blue2); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.25); }

    @media(max-width:640px) { .help-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <h2>🆘 Help & Support</h2>
    <p>Get help, find answers, or send us a message.</p>
</div>

{{-- Contact Cards --}}
<div class="help-grid">
    <a href="mailto:support@smart-multiwarehouse.pk" class="contact-card">
        <div class="contact-icon">📧</div>
        <div>
            <div class="contact-label">Email Us</div>
            <div class="contact-value">support@smart-multiwarehouse.pk</div>
        </div>
    </a>
    <a href="tel:+923007239005" class="contact-card">
        <div class="contact-icon">📞</div>
        <div>
            <div class="contact-label">Call Us</div>
            <div class="contact-value">+92 300 7239005</div>
        </div>
    </a>
    <div class="contact-card" style="cursor:default;">
        <div class="contact-icon">🕐</div>
        <div>
            <div class="contact-label">Support Hours</div>
            <div class="contact-value">Mon – Sat, 9am – 6pm</div>
        </div>
    </div>
    <a href="{{ route('chat.inbox') }}" class="contact-card">
        <div class="contact-icon">💬</div>
        <div>
            <div class="contact-label">Live Chat</div>
            <div class="contact-value">Message us directly →</div>
        </div>
    </a>
</div>

{{-- FAQ --}}
<div class="faq-card">
    <div class="faq-header">
        <div class="faq-header-icon">❓</div>
        <h3>Frequently Asked Questions</h3>
    </div>

    @php
        $faqs = [
            ['q' => 'How do I add a new warehouse?', 'a' => 'Go to My Warehouses from the sidebar and click "Add New Warehouse". Fill in the details and submit for admin approval. You will be notified once it is approved.'],
            ['q' => 'When will I receive my payment?', 'a' => 'Payments are held in escrow until the customer confirms goods arrival. Once confirmed, the admin releases your payment within 1–2 business days.'],
            ['q' => 'Why is my warehouse still pending?', 'a' => 'Admin reviews all warehouse submissions manually. This usually takes 24–48 hours. Make sure your property document is uploaded correctly.'],
            ['q' => 'How do I update my warehouse details?', 'a' => 'Go to My Warehouses, click "Edit" on the warehouse you want to update, make your changes and save.'],
            ['q' => 'What happens if a customer cancels a booking?', 'a' => 'If a booking is cancelled before goods are confirmed, the payment is refunded to the customer. You will be notified via email and notification.'],
            ['q' => 'How do I connect my Stripe account?', 'a' => 'Go to Profile Settings and click "Connect Stripe". Follow the onboarding steps to link your Stripe Express account for payouts.'],
        ];
    @endphp

    @foreach($faqs as $i => $faq)
        <div class="faq-item" id="faq-{{ $i }}">
            <div class="faq-question" onclick="toggleFaq({{ $i }})">
                {{ $faq['q'] }}
                <span class="faq-arrow">▼</span>
            </div>
            <div class="faq-answer">{{ $faq['a'] }}</div>
        </div>
    @endforeach
</div>

{{-- Support Ticket Form --}}
<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon">🎫</div>
        <div>
            <h3>Submit a Support Ticket</h3>
            <p>We'll get back to you within 24 hours</p>
        </div>
    </div>
    <div class="form-card-body">
        @if(session('success'))
            <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;font-weight:500;">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('owner.help') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control">
                    <option value="payment">💳 Payment Issue</option>
                    <option value="warehouse">🏢 Warehouse Problem</option>
                    <option value="booking">📦 Booking Issue</option>
                    <option value="account">👤 Account Problem</option>
                    <option value="other">❓ Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Subject <span style="color:#ef4444;">*</span></label>
                <input type="text" name="subject" class="form-control"
                       placeholder="Brief description of your issue" required>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
                <label>Message <span style="color:#ef4444;">*</span></label>
                <textarea name="message" class="form-control"
                          placeholder="Describe your issue in detail..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">🚀 Send Ticket</button>
        </form>
    </div>
</div>

<script>
    function toggleFaq(i) {
        const item = document.getElementById('faq-' + i);
        item.classList.toggle('open');
    }
</script>

@endsection
