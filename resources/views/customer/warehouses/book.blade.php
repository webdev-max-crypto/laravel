@extends('layouts.customer')

@section('content')
<div class="content" style="max-width:760px;margin:auto;padding:25px;">

    <!-- Back Button -->
    <a href="{{ route('customer.dashboard') }}"
       style="display:inline-block;margin-bottom:20px;padding:9px 14px;
       background:#ef4444;color:white;border-radius:6px;text-decoration:none;
       font-weight:500;">
        ← Back to Dashboard
    </a>

    <!-- Warehouse Card -->
    <div style="background:white;padding:30px;border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,0.12);">

        <h2 style="margin-top:0;color:#1f2937;font-size:26px;">
            {{ $warehouse->name }}
        </h2>

        <p style="color:#374151;"><strong>Location:</strong> {{ $warehouse->location }}</p>
        <p style="color:#374151;"><strong>Size:</strong> {{ $warehouse->size }} sq ft</p>
        <p style="color:#374151;"><strong>Contact:</strong> {{ $warehouse->contact }}</p>
        <p style="color:#374151;"><strong>Description:</strong> {{ $warehouse->description }}</p>

        <p style="margin-top:10px;">
            <strong>Price per Month:</strong>
            <span style="color:#16a34a;font-weight:700;font-size:18px;">
                Rs {{ number_format($warehouse->price_per_month) }}
            </span>
        </p>

        <!-- IMAGE -->
        @if($warehouse->image)

            <div style="text-align:center;margin:10px 0;">
                <img src="{{ asset('storage/'.$warehouse->image) }}"
                     alt="{{ $warehouse->name }}"
                     style="max-width:100%;max-height:300px;
                     border-radius:10px;
                     box-shadow:0 3px 10px rgba(0,0,0,0.15)">
            </div>
        @endif

        <!-- PROPERTY DOCUMENT -->
        @if($warehouse->property_doc)
            <p style="margin-bottom:20px;">
                <strong>Property Document:</strong>
                <a href="{{ asset('storage/'.$warehouse->property_doc) }}"
                   target="_blank"
                   style="color:#2563eb;font-weight:600;text-decoration:none;">
                   View Document
                </a>
            </p>
        @endif

        <hr style="margin:25px 0;">

        <!-- BOOKING FORM -->
        <form action="{{ route('customer.warehouses.calculate',$warehouse->id) }}"
              method="POST">
            @csrf

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Area Required (sq units)</label>
                <input type="number" name="area" required
                       style="width:100%;padding:10px;
                       border-radius:6px;border:1px solid #ccc;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">Number of Items</label>
                <input type="number" name="items" required
                       style="width:100%;padding:10px;
                       border-radius:6px;border:1px solid #ccc;">
            </div>

            <!-- NEW FIELD -->
            <div style="margin-bottom:15px;">
                <label style="font-weight:600;">
                    Items / Boxes Details
                </label>
                <textarea name="items_detail" rows="4"
                          placeholder="Example: 10 cartons, fragile items, electronics, pallets etc."
                          style="width:100%;padding:10px;
                          border-radius:6px;border:1px solid #ccc;"></textarea>
            </div>

            <div style="margin-bottom:20px;">
                <label style="font-weight:600;">Storage Duration (Months)</label>
                <input type="number" name="months" required
                       style="width:100%;padding:10px;
                       border-radius:6px;border:1px solid #ccc;">
            </div>

            <button type="submit"
                    style="width:100%;padding:13px;
                    background:#16a34a;color:white;
                    border:none;border-radius:8px;
                    font-size:16px;font-weight:600;
                    cursor:pointer;">
                Calculate Total
            </button>

        </form>

    </div>
</div>
@endsection
