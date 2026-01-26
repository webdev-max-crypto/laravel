@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">

    <h2>Verify Owner</h2>

    <div class="card p-4 mt-3">

        <h4>Owner Information</h4>
        <hr>

        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
        <p><strong>CNIC:</strong> {{ $user->cnic ?? 'Not provided' }}</p>

        <p><strong>Status:</strong>
            @if($user->is_verified)
                <span class="badge bg-success">Verified</span>
            @else
                <span class="badge bg-warning text-dark">Pending</span>
            @endif
        </p>

        <hr>

        <h4>Uploaded Documents</h4>

        <div class="row mt-3">

            {{-- Profile Photo --}}
            <div class="col-md-3 text-center">
                <h6>Profile Photo</h6>
                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" class="img-fluid rounded">
                @else
                    <p class="text-muted">No Image</p>
                @endif
            </div>

            {{-- CNIC Front --}}
            <div class="col-md-3 text-center">
                <h6>CNIC Front</h6>
                @if($user->cnic_front)
                    <img src="{{ asset('storage/'.$user->cnic_front) }}" class="img-fluid rounded">
                @else
                    <p class="text-muted">Not Uploaded</p>
                @endif
            </div>

            {{-- CNIC Back --}}
            <div class="col-md-3 text-center">
                <h6>CNIC Back</h6>
                @if($user->cnic_back)
                    <img src="{{ asset('storage/'.$user->cnic_back) }}" class="img-fluid rounded">
                @else
                    <p class="text-muted">Not Uploaded</p>
                @endif
            </div>

            {{-- Property Document --}}
            <div class="col-md-3 text-center">
                <h6>Property Document</h6>
                @if($user->property_document)
                    <a href="{{ asset('storage/'.$user->property_document) }}" target="_blank" class="btn btn-sm btn-primary">
                        View Document
                    </a>
                @else
                    <p class="text-muted">Not Uploaded</p>
                @endif
            </div>

        </div>

        <hr class="mt-4">

        <div class="mt-3">
            @if(!$user->is_verified)
                <form action="{{ route('admin.users.verifyFinal', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success">Verify Owner</button>
                </form>

                <form action="{{ route('admin.users.block', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger">Reject Owner</button>
                </form>
            @else
                <div class="alert alert-success">This owner is already verified.</div>
            @endif
        </div>

    </div>
</div>

@endsection
