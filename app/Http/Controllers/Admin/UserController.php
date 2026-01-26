<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::paginate(25);
        return view('admin.users.index', compact('users'));
    }

    // ========================================
    // VIEW OWNER DETAILS BEFORE VERIFYING
    // ========================================
    public function verifyView($id)
    {
        $user = User::findOrFail($id);

        // Get all warehouses created by this user (owner)
        $warehouses = Warehouse::where('owner_id', $id)->get();

        return view('admin.users.verify', compact('user', 'warehouses'));
    }

    // ========================================
    // FINAL VERIFICATION
    // ========================================
    public function verifyFinal($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = 1;
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Owner verified successfully!');
    }

    // Block user
    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = 1;
        $user->save();

        return back()->with('success','User blocked.');
    }
}
