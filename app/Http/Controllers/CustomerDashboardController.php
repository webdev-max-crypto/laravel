<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse;

class CustomerDashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        // sirf approved warehouses
        $warehouses = Warehouse::where('status', 'approved')->latest()->get();
        return view('customer.dashboard', compact('warehouses'));
    }

    // Edit Profile
    public function edit()
    {
        return view('customer.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255'
        ]);

        $user = Auth::user();
        $user->update($request->only('name','email'));

        return redirect()->route('customer.edit')->with('success','Profile updated!');
    }

    // Delete Account
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        Auth::logout();

        return redirect('/')->with('success','Account deleted!');
    }
}
