<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::where('owner_id', Auth::id())
                        ->orderByDesc('created_at')
                        ->get();

        return view('owner.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('owner.warehouses.create', compact('user'));
    }

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

        // -------------------
        // Notify Admin
        // -------------------
        Notification::create([
            'user_id' => User::where('role','admin')->first()->id,
            'type' => 'warehouse',
            'message' => "New warehouse added: {$warehouse->name} by owner {$owner->name}",
        ]);

        return redirect()->route('owner.warehouses.index')
                         ->with('success', 'Warehouse submitted for approval.');
    }

    // ... edit, update, destroy methods remain same
}
