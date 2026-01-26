@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Warehouse Details</h2>

    <div class="card p-4 shadow">

        {{-- Warehouse Image --}}
        @if($warehouse->image)
            <img src="{{ asset($warehouse->image) }}" class="img-fluid mb-4 rounded" style="max-height: 250px;">
        @endif

        <h4>{{ $warehouse->name }}</h4>

        <p>
            <strong>Owner:</strong>
            {{ $warehouse->owner->name }}  
            ({{ $warehouse->owner->email }})
        </p>

        <p><strong>Location:</strong> {{ $warehouse->location }}</p>
        <p><strong>Description:</strong> {{ $warehouse->description }}</p>
        <p><strong>Contact:</strong> {{ $warehouse->contact }}</p>

        <hr>

        {{-- CNIC FRONT --}}
        @if($warehouse->owner->cnic_front)
            <p><strong>CNIC Front:</strong></p>
            <img src="{{ asset('storage/' . $warehouse->owner->cnic_front) }}"
                 width="200" class="rounded mb-3">
        @endif

        {{-- CNIC BACK --}}
        @if($warehouse->owner->cnic_back)
            <p><strong>CNIC Back:</strong></p>
            <img src="{{ asset('storage/' . $warehouse->owner->cnic_back) }}"
                 width="200" class="rounded mb-3">
        @endif

        {{-- PROPERTY DOC --}}
        @if($warehouse->owner->property_document)
            <p><strong>Property Document:</strong></p>
            <a href="{{ asset('storage/' . $warehouse->owner->property_document) }}"
               target="_blank"
               class="btn btn-outline-primary btn-sm mb-3">View Document</a>
        @endif

        <hr>

        {{-- Approve / Reject --}}
        <div class="mt-3">

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

</div>

@endsection
