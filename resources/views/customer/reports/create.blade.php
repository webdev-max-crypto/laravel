<!-- customer/reports/create.blade.php -->
@extends('layouts.customer')

@section('content')
<h1>Submit Report for {{ $warehouse->name }}</h1>
<form action="{{ route('customer.report.store', $warehouse->id) }}" method="POST">
    @csrf
    <textarea name="message" placeholder="Write your report here..." required></textarea>
    <button type="submit">Submit Report</button>
</form>
@endsection