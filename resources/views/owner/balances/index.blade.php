@extends('layouts.owner')
@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-center">💰 My Financial Dashboard</h2>

    {{-- Total Received Card --}}
    <div class="card text-center mb-4 shadow" style="background:#111; color:white;">
        <div class="card-body">
            <h4>Total Received</h4>
            <h1 style="font-size:48px; color:#28a745;">
                Rs {{ number_format($totalReceived,2) }}
            </h1>
        </div>
    </div>

    {{-- Payments History --}}
    <h3>Payments History</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
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
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->warehouse->name }}</td>
                <td>{{ $payment->customer->name }}</td>
                <td>Rs {{ number_format($payment->owner_amount ?? ($payment->total_price * 0.9),2) }}</td>
                <td>{{ ucfirst($payment->payment_status) }}</td>
                <td>{{ $payment->created_at->format('d M, Y') }}</td>
                

            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection