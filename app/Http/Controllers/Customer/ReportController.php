<?php

namespace App\Http\Controllers\Customer;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ReportSubmitted;

class ReportController extends Controller
{

    // 1️⃣ Show the report form
    public function create($warehouseId)
    {
        return view('customer.warehouses.report', [
            'warehouseId' => $warehouseId
        ]);
    }

    public function store(Request $request, $warehouseId)
    {
        // 1️⃣ Save the report
        $report = auth()->user()->reports()->create([
            'warehouse_id' => $warehouseId,
            'message'      => $request->message,
            'status'       => 'pending',
        ]);

        // 2️⃣ Customer notification (optional, using built-in notify)
        auth()->user()->notify(
            new ReportSubmitted("Your report has been submitted successfully")
        );

        // 3️⃣ Admin notifications (custom insert)
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'report_submitted',
                'message' => "New report submitted by " . auth()->user()->name,
                'data'    => json_encode([
                    'report_id' => $report->id,
                    'user_id'   => auth()->user()->id
                ]),
                'is_read' => 0
                // DO NOT assign 'id' manually
            ]);
        }

        return back()->with('success', 'Report submitted successfully!');
    }
}