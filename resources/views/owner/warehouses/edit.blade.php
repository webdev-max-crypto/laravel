@extends('layouts.owner')

@section('content')
<h2>Edit Warehouse: {{ $warehouse->name }}</h2>

<form action="{{ route('owner.warehouses.update', $warehouse->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $warehouse->name) }}">
    </div>

    <div class="mb-3">
        <label>Location</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $warehouse->location) }}">
    </div>

    <div class="mb-3">
        <label>Size</label>
        <input type="text" name="size" class="form-control" value="{{ old('size', $warehouse->size) }}">
    </div>

    <div class="mb-3">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control" value="{{ old('contact', $warehouse->contact) }}">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ old('description', $warehouse->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Total Space</label>
        <input type="number" name="total_space" class="form-control" value="{{ old('total_space', $warehouse->total_space) }}">
    </div>

    <div class="mb-3">
        <label>Available Space</label>
        <input type="number" name="available_space" class="form-control" value="{{ old('available_space', $warehouse->available_space) }}">
    </div>

    <div class="mb-3">
        <label>Price per Month</label>
        <input type="number" step="0.01" name="price_per_month" class="form-control" value="{{ old('price_per_month', $warehouse->price_per_month) }}">
    </div>

    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
        @if($warehouse->image)
            <img src="{{ asset('storage/'.$warehouse->image) }}" width="200" class="mt-2">
        @endif
    </div>

    <div class="mb-3">
        <label>Property Document</label>
        <input type="file" name="property_doc" class="form-control">
        @if($warehouse->property_doc)
            <a href="{{ asset('storage/'.$warehouse->property_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">View Existing Document</a>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Update Warehouse</button>
    <a href="{{ route('owner.warehouses.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
