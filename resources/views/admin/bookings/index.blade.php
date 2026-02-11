@extends('admin.layouts.app')

@section('content')
<h1>Bookings Management</h1>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3">
    @php
        $tabs = ['all'=>'All','pending'=>'Pending','active'=>'Active','expired'=>'Expired'];
    @endphp
    @foreach($tabs as $key=>$label)
    <li class="nav-item">
        <a class="nav-link {{ $status==$key ? 'active' : '' }}" href="{{ route('admin.bookings.index',['status'=>$key]) }}">
            {{ $label }}
        </a>
    </li>
    @endforeach
</ul>

@if($bookings->count())
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Warehouse</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Total Price</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
    @foreach($bookings as $b)
        <tr>
            <td>{{ $b->id }}</td>
            <td>{{ $b->warehouse->name ?? '-' }}</td>
            <td>{{ $b->customer->name ?? '-' }}</td>
            <td>{{ ucfirst($b->status) }}</td>
            <td>Rs. {{ number_format($b->total_price,2) }}</td>
            <td>{{ ucfirst($b->payment_method ?? 'N/A') }}</td>
            <td>{{ $b->payment_status==='paid' ? 'Paid' : 'Unpaid' }}</td>
            <td>{{ $b->created_at->format('d-M-Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $bookings->links() }}
@else
<p class="text-muted">No bookings found for this tab.</p>
@endif
@endsection
