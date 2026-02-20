<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Monarobase\CountryList\CountryList;

class WarehouseController extends Controller
{
    // List all warehouses
    public function index()
    {
        $warehouses = Warehouse::where('owner_id', Auth::id())
                        ->orderByDesc('created_at')
                        ->get();

        return view('owner.warehouses.index', compact('warehouses'));
    }

    // Show create form
    public function create()
    {
        $countries = (new CountryList)->getList('en'); 
        $countries = collect($countries)->sort()->toArray();
        $user = Auth::user();
        return view('owner.warehouses.create', compact('user','countries'));
    }

    // Store new warehouse
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'location'=>'required|string|max:255',
            'size'=>'nullable|string|max:255',
            'contact'=>'required|string|max:20',
            'description'=>'nullable|string|max:255',
            'address'=>'required|string',
            'total_space'=>'required|integer|min:1',
            'available_space'=>'required|integer|min:0',
            'price_per_month'=>'required|numeric|min:0', // PKR price

            'preferred_payment_method'=>'required|in:stripe,jazzcash',
            'jazzcash_number'=>'nullable|string|max:20',
            'stripe_account_id'=>'nullable|string|max:255',

            'image'=>'required|image|mimes:jpg,jpeg,png|max:5120',
            'property_doc'=>'nullable|mimes:jpg,jpeg,png,pdf|max:8192',
        ]);

        if($request->preferred_payment_method == 'jazzcash' && empty($request->jazzcash_number)){
            return back()->withErrors(['jazzcash_number'=>'JazzCash number is required'])->withInput();
        }
        if($request->preferred_payment_method == 'stripe' && empty($request->stripe_account_id)){
            return back()->withErrors(['stripe_account_id'=>'Stripe Account ID is required'])->withInput();
        }

        // Files
        if($request->hasFile('image')){
            $data['image']=$request->file('image')->store('uploads/warehouses','public');
        }
        if($request->hasFile('property_doc')){
            $data['property_doc']=$request->file('property_doc')->store('uploads/warehouses','public');
        }

        $data['owner_id']=Auth::id();
        $data['status']='pending';
        $data['last_active']=now();

        $warehouse = Warehouse::create($data);

        // Notify Admin
        $admin = User::where('role','admin')->first();
        if($admin){
            Notification::create([
    'user_id' => $admin->id,
    'type' => 'warehouse',
    'message' => "New warehouse added: {$warehouse->name} by owner " . Auth::user()->name,
    'data' => json_encode([
        'warehouse_id' => $warehouse->id,
        'warehouse_name' => $warehouse->name,
        'owner' => Auth::user()->name
    ])
]);
        }

        return redirect()->route('owner.warehouses.index')->with('success','Warehouse submitted for approval.');
    }

    // Show edit form
    public function edit($id)
    {
        $warehouse = Warehouse::where('owner_id',Auth::id())->where('id',$id)->firstOrFail();
        $countries = (new CountryList)->getList('en'); 
        $countries = collect($countries)->sort()->toArray();

        return view('owner.warehouses.edit', compact('warehouse','countries'));
    }

    // Update warehouse
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::where('owner_id',Auth::id())->where('id',$id)->firstOrFail();

        $data = $request->validate([
            'name'=>'required|string|max:255',
            'location'=>'required|string|max:255',
            'size'=>'nullable|string|max:255',
            'contact'=>'required|string|max:20',
            'description'=>'nullable|string|max:255',
            'address'=>'required|string',
            'total_space'=>'required|integer|min:1',
            'available_space'=>'required|integer|min:0',
            'price_per_month'=>'required|numeric|min:0', // PKR price

            'preferred_payment_method'=>'required|in:stripe,jazzcash',
            'jazzcash_number'=>'nullable|string|max:20',
            'stripe_account_id'=>'nullable|string|max:255',

            'image'=>'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'property_doc'=>'nullable|mimes:jpg,jpeg,png,pdf|max:8192',
        ]);

        if($request->preferred_payment_method == 'jazzcash' && empty($request->jazzcash_number)){
            return back()->withErrors(['jazzcash_number'=>'JazzCash number is required'])->withInput();
        }
        if($request->preferred_payment_method == 'stripe' && empty($request->stripe_account_id)){
            return back()->withErrors(['stripe_account_id'=>'Stripe Account ID is required'])->withInput();
        }

        if($request->hasFile('image')){
            if($warehouse->image) Storage::disk('public')->delete($warehouse->image);
            $data['image']=$request->file('image')->store('uploads/warehouses','public');
        }
        if($request->hasFile('property_doc')){
            if($warehouse->property_doc) Storage::disk('public')->delete($warehouse->property_doc);
            $data['property_doc']=$request->file('property_doc')->store('uploads/warehouses','public');
        }

        $warehouse->update($data);

        return redirect()->route('owner.warehouses.edit',$warehouse->id)->with('success','Warehouse updated successfully!');
    }

    // Delete warehouse
    public function destroy($id)
    {
        $warehouse = Warehouse::where('owner_id',Auth::id())->where('id',$id)->firstOrFail();
        if($warehouse->image) Storage::disk('public')->delete($warehouse->image);
        if($warehouse->property_doc) Storage::disk('public')->delete($warehouse->property_doc);
        $warehouse->delete();

        return redirect()->route('owner.warehouses.index')->with('success','Warehouse deleted successfully.');
    }
    // Show warehouse details for owner
public function show($id)
{
    $warehouse = Warehouse::where('owner_id', Auth::id())
                          ->where('id', $id)
                          ->firstOrFail();

    return view('owner.warehouses.show', compact('warehouse'));
}

}
