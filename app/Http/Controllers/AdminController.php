<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // Basic validation (Admin + Customer)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,owner,customer',
        ];

        // Owner-only validation
        if ($request->role === 'owner') {
            $rules['cnic'] = 'required|string|max:20';
            $rules['cnic_front'] = 'required|file|mimes:jpg,jpeg,png,pdf';
            $rules['cnic_back'] = 'required|file|mimes:jpg,jpeg,png,pdf';
            $rules['property_document'] = 'required|file|mimes:jpg,jpeg,png,pdf';
        }

        $request->validate($rules);

        // Prepare data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'agreement_accepted' => $request->role === 'owner' ? 1 : 0,
            'is_verified' => $request->role === 'owner' ? 1 : 0,
        ];

        // Profile photo (only for owner + customer)
        if ($request->hasFile('profile_photo') && $request->role !== 'admin') {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Owner attachments
        if ($request->role === 'owner') {
            $data['cnic'] = $request->cnic;
            $data['cnic_front'] = $request->file('cnic_front')->store('owners/cnic', 'public');
            $data['cnic_back'] = $request->file('cnic_back')->store('owners/cnic', 'public');
            $data['property_document'] = $request->file('property_document')->store('owners/documents', 'public');
        }

        // Create user
        $user = User::create($data);

        // Auto login
        auth()->login($user);

        // Success
        session()->flash('success', 'Account created successfully!');

        // Redirect by role
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'owner' => redirect()->route('owner.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }
}
