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
                            <span class="badge bg-warning">
                                {{ $report->status }}
                            </span>
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