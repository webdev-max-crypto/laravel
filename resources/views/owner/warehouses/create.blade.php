@extends('layouts.owner')

@section('content')
<div class="container">
    <h2 class="mb-4">Add New Warehouse</h2>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('owner.warehouses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <textarea name="location" class="form-control" required>{{ old('location') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Size</label>
            <input type="text" name="size" class="form-control" value="{{ old('size') }}">
        </div>

        <div class="mb-3">
            <label>Contact</label>
            <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Total Units</label>
            <input type="number" name="total_space" class="form-control" value="{{ old('total_space') }}" required>
        </div>

        <div class="mb-3">
            <label>Available Units</label>
            <input type="number" name="available_space" class="form-control" value="{{ old('available_space') }}" required>
        </div>

        <div class="mb-3">
            <label>Price per Month</label>
            <input type="number" step="0.01" name="price_per_month" class="form-control" value="{{ old('price_per_month') }}" required>
        </div>

        <hr>
        <h5>Your Registered Documents</h5>
        <div class="mb-3">
            <label>CNIC Front</label><br>
            @if($user->cnic_front)
                <img src="{{ asset('uploads/cnic/'.$user->cnic_front) }}" width="150" class="mb-2">
            @else
                <p class="text-muted">Not uploaded</p>
            @endif
        </div>

        <div class="mb-3">
            <label>CNIC Back</label><br>
            @if($user->cnic_back)
                <img src="{{ asset('uploads/cnic/'.$user->cnic_back) }}" width="150" class="mb-2">
            @else
                <p class="text-muted">Not uploaded</p>
            @endif
        </div>

        <hr>
        <div class="mb-3">
            <label>Warehouse Main Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Property Document (PDF/Image)</label>
            <input type="file" name="property_doc" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Submit for Approval</button>
    </form>
</div>
@endsection
