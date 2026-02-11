@extends('layouts.owner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>{{ $warehouse->name }}</h2>
    <div>
        <a href="{{ route('owner.warehouses.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

@if($warehouse->image)
    <img src="{{ asset('storage/'.$warehouse->image) }}" width="300" class="mb-3 img-thumbnail">
@endif

<p><strong>Location:</strong> {!! nl2br(e($warehouse->location)) !!}</p>
<p><strong>Size:</strong> {{ $warehouse->size ?? 'N/A' }}</p>
<p><strong>Contact:</strong> {{ $warehouse->contact }}</p>
<p><strong>Description:</strong> {!! nl2br(e($warehouse->description)) !!}</p>
<p><strong>Total Space:</strong> {{ $warehouse->total_space }}</p>
<p><strong>Available Space:</strong> {{ $warehouse->available_space }}</p>
<p><strong>Price per Month:</strong> ${{ number_format($warehouse->price_per_month, 2) }}</p>
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

@if($warehouse->property_doc)
    <p>
        <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
            View Property Document
        </a>
    </p>
@endif
@endsection
