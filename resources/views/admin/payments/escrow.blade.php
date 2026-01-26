@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">

    <h1 class="mb-4">Escrow Payments</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    @if($payments->isEmpty())
        <div class="alert alert-warning">
            No escrow payments found.
        </div>
    @else

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Payment ID</th>
                    <th>Booking ID</th>
                    <th>Warehouse</th>
                    <th>Owner</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Release</th>
                </tr>
            </thead>

            <tbody>
                @foreach($payments as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->booking_id }}</td>

                    <td>{{ $p->booking->warehouse->name ?? 'N/A' }}</td>

                    <td>{{ $p->booking->warehouse->owner->name ?? 'N/A' }}</td>

                    <td>{{ $p->amount }}</td>

                    <td>
                        <span class="badge bg-warning">Escrow</span>
                    </td>

                    <td>
                        <form action="{{ route('admin.payments.release', $p->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm">Release</button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-3">
            {{ $payments->links() }}
        </div>

    @endif

</div>

@endsection
