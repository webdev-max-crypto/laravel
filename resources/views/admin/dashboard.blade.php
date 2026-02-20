@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4 text-center">Admin Dashboard</h1>

    <div class="row">
        {{-- Total Users --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted">Total Users</h5>
                    <h2 class="fw-bold">{{ $users }}</h2>
                </div>
            </div>
        </div>

        {{-- Total Warehouses --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted">Total Warehouses</h5>
                    <h2 class="fw-bold">{{ $warehouses }}</h2>
                </div>
            </div>
        </div>

        {{-- Pending Warehouses --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted">Pending Warehouses</h5>
                    <h2 class="fw-bold">{{ $pendingWarehouses }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        {{-- Active Bookings --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted">Active Bookings</h5>
                    <h2 class="fw-bold">{{ $activeBookings }}</h2>
                </div>
            </div>
        </div>

        {{-- Total Admin Commission --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted">Total Admin Commission</h5>
                    <h2 class="fw-bold">Rs {{ number_format($totalCommission,2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    

        {{-- Released Bookings --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="text-muted">Released Bookings</h5>
                    <h2 class="fw-bold">{{ $releasedBookings }}</h2>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection