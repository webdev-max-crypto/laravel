@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">

<h2 class="mb-4">Warehouse Full Details</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow p-4">

    {{-- Warehouse Image --}}
    @if($warehouse->image)
        <a href="{{ asset('storage/'.$warehouse->image) }}" target="_blank">
            <img src="{{ asset('storage/'.$warehouse->image) }}"
                 class="img-fluid rounded mb-4"
                 style="max-height: 300px;">
        </a>
    @endif

    <h4>{{ $warehouse->name }}</h4>

    <p><strong>Status:</strong>
        <span class="badge bg-warning">{{ ucfirst($warehouse->status) }}</span>
    </p>

    <hr>

    <p><strong>Owner:</strong> {{ $warehouse->owner->name }}</p>
    <p><strong>Email:</strong> {{ $warehouse->owner->email }}</p>
    <p><strong>Contact:</strong> {{ $warehouse->contact }}</p>

    <hr>

    <p><strong>Location:</strong> {{ $warehouse->location }}</p>
    <p><strong>Address:</strong> {{ $warehouse->address }}</p>
    <p><strong>Description:</strong> {{ $warehouse->description }}</p>
    <p><strong>Size:</strong> {{ $warehouse->size }}</p>

    <hr>

    <p><strong>Total Space:</strong> {{ $warehouse->total_space }}</p>
    <p><strong>Available Space:</strong> {{ $warehouse->available_space }}</p>
    <p><strong>Price / Month:</strong> {{ $warehouse->price_per_month }}</p>

    <hr>

    {{-- Warehouse Property Document --}}
    @if($warehouse->property_doc)
        <p><strong>Warehouse Property Document:</strong></p>
        <a href="{{ asset('storage/'.$warehouse->property_doc) }}"
           target="_blank"
           class="btn btn-outline-primary btn-sm">
           View Document
        </a>
    @endif

    <hr>

    {{-- Owner CNIC --}}
    @if($warehouse->owner->cnic_front)
        <p><strong>Owner CNIC Front:</strong></p>
        <img src="{{ asset('storage/'.$warehouse->owner->cnic_front) }}"
             width="200" class="rounded mb-3">
    @endif

    @if($warehouse->owner->cnic_back)
        <p><strong>Owner CNIC Back:</strong></p>
        <img src="{{ asset('storage/'.$warehouse->owner->cnic_back) }}"
             width="200" class="rounded mb-3">
    @endif

    <hr>

    {{-- Approve / Reject --}}
    <form action="{{ route('admin.warehouses.approve', $warehouse->id) }}"
          method="POST" class="d-inline">
        @csrf
        <button class="btn btn-success">Approve</button>
    </form>

    <form action="{{ route('admin.warehouses.reject', $warehouse->id) }}"
          method="POST" class="d-inline">
        @csrf
        <button class="btn btn-danger">Reject</button>
    </form>

</div>
</div>

@endsection
