@extends('layouts.customer')

@section('content')
<div class="container mt-4">
    <h2>Book Warehouse: {{ $warehouse->name }}</h2>

    <p><strong>Location:</strong> {{ $warehouse->location }}</p>
    <p><strong>Price per Month:</strong> ${{ $warehouse->price_per_month }}</p>

    @if($warehouse->image)
        <p><strong>Image:</strong></p>
        <img src="{{ asset($warehouse->image) }}" width="300" class="rounded mb-3">
    @endif

    @if($warehouse->property_doc)
        <p><strong>Property Document:</strong></p>
        <a href="{{ asset($warehouse->property_doc) }}" target="_blank" class="btn btn-outline-primary mb-3">View Document</a>
    @endif

    <form action="{{ route('customer.warehouses.calculate', $warehouse->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Area you want to book (sq units)</label>
            <input type="number" name="area" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label>Number of items to store</label>
            <input type="number" name="items" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label>Number of months to store</label>
            <input type="number" name="months" class="form-control" min="1" required>
        </div>

        <button class="btn btn-success">Calculate Total</button>
    </form>
</div>
@endsection
