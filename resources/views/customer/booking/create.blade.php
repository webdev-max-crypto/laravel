@extends('layouts.customer')

@section('content')
<div class="content" style="max-width:750px;margin:auto;padding:20px;">

    <!-- Back Button -->
    <a href="{{ route('customer.dashboard') }}" 
       style="display:inline-block;margin-bottom:25px;padding:10px 15px;background:#ef4444;color:white;border-radius:6px;text-decoration:none;transition:0.3s;">
        ← Back to Dashboard
    </a>

    <!-- Warehouse Card -->
    <div style="background:white;padding:30px;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.1);">

        <h2 style="margin-top:0;color:#1f2937;">{{ $warehouse->name }}</h2>

        <p><strong>Location:</strong> {{ $warehouse->location }}</p>
        <p><strong>Size:</strong> {{ $warehouse->size }} sq ft</p>
        <p><strong>Contact:</strong> {{ $warehouse->contact }}</p>
        <p><strong>Description:</strong> {{ $warehouse->description }}</p>
        <p><strong>Price per unit:</strong> <span style="color:#16a34a;font-weight:600;">{{ $warehouse->price_per_unit }}</span></p>

        <!-- IMAGE -->
       @if($warehouse->image)
    <img src="{{ asset('storage/' . $warehouse->image) }}" 
         alt="{{ $warehouse->name }}" 
         style="max-width:100%;border-radius:10px;margin-bottom:15px;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
@endif

        <!-- PROPERTY DOCUMENT -->
        @if($warehouse->property_doc)
    <p>
        <strong>Property Document:</strong> 
        <a href="{{ asset('storage/' . $warehouse->property_doc) }}" target="_blank"
           style="color:#3b82f6;text-decoration:underline;font-weight:600;">
           View Document
        </a>
    </p>
@endif

        <!-- BOOKING FORM -->
        <form action="{{ route('customer.booking.store', $warehouse->id) }}" method="POST" style="margin-top:25px;">
            @csrf

            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">Start Date</label>
                <input type="date" name="start_date" required 
                       style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;transition:0.3s;">
            </div>

            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:600;">End Date</label>
                <input type="date" name="end_date" required 
                       style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;transition:0.3s;">
            </div>

            <a href="{{ route('customer.warehouses.book', $warehouse->id) }}" class="btn btn-primary">Book Warehouse</a>
        </form>

    </div>
</div>
@endsection
