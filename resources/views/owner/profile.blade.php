@extends('layouts.owner')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Owner Profile Settings</h2>

        <!-- Back to Dashboard Button -->
        <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="card p-4">

        <form action="{{ route('owner.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- NAME --}}
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            {{-- PHONE --}}
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
            </div>

            <hr>

            <h5>Documents</h5>

            {{-- CNIC FRONT --}}
            <div class="mb-3">
                <label class="form-label">CNIC Front</label><br>

                @if($user->cnic_front)
                    <img src="{{ asset('storage/'.$user->cnic_front) }}" width="150" class="mb-2 rounded">
                @endif

                <input type="file" name="cnic_front" class="form-control">
            </div>

            {{-- CNIC BACK --}}
            <div class="mb-3">
                <label class="form-label">CNIC Back</label><br>

                @if($user->cnic_back)
                    <img src="{{ asset('storage/'.$user->cnic_back) }}" width="150" class="mb-2 rounded">
                @endif

                <input type="file" name="cnic_back" class="form-control">
            </div>

            {{-- PROPERTY DOC --}}
            <div class="mb-3">
                <label class="form-label">Property Ownership Document</label><br>

                @if($user->property_document)
                    <a href="{{ asset('storage/'.$user->property_document) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-2">View Document</a>
                @endif

                <input type="file" name="property_document" class="form-control">
            </div>

            {{-- PROFILE PHOTO --}}
            <div class="mb-3">
                <label class="form-label">Profile Photo</label><br>

                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}" width="150" class="mb-2 rounded-circle">
                @endif

                <input type="file" name="profile_photo" class="form-control">
            </div>

            <button class="btn btn-primary">Update Profile</button>

        </form>

        <hr>

        <a href="{{ route('owner.delete.confirm') }}" class="btn btn-danger mt-3">
            Delete Account
        </a>

    </div>
</div>

@endsection
