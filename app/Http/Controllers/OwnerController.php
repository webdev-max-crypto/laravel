<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class OwnerController extends Controller
{
    // 🔹 Stripe Connect
    public function connectStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();

        // 🔸 Agar already account hai → dubara create na karo
        if ($user->stripe_account_id) {

            $accountLink = AccountLink::create([
                'account' => $user->stripe_account_id,
                'refresh_url' => route('owner.dashboard'),
                'return_url' => route('owner.dashboard'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        }

        // 🔸 New Stripe Account create
        $account = Account::create([
            'type' => 'express',
            'email' => $user->email,
        ]);

        // 🔸 Save in DB
        $user->stripe_account_id = $account->id;
        $user->save();

        // 🔸 Onboarding Link
        $accountLink = AccountLink::create([
            'account' => $account->id,
            'refresh_url' => route('owner.dashboard'),
            'return_url' => route('owner.dashboard'),
            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }
}