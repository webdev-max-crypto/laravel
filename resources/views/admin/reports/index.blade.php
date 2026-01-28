@extends('admin.layouts.app')

@section('content')
    <h1>All Reports</h1>

    @if(count($reports) > 0)
        <ul>
            @foreach($reports as $report)
                <li>Warehouse: {{ $report->warehouse_name ?? 'N/A' }} - Details: {{ $report->details }}</li>
            @endforeach
        </ul>
    @else
        <p>No reports found.</p>
    @endif
@endsection
