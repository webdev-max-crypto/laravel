@extends('layouts.owner')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ $warehouse->name }}</h2>
        <a href="{{ route('owner.warehouses.index') }}" class="btn btn-secondary">Back</a>
    </div>

    {{-- Preferred Payment Info --}}
    <div class="mb-3">
        <h5>Preferred Payment: <strong>{{ ucfirst($warehouse->preferred_payment_method) }}</strong></h5>
        @if($warehouse->preferred_payment_method == 'jazzcash' && $warehouse->jazzcash_number)
            <p><strong>JazzCash Number:</strong> {{ $warehouse->jazzcash_number }}</p>
        @endif
        @if($warehouse->preferred_payment_method == 'stripe' && $warehouse->stripe_account_id)
            <p><strong>Stripe Account:</strong> {{ $warehouse->stripe_account_id }}</p>
        @endif
    </div>

    {{-- Warehouse Image --}}
    @if($warehouse->image)
        <div class="mb-3">
            <img src="{{ asset('storage/'.$warehouse->image) }}" class="img-thumbnail" style="max-width:400px;">
        </div>
    @endif

    {{-- Warehouse Info --}}
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Location:</strong> {{ $warehouse->location }}</p>
            <p><strong>Size:</strong> {{ $warehouse->size ?? 'N/A' }}</p>
            <p><strong>Contact:</strong> {{ $warehouse->contact }}</p>
            <p><strong>Description:</strong> {!! nl2br(e($warehouse->description)) !!}</p>
            <p><strong>Total Units:</strong> {{ $warehouse->total_space }}</p>
            <p><strong>Available Units:</strong> {{ $warehouse->available_space }}</p>
            <p><strong>Price per Month (PKR):</strong> {{ number_format($warehouse->price_per_month, 2) }}</p>
            <p><strong>Status:</strong>
                @if($warehouse->status == 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif($warehouse->status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($warehouse->status == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($warehouse->status) }}</span>
                @endif
            </p>

            {{-- Property Document --}}
            @if($warehouse->property_doc)
                <p>
                    <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        View Property Document
                    </a>
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
