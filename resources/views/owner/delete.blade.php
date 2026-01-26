@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <div class="card p-4 text-center">

        <h3 class="text-danger">Delete Your Account?</h3>
        <p>
            Once your account is deleted, all your warehouse data, bookings, and documents will be permanently removed.
        </p>

        <form method="POST" action="{{ route('owner.delete') }}">
            @csrf

            <button class="btn btn-danger">Yes, Delete My Account</button>
            <a href="{{ route('owner.profile') }}" class="btn btn-secondary">Cancel</a>
        </form>

    </div>

</div>

@endsection
