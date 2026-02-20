<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Report;

class ReportController extends Controller
{
    // Show form to create a report for a warehouse
    public function create($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('customer.report.create', compact('warehouse'));
    }

    // Store the report
    public function store(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $warehouse = Warehouse::findOrFail($id);

        $report = new Report();
        $report->warehouse_id = $warehouse->id;
        $report->customer_id = auth()->id();
        $report->title = $request->title;
        $report->description = $request->description;
        $report->save();

        return redirect()->route('customer.history')->with('success', 'Report submitted successfully!');
    }
}
