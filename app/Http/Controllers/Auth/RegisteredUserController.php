<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','string','email','max:255','unique:users'],
            'password' => ['required','confirmed', Rules\Password::defaults()],
            'role'     => ['required','in:admin,owner,customer'],
            'phone'    => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',

            'cnic' => 'required_if:role,owner|string|max:20',
            'cnic_front' => 'required_if:role,owner|image|max:2048',
            'cnic_back'  => 'required_if:role,owner|image|max:2048',
            'property_document' => 'required_if:role,owner|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        // Owner file uploads
        $cnicFront = $request->file('cnic_front')?->store('uploads/cnic', 'public');
        $cnicBack = $request->file('cnic_back')?->store('uploads/cnic', 'public');
        $property = $request->file('property_document')?->store('uploads/docs', 'public');
        $profilePhoto = $request->file('profile_photo')?->store('uploads/profile', 'public');

        $user = User::create([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'  => $validated['role'],
            'phone' => $validated['role'] === 'admin' ? null : $validated['phone'],
            'profile_photo' => $profilePhoto,
            'cnic' => $validated['role'] === 'owner' ? $validated['cnic'] : null,
            'cnic_front' => $validated['role'] === 'owner' ? $cnicFront : null,
            'cnic_back' => $validated['role'] === 'owner' ? $cnicBack : null,
            'property_document' => $validated['role'] === 'owner' ? $property : null,
            'agreement_accepted' => $validated['role'] === 'owner' ? 0 : 1,
            'is_verified' => $validated['role'] === 'owner' ? 0 : 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'owner' => !$user->agreement_accepted ? redirect()->route('owner.agreement') : redirect()->route('owner.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
        };
    }
}
