<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Warehouse;


class DashboardController extends Controller
{
    /**
     * Admin dashboard
     */
    public function admin()
    {
        $user = Auth::user();
        abort_if($user->role !== 'admin', 403);

        return view('dashboard.admin', compact('user'));
    }

    /**
     * Owner dashboard
     */
  public function owner()
    {
        $user = Auth::user();
        abort_if($user->role !== 'owner', 403);

        if (!$user->agreement_accepted) return redirect()->route('owner.agreement');
        if (!$user->is_verified) return redirect()->route('owner.waiting');

        // Fetch owner's warehouses count
        $warehousesCount = Warehouse::where('owner_id', $user->id)->count();

        return view('dashboard.owner', compact('user','warehousesCount'));
    }



    /**
     * Customer dashboard
     */
    public function customer()
    {
        $user = Auth::user();
        abort_if($user->role !== 'customer', 403);

        return view('dashboard.customer', compact('user'));
    }

    /**
     * Owner agreement page
     */
    public function agreement()
    {
        $user = Auth::user();
        if ($user->role !== 'owner') return redirect()->route('dashboard');

        return view('owner.agreement', compact('user'));
    }

    /**
     * Owner accepts agreement
     */
    public function acceptAgreement(Request $request)
    {
        $user = Auth::user();
        $user->agreement_accepted = 1;
        $user->save();

        return redirect()->route('owner.dashboard');
    }

    /**
     * Owner profile page
     */
    public function ownerProfile()
    {
        return view('owner.profile', ['user' => Auth::user()]);
    }

    /**
     * Update owner profile
     */
    public function updateOwnerProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
              'name' => 'required|string|max:255',
    'location' => 'required|string|max:255',
    'size' => 'nullable|string|max:255',
    'contact' => 'required|string|max:20',
    'description' => 'nullable|string|max:255',
    'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
    'property_doc' => 'nullable|mimes:jpg,jpeg,png,pdf|max:8192',
    'address' => 'required|string',
    'total_space' => 'required|integer|min:1',
    'available_space' => 'required|integer|min:0',
    'price_per_month' => 'required|numeric|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('cnic_front')) {
            $user->cnic_front = $request->file('cnic_front')->store('uploads/cnic', 'public');
        }
        if ($request->hasFile('cnic_back')) {
            $user->cnic_back = $request->file('cnic_back')->store('uploads/cnic', 'public');
        }
        if ($request->hasFile('property_document')) {
            $user->property_document = $request->file('property_document')->store('uploads/docs', 'public');
        }
        if ($request->hasFile('profile_photo')) {
            $user->profile_photo = $request->file('profile_photo')->store('uploads/profile', 'public');
        }

        // Update profile info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete owner account
     */
    public function deleteAccount()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }

    /**
     * Owner help/support page
     */
    public function help()
    {
        // Fetch support tickets later if needed
        return view('owner.help.index');
    }
}
