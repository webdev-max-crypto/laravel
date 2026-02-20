@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Bookings / Orders Management</h1>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $statusClass = [
            'pending'   => 'bg-warning text-dark',
            'approved'  => 'bg-success',
            'cancelled' => 'bg-danger',
            'active'    => 'bg-primary text-white',
            'expired'   => 'bg-secondary text-white'
        ];
    @endphp

    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Owner</th>
                        <th>Total Price</th>
                        <th>Owner Amount</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th width="200">Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>

                        {{-- Customer --}}
                        <td>
                            {{ $booking->customer->name ?? 'N/A' }}
                        </td>

                        {{-- Owner --}}
                        <td>
                            {{ $booking->warehouse->owner->name ?? 'N/A' }}
                        </td>

                        {{-- Total --}}
                        <td>
                            Rs. {{ number_format($booking->total_price ?? 0, 2) }}
                        </td>

                        {{-- Owner Amount --}}
                        <td>
                            Rs. {{ number_format($booking->owner_amount ?? $booking->total_price ?? 0, 2) }}
                        </td>

                        {{-- Booking Status --}}
                        <td>
                            <span class="badge {{ $statusClass[$booking->status] ?? 'bg-secondary' }}">
                                {{ ucfirst($booking->status ?? 'N/A') }}
                            </span>
                        </td>

                        {{-- Payment Status --}}
                        <td>
                            @switch($booking->payment_status)
                                @case('unpaid')
                                    <span class="badge bg-danger">Unpaid</span>
                                    @break

                                @case('paid')
                                    <span class="badge bg-warning text-dark">
                                        Paid (Pending Release)
                                    </span>
                                    @break

                                @case('released')
                                    <span class="badge bg-success">
                                        Released
                                    </span>
                                    @break

                                @case('cash')
                                    <span class="badge bg-info">
                                        Cash
                                    </span>
                                    @break

                                @case('escrow')
                                    <span class="badge bg-primary">
                                        Escrow
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                    </span>
                            @endswitch
                        </td>

                        {{-- ACTIONS --}}
                        <td>

                            {{-- Approve / Cancel --}}
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.approved', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm mb-1">
                                        Approve
                                    </button>
                                </form>

                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm mb-1">
                                        Cancel
                                    </button>
                                </form>
                            @endif

                            {{-- Release Payment --}}
                            @if($booking->payment_status === 'paid')
                                <form action="{{ route('admin.bookings.release', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm mb-1">
                                        Release to Owner
                                    </button>
                                </form>
                            @elseif($booking->payment_status === 'released')
                                <span class="text-success fw-bold">
                                    ✔ Released
                                </span>
                            @endif

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No bookings found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>
@endsection
