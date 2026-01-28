<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function pending()
    {
        $warehouses = Warehouse::where('status','pending')
                               ->with('owner')
                               ->get();

        return view('admin.warehouses.pending', compact('warehouses'));
    }

    public function show($id)
    {
        $warehouse = Warehouse::with('owner')->findOrFail($id);

        return view('admin.warehouses.show', compact('warehouse'));
    }

   public function approve($id)
{
    $warehouse = Warehouse::findOrFail($id);
    $warehouse->status = 'approved';
    $warehouse->save();

    // Send message to owner dashboard
    session()->flash('warehouse_approved', true);

    return back()->with('success','Warehouse approved.');
}

    public function reject($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->status = 'rejected';
        $warehouse->save();

        return back()->with('success','Warehouse rejected.');
    }
    public function index()
    {
        $warehouses = Warehouse::all();
        $activeWarehouses = Warehouse::where('status', 'active')->get();
        return view('admin.warehouses.index', compact('warehouses', 'activeWarehouses'));
    }

}
