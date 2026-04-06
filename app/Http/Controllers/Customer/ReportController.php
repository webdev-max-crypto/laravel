<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FraudReport;

class ReportController extends Controller
{
    public function create($warehouseId)
    {
        return view('customer.warehouses.report', [
            'warehouseId' => $warehouseId
        ]);
    }

    public function store(Request $request, $warehouseId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        FraudReport::create([
            'user_id'      => auth()->id(),
            'warehouse_id' => $warehouseId,
            'message'      => $request->message,
            'status'       => 'pending',
        ]);

        return redirect()->back()->with('success', 'Report submitted successfully');
    }
}