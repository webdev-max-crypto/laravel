<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FraudReport;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'message' => 'required'
        ]);

        FraudReport::create([
            'user_id' => auth()->id(),
            'warehouse_id' => $request->warehouse_id,
            'message' => $request->message
        ]);

        return back()->with('success', 'Report submitted successfully');
    }
}