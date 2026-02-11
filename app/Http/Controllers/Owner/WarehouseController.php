<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class WarehouseController extends Controller
{
    // List all warehouses of this owner
    public function index()
    {
        $warehouses = Warehouse::where('owner_id', Auth::id())
                        ->orderByDesc('created_at')
                        ->get();

        return view('owner.warehouses.index', compact('warehouses'));
    }

    // Show warehouse creation form
    public function create()
    {
        $user = Auth::user();
        return view('owner.warehouses.create', compact('user'));
    }

    // Store new warehouse
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
            'contact' => 'required|string|max:20',
            'description' => 'nullable|string|max:255',
            'address' => 'required|string',
            'total_space' => 'required|integer|min:1',
            'available_space' => 'required|integer|min:0',
            'price_per_month' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'property_doc' => 'nullable|mimes:jpg,jpeg,png,pdf|max:8192',
        ]);

        // Handle file uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/warehouses', 'public');
        }
        if ($request->hasFile('property_doc')) {
            $data['property_doc'] = $request->file('property_doc')->store('uploads/warehouses', 'public');
        }

        $data['owner_id'] = Auth::id();
        $data['status'] = 'pending';
        $data['last_active'] = now();

        $warehouse = Warehouse::create($data);
        $owner = Auth::user();

        // Notify Admin
        Notification::create([
            'user_id' => User::where('role','admin')->first()->id,
            'type' => 'warehouse',
            'message' => "New warehouse added: {$warehouse->name} by owner {$owner->name}",
        ]);

        return redirect()->route('owner.warehouses.index')
                         ->with('success', 'Warehouse submitted for approval.');
    }

    // Show warehouse details
    public function show($id)
    {
        $warehouse = Warehouse::where('owner_id', Auth::id())
                              ->where('id', $id)
                              ->firstOrFail();

        return view('owner.warehouses.show', compact('warehouse'));
    }

    // Show edit form
    public function edit($id)
    {
        $warehouse = Warehouse::where('owner_id', Auth::id())
                              ->where('id', $id)
                              ->firstOrFail();

        return view('owner.warehouses.edit', compact('warehouse'));
    }

    // Update warehouse
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::where('owner_id', Auth::id())
                              ->where('id', $id)
                              ->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
            'contact' => 'required|string|max:20',
            'description' => 'nullable|string|max:255',
            'address' => 'required|string',
            'total_space' => 'required|integer|min:1',
            'available_space' => 'required|integer|min:0',
            'price_per_month' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'property_doc' => 'nullable|mimes:jpg,jpeg,png,pdf|max:8192',
        ]);

        // Handle new file uploads
        if ($request->hasFile('image')) {
            if ($warehouse->image) {
                Storage::disk('public')->delete($warehouse->image);
            }
            $data['image'] = $request->file('image')->store('uploads/warehouses', 'public');
        }

        if ($request->hasFile('property_doc')) {
            if ($warehouse->property_doc) {
                Storage::disk('public')->delete($warehouse->property_doc);
            }
            $data['property_doc'] = $request->file('property_doc')->store('uploads/warehouses', 'public');
        }

        $warehouse->update($data);

        return redirect()->route('owner.warehouses.index')
                         ->with('success', 'Warehouse updated successfully.');
    }

    // Delete warehouse
    public function destroy($id)
    {
        $warehouse = Warehouse::where('owner_id', Auth::id())
                              ->where('id', $id)
                              ->firstOrFail();

        // Delete associated files
        if ($warehouse->image) {
            Storage::disk('public')->delete($warehouse->image);
        }
        if ($warehouse->property_doc) {
            Storage::disk('public')->delete($warehouse->property_doc);
        }

        $warehouse->delete();

        return redirect()->route('owner.warehouses.index')
                         ->with('success', 'Warehouse deleted successfully.');
    }
}
