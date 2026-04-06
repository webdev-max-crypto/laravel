<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudReport;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Show all reports
    public function index()
    {
        $reports = FraudReport::with(['user', 'warehouse'])
                    ->latest()
                    ->paginate(10);

        return view('admin.reports.index', compact('reports'));
    }

    // Block a warehouse
    public function block($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update(['status' => 'blocked']);

        return back()->with('success', 'Warehouse blocked successfully');
    }

    // Delete a warehouse
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return back()->with('success', 'Warehouse deleted successfully');
    }
}