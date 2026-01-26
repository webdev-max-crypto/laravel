@extends('admin.layouts.app')

@section('content')
<h2>Pending Warehouses</h2>

<table class="table">
<thead><tr><th>Owner</th><th>Name</th><th>Location</th><th>Image</th><th>Actions</th></tr></thead>
<tbody>
@foreach($warehouses as $w)
<tr>
    <td>{{ $w->owner->name }} ({{ $w->owner->email }})</td>
    <td>{{ $w->name }}</td>
    <td>{{ Str::limit($w->location,50) }}</td>
    <td>@if($w->image)<img src="{{ asset($w->image) }}" width="80">@endif</td>
    <td>
        <a href="{{ route('admin.warehouses.show',$w->id) }}" class="btn btn-sm btn-info">View</a>

        <form action="{{ route('admin.warehouses.approve',$w->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-success">Approve</button>
        </form>

        <form action="{{ route('admin.warehouses.reject',$w->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-danger">Reject</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
@endsection
