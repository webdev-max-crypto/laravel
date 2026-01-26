<x-guest-layout>
    {{-- Display success message if any --}}
    @if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif
    <form method="POST" action="{{ route('admin.register') }}" enctype="multipart/form-data">

        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full"
                          type="text" name="name"
                          :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email"
                          :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Phone (Hide for Admin) -->
        <div class="mt-4 user-phone">
            <x-input-label for="phone" :value="'Phone Number'" />
            <x-text-input id="phone" class="block mt-1 w-full"
                          type="text" name="phone"
                          :value="old('phone')" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Owner Fields -->
        <div class="mt-4 owner-only" style="display:none;">
            <x-input-label for="cnic" :value="'CNIC Number'" />
            <x-text-input id="cnic" class="block mt-1 w-full"
                          type="text" name="cnic"
                          :value="old('cnic')" />
            <x-input-error :messages="$errors->get('cnic')" class="mt-2" />
        </div>

        <div class="mt-4 owner-only" style="display:none;">
            <x-input-label for="cnic_front" :value="'CNIC Front Image'" />
            <input type="file" name="cnic_front" class="block mt-1 w-full">
            <x-input-error :messages="$errors->get('cnic_front')" class="mt-2" />
        </div>

        <div class="mt-4 owner-only" style="display:none;">
            <x-input-label for="cnic_back" :value="'CNIC Back Image'" />
            <input type="file" name="cnic_back" class="block mt-1 w-full">
            <x-input-error :messages="$errors->get('cnic_back')" class="mt-2" />
        </div>

        <div class="mt-4 owner-only" style="display:none;">
            <x-input-label for="property_document" :value="'Property Document (PDF/Image)'" />
            <input type="file" name="property_document" class="block mt-1 w-full">
            <x-input-error :messages="$errors->get('property_document')" class="mt-2" />
        </div>

        <!-- Profile Photo (Hide for Admin) -->
        <div class="mt-4 user-profile">
            <x-input-label for="profile_photo" :value="'Profile Photo (optional)'" />
            <input type="file" name="profile_photo" class="block mt-1 w-full">
            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        @php
            $adminExists = \App\Models\User::where('role','admin')->exists();
        @endphp
        <div class="mt-4">
            <x-input-label for="role" :value="__('Select Role')" />
            <select name="role" id="role" class="block mt-1 w-full" required>
                @if(!$adminExists)
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                @endif
                <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="customer" {{ old('role') === 'customer' || old('role') === null ? 'selected' : '' }}>Customer</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- SCRIPT: Role-based Field Toggle -->
    <script>
        const roleSelect = document.getElementById('role');
        const ownerFields = document.querySelectorAll('.owner-only');
        const userPhone = document.querySelector('.user-phone');
        const userProfile = document.querySelector('.user-profile');

        function toggleFields() {
            const role = roleSelect.value;

            // Show/hide owner fields
            ownerFields.forEach(f => f.style.display = (role === 'owner') ? 'block' : 'none');

            // Hide phone & profile for admin
            if(role === 'admin') {
                userPhone.style.display = 'none';
                userProfile.style.display = 'none';
            } else {
                userPhone.style.display = 'block';
                userProfile.style.display = 'block';
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    </script>
</x-guest-layout>
