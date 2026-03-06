@extends('layouts.owner')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow text-center">
    <h2 class="text-2xl font-bold mb-2">Payout Account Setup</h2>
    <p class="text-gray-500 mb-6">Connect your Stripe account to receive payments from bookings.</p>

    @if(auth()->user()->stripe_account_status === 'active')
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            ✅ Stripe account connected & active!
        </div>
        <p>Payouts will be sent directly to your bank after admin releases payment.</p>

    @elseif(auth()->user()->stripe_account_status === 'pending')
        <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
            ⚠️ Setup incomplete. Please finish connecting your account.
        </div>
        <a href="{{ route('owner.stripe.connect') }}"
           class="bg-blue-600 text-white px-6 py-3 rounded font-bold">
            Continue Stripe Setup →
        </a>

    @else
        <a href="{{ route('owner.stripe.connect') }}"
           class="bg-blue-600 text-white px-6 py-3 rounded font-bold text-lg">
            Connect Stripe Account
        </a>
        <p class="text-sm text-gray-400 mt-3">You'll be redirected to Stripe to enter your bank details.</p>
    @endif
</div>
@endsection
