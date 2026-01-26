@extends('layouts.owner')

@section('content')
<h3>Payments</h3>

@if($payments->count() > 0)
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Warehouse</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $payment->booking->warehouse->name ?? '-' }}</td>
            <td>{{ $payment->booking->customer->name ?? '-' }}</td>
            <td>${{ $payment->amount }}</td>
            <td>
                @if($payment->status == 'completed')
                    <span class="badge bg-success">Completed</span>
                @elseif($payment->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($payment->status == 'escrow')
                    <span class="badge bg-info">Escrow</span>
                @endif
            </td>
            <td>{{ $payment->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $payments->links() }} <!-- pagination -->

@else
<p>No payments yet.</p>
@endif
@endsection
