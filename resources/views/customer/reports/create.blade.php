<!-- customer/reports/create.blade.php -->
@extends('layouts.customer')

<form method="POST" action="{{ route('report.store', $warehouseId) }}">
    @csrf

    <textarea name="message" placeholder="Write your report..." required></textarea>

    <button type="submit">Submit Report</button>
</form>