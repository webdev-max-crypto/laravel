@extends('layouts.customer')

@section('content')
<h1>Submit Report for {{ $warehouse->name }}</h1>
<form method="POST" action="{{ route('report.store', $warehouse->id) }}">
    @csrf
    <label>Title:</label>
    <input type="text" name="title" required>
    <label>Description:</label>
    <textarea name="description" required></textarea>
    <button type="submit">Submit Report</button>
</form>
