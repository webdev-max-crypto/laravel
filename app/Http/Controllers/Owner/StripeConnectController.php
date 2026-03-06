<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeConnectController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // Step 1: Create connected account & redirect owner to Stripe
    public function connect()
    {
        $owner = auth()->user();

        // If owner already has an account, just re-generate the link
        if (!$owner->stripe_account_id) {
            $account = Account::create([
                'type'    => 'express',
                'email'   => $owner->email,
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'metadata' => ['owner_id' => $owner->id],
            ]);

            $owner->update([
                'stripe_account_id'     => $account->id,
                'stripe_account_status' => 'pending',
            ]);
        }

        // Generate onboarding link
        $link = AccountLink::create([
            'account'     => $owner->stripe_account_id,
            'refresh_url' => route('owner.stripe.connect'),   // if they quit midway
            'return_url'  => route('owner.stripe.callback'),  // after completion
            'type'        => 'account_onboarding',
        ]);

        return redirect($link->url);
    }

    // Step 2: Owner returns after completing Stripe form
    public function callback()
    {
        $owner = auth()->user();

        // Check account status with Stripe
        $account = Account::retrieve($owner->stripe_account_id);

        if ($account->details_submitted) {
            $owner->update(['stripe_account_status' => 'active']);
            return redirect()->route('owner.dashboard')
                ->with('success', '✅ Stripe account connected! You can now receive payments.');
        }

        return redirect()->route('owner.dashboard')
            ->with('warning', '⚠️ Stripe setup incomplete. Please try again.');
    }

    // Show connect status on dashboard
    public function status()
    {
        $owner = auth()->user();
        $accountData = null;

        if ($owner->stripe_account_id) {
            $account = Account::retrieve($owner->stripe_account_id);
            $accountData = [
                'charges_enabled'  => $account->charges_enabled,
                'payouts_enabled'  => $account->payouts_enabled,
                'details_submitted'=> $account->details_submitted,
            ];
        }

        return view('owner.stripe-connect', compact('accountData'));
    }
}