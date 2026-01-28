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
            @foreach($orders as $orders)
            <tr>
                <td>{{ $orders->id }}</td>
                <td>{{ $orders->name }}</td>
                <td>{{ $orders->email }}</td>
                <td>{{ $orders->is_verified ? 'Yes' : 'No' }}</td>
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
