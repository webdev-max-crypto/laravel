@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">

    <h1 class="mb-4">Fraud Reports</h1>

    @if($reports->isEmpty())
        <div class="alert alert-warning">No fraud reports submitted.</div>
    @else

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Warehouse</th>
                    <th>Reported By</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->warehouse->name }}</td>
                        <td>{{ $report->user->name }}</td>
                        <td>{{ $report->message }}</td>
                        <td>
    @if($report->warehouse->status == 'blocked')
        <span class="badge bg-danger">Blocked</span>
    @else
        <span class="badge bg-success">Active</span>
    @endif
</td>
                        <td>
    <!-- Block Button -->
   <form action="{{ route('admin.warehouse.block', $report->warehouse->id) }}" method="POST" style="display:inline;">
    @csrf
    <button class="btn btn-warning btn-sm">Block</button>
</form>

<form action="{{ route('admin.warehouse.delete', $report->warehouse->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
</form>
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $reports->links() }}
        </div>

    @endif

</div>

@endsection