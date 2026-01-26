@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">
    <h1 class="mb-4">All Users</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Verified</th>
                <th>Blocked</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>

                    <td>
                        @if($user->is_verified)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-warning">No</span>
                        @endif
                    </td>

                    <td>
                        @if($user->is_blocked)
                            <span class="badge bg-danger">Blocked</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>

                    <td>

                        <!-- ⭐ Review Documents Button -->
                        <a href="{{ route('admin.users.verifyView', $user->id) }}"
                           class="btn btn-sm btn-info mb-1">
                            Review Documents
                        </a>

                        <!-- ⭐ Final Verify Button -->
                        @if(!$user->is_verified)
                            <form action="{{ route('admin.users.verifyFinal', $user->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success mb-1">Verify</button>
                            </form>
                        @endif

                        <!-- Block Button -->
                        @if(!$user->is_blocked)
                            <form action="{{ route('admin.users.block', $user->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger mb-1">Block</button>
                            </form>
                        @endif

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

</div>

@endsection
