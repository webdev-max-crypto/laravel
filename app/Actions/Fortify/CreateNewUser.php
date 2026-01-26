<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'password' => $this->passwordRules(),
            'role' => ['required','in:admin,owner,customer'],
            'phone' => ['nullable','string','max:255'],
            'profile_photo' => ['nullable','image','max:2048'],
            'cnic' => ['nullable','string','max:255'],
            'cnic_front' => ['nullable','file','max:4096'],
            'cnic_back' => ['nullable','file','max:4096'],
            'property_document' => ['nullable','file','max:4096'],
        ])->validate();

        // Handle file uploads
        $profilePhotoPath = isset($input['profile_photo']) ? $input['profile_photo']->store('profile_photos','public') : null;
        $cnicFrontPath = isset($input['cnic_front']) ? $input['cnic_front']->store('cnics','public') : null;
        $cnicBackPath = isset($input['cnic_back']) ? $input['cnic_back']->store('cnics','public') : null;
        $propertyDocPath = isset($input['property_document']) ? $input['property_document']->store('documents','public') : null;

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
            'phone' => $input['phone'] ?? null,
            'profile_photo' => $profilePhotoPath,
            'cnic' => $input['cnic'] ?? null,
            'cnic_front' => $cnicFrontPath,
            'cnic_back' => $cnicBackPath,
            'property_document' => $propertyDocPath,
            'agreement_accepted' => ($input['role'] === 'owner') ? 0 : 1,
            'is_verified' => ($input['role'] === 'admin') ? 1 : 0,
        ]);
    }
}
