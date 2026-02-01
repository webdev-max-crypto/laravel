@extends('customer.layouts.app')

@section('content')
<div class="content">

    <h3>Edit Profile</h3>

    <!-- Success Message -->
    @if(session('success'))
        <div style="background:#16a34a; color:white; padding:10px 14px; border-radius:6px; margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div style="background:#ef4444; color:white; padding:10px 14px; border-radius:6px; margin-bottom:15px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <label>Name:</label><br>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"><br><br>

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
