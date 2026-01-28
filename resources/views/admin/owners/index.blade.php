@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Owners List</h1>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Verified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($owners as $owner)
            <tr>
                <td>{{ $owner->id }}</td>
                <td>{{ $owner->name }}</td>
                <td>{{ $owner->email }}</td>
                <td>{{ $owner->is_verified ? 'Yes' : 'No' }}</td>
                <td>
                    
                        @csrf
                        <button class="btn btn-sm btn-danger">Block</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
