@extends('admin.layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">💰 Admin Financial Dashboard</h2>

    {{-- SUMMARY CARDS --}}
    <div class="row mb-4">

        {{-- Admin Commission --}}
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h4>Admin Commission</h4>
                <p>Pending: Rs {{ number_format($adminCommissionPending, 2) }}</p>
                <p>Released: Rs {{ number_format($adminCommissionReleased, 2) }}</p>
                <p><strong>Total: Rs {{ number_format($totalAdminCommission, 2) }}</strong></p>
            </div>
        </div>

        {{-- Owner Balance --}}
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h4>Owner Balances</h4>
                <p>Pending: Rs {{ number_format($ownerBalancePending, 2) }}</p>
                <p>Released: Rs {{ number_format($ownerBalanceReleased, 2) }}</p>
                <p><strong>Total: Rs {{ number_format($totalOwnerBalance, 2) }}</strong></p>
            </div>
        </div>
    </div>

    {{-- OWNERS TABLE --}}
    <h4 class="mb-3">Owners Details</h4>

    <table class="table table-bordered shadow">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Balance (Total)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($owners as $owner)
            <tr>
                <td>{{ $owner->name }}</td>
                <td>{{ $owner->email }}</td>
                <td>Rs {{ number_format($owner->balance ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection