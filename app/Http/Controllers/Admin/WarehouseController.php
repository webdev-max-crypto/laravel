<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    /**
     * Show all warehouses (admin index)
     * Includes both pending and approved
     */
    public function index()
    {
        $warehouses = Warehouse::with('owner')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show only pending warehouses
     */
    public function pending()
    {
        $warehouses = Warehouse::where('status', 'pending')
                               ->with('owner')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('admin.warehouses.pending', compact('warehouses'));
    }

    /**
     * Show only approved warehouses
     * Determines Active / Inactive based on last activity
     */
    public function approved()
    {
        $warehouses = Warehouse::where('status', 'approved')
                               ->with('owner')
                               ->orderBy('updated_at', 'desc')
                               ->get()
                               ->map(function($w) {
                                   // Active if last update/login ≤ 30 days, else Inactive
                                   $w->active_status = $w->updated_at->diffInDays(now()) <= 30 ? 'active' : 'inactive';
                                   return $w;
                               });

        return view('admin.warehouses.approved', compact('warehouses'));
    }

    /**
     * Show warehouse details
     */
    public function show($id)
    {
        $warehouse = Warehouse::with('owner')->findOrFail($id);

        return view('admin.warehouses.show', compact('warehouse'));
    }

    /**
     * Approve a warehouse
     */
    public function approve($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->status = 'approved';
        $warehouse->save();

        // Optional: flash message for admin
        session()->flash('success', 'Warehouse approved.');

        return redirect()->route('admin.warehouses.approved');
    }

    /**
     * Reject a warehouse
     */
    public function reject($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->status = 'rejected';
        $warehouse->save();

        return back()->with('success', 'Warehouse rejected.');
    }
}
