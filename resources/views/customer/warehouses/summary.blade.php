@extends('layouts.customer')

@section('content')
<div class="content" style="max-width:760px;margin:auto;padding:25px;">

    <!-- Back Button -->
    <a href="{{ url()->previous() }}"
       style="display:inline-block;margin-bottom:20px;padding:9px 14px;
       background:#6b7280;color:white;border-radius:6px;
       text-decoration:none;font-weight:500;">
        ← Back
    </a>

    <!-- Summary Card -->
    <div style="background:white;padding:30px;border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,0.12);">

        <h2 style="margin-top:0;color:#1f2937;font-size:26px;">
            Booking Summary
        </h2>

        <p style="color:#6b7280;margin-bottom:20px;">
            Please review your booking details before confirming.
        </p>

        <hr style="margin:20px 0;">

        <p><strong>Warehouse:</strong> {{ $warehouse->name }}</p>
        <p><strong>Location:</strong> {{ $warehouse->location }}</p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:15px;">
            <p><strong>Area Required:</strong> {{ $data['area'] }} sq units</p>
            <p><strong>Items Count:</strong> {{ $data['items'] }}</p>
            <p><strong>Storage Duration:</strong> {{ $data['months'] }} months</p>
            <p>
                <strong>Price / Month:</strong>
                <span style="color:#16a34a;font-weight:600;">
                    Rs {{ number_format($warehouse->price_per_month) }}
                </span>
            </p>
        </div>

        <!-- ITEMS DETAIL -->
        @if(!empty($data['items_detail']))
            <div style="margin-top:20px;">
                <strong>Items / Boxes Details:</strong>
                <div style="margin-top:8px;padding:12px;
                background:#f9fafb;border-radius:8px;
                border:1px solid #e5e7eb;">
                    {{ $data['items_detail'] }}
                </div>
            </div>
        @endif

        <hr style="margin:25px 0;">

        <!-- TOTAL -->
        <div style="display:flex;justify-content:space-between;
        align-items:center;margin-bottom:20px;">
            <h3 style="margin:0;color:#1f2937;">Total Price</h3>
            <h3 style="margin:0;color:#16a34a;">
                Rs {{ number_format($total) }}
            </h3>
        </div>

        <!-- CONFIRM FORM -->
        <form action="{{ route('customer.warehouses.confirm', $warehouse->id) }}" method="POST">
            @csrf

            <input type="hidden" name="area" value="{{ $data['area'] }}">
            <input type="hidden" name="items" value="{{ $data['items'] }}">
            <input type="hidden" name="months" value="{{ $data['months'] }}">
            <input type="hidden" name="items_detail" value="{{ $data['items_detail'] ?? '' }}">
            <input type="hidden" name="total_price" value="{{ $total }}">

            <button type="submit"
                    style="width:100%;padding:14px;
                    background:#16a34a;color:white;
                    border:none;border-radius:10px;
                    font-size:17px;font-weight:600;
                    cursor:pointer;">
                Confirm Booking
            </button>
        </form>

    </div>
</div>
@endsection
