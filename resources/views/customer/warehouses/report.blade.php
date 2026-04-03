@extends('layouts.customer')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Submit Report</h2>

    @if(session('success'))
        <div class="bg-green-100 p-2 mb-4 text-green-700">{{ session('success') }}</div>
    @endif

    <form action="{{ route('customer.warehouse.report.store', $warehouseId) }}" method="POST">
        @csrf
        <textarea name="message" class="w-full border p-2 mb-2" placeholder="Enter your report here" required></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Report</button>
    </form>
</div>
@endsection