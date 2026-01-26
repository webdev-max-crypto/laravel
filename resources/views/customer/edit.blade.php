@extends('layouts.customer')

@section('content')
<div class="content">
    <h3>Edit Profile</h3>

    <form action="{{ route('customer.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <label>Name:</label>
        <input type="text" name="name" value="{{ auth()->user()->name }}"><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ auth()->user()->email }}"><br><br>

        <button type="submit" style="background:#3b82f6; color:white; padding:8px 14px; border-radius:6px;">Update</button>
    </form>

    <hr>

    <!-- Delete Account -->
    <form action="{{ route('customer.delete') }}" method="POST" style="margin-top:20px;">
        @csrf
        @method('DELETE')
        <button type="submit" style="background:#ef4444; color:white; padding:8px 14px; border-radius:6px;">
            Delete Account
        </button>
    </form>
</div>
@endsection
