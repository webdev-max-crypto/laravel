@extends('layouts.customer')

@section('content')
<div class="container mt-4">
    <h2>Booking Summary: {{ $warehouse->name }}</h2>

    <p><strong>Location:</strong> {{ $warehouse->location }}</p>
    <p><strong>Area:</strong> {{ $data['area'] }} sq units</p>
    <p><strong>Items:</strong> {{ $data['items'] }}</p>
    <p><strong>Months:</strong> {{ $data['months'] }}</p>
    <p><strong>Total Price:</strong> ${{ $total }}</p>

    <form action="{{ route('customer.warehouses.confirm', $warehouse->id) }}" method="POST">
        @csrf
        <input type="hidden" name="area" value="{{ $data['area'] }}">
        <input type="hidden" name="items" value="{{ $data['items'] }}">
        <input type="hidden" name="months" value="{{ $data['months'] }}">
        <input type="hidden" name="total_price" value="{{ $total }}">

        <button class="btn btn-primary">Confirm Booking</button>
    </form>
</div>
@endsection
