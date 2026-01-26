@extends('layouts.admin')

@section('content')
<h1>All Payments</h1>


@if($payments->count())
<table class="table table-striped">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>Warehouse</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->booking->id }}</td>
            <td>{{ $payment->booking->warehouse->name }}</td>
            <td>{{ $payment->booking->customer->name }}</td>
            <td>${{ number_format($payment->amount,2) }}</td>
            <td>{{ ucfirst($payment->payment_method) }}</td>
            <td>
                @if($payment->status == 'completed')
                    <span class="badge bg-success">Completed</span>
                @else
                    <span class="badge bg-warning">{{ ucfirst($payment->status) }}</span>
                @endif
            </td>
            <td>{{ $payment->created_at->format('d-m-Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No payments yet.</p>
@endif
@endsection
