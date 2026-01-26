<x-guest-layout>
    <h2 class="text-2xl font-bold mb-4">Forgot Password</h2>

    @if (session('status'))
        <div class="mb-4 text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-primary-button>
                {{ __('Send Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
