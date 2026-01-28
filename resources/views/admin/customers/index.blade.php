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
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->is_verified ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">View</a>
                    <form action="#" method="POST" class="d-inline">
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
