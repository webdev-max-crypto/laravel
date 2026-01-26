@extends('layouts.owner')

@section('content')
<div class="d-flex justify-content-between">
    <h2>{{ $w->name }}</h2>
    <div>
        <a href="{{ route('owner.warehouses.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

@if($w->image) <img src="{{ asset($w->image) }}" width="300" class="mb-3">@endif

<p><strong>Location:</strong> {!! nl2br(e($w->location)) !!}</p>
<p><strong>Size:</strong> {{ $w->size }}</p>
<p><strong>Contact:</strong> {{ $w->contact }}</p>
<p><strong>Description:</strong> {!! nl2br(e($w->description)) !!}</p>

<p><strong>Status:</strong> {{ $w->status }}</p>

@if($w->property_doc)
    <p><a href="{{ asset($w->property_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Property Document</a></p>
@endif

@endsection
