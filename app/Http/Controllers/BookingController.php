<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Transfer;

class BookingController extends Controller
{
    // -----------------------------
    // Customer Checkout
    // -----------------------------
    public function checkout($id)
    {
        $booking = Booking::findOrFail($id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $booking->customer->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $booking->total_price * 100,
                    'product_data' => [
                        'name' => 'Booking #' . $booking->id,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('booking.success', $booking->id),
            'cancel_url' => route('booking.cancel', $booking->id),
        ]);

        return redirect($session->url);
    }

    // -----------------------------
    // Payment Success
    // -----------------------------
    public function success(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->payment_status = 'paid';
        $booking->payment_ref = $request->get('session_id');
        $booking->owner_amount = $booking->total_price - ($booking->admin_commission ?? 0);
        $booking->save();

        return redirect()->back()->with('success', 'Payment successful. Admin can release to owner.');
    }

    // -----------------------------
    // Payment Cancel
    // -----------------------------
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->payment_status = 'unpaid';
        $booking->save();

        return redirect()->back()->with('error', 'Payment cancelled.');
    }

    // -----------------------------
    // Owner Stripe Connect Onboarding
    // -----------------------------
    public function ownerOnboard($id)
    {
        $owner = User::findOrFail($id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $account = Account::create([
            'type' => 'express',
            'email' => $owner->email,
        ]);

        $owner->stripe_account_id = $account->id;
        $owner->save();

        $link = AccountLink::create([
            'account' => $account->id,
            'refresh_url' => route('stripe.refresh'),
            'return_url' => route('stripe.success'),
            'type' => 'account_onboarding',
        ]);

        return redirect($link->url);
    }

    public function stripeRefresh() {
        return redirect()->back()->with('error', 'Please complete your Stripe onboarding.');
    }

    public function stripeSuccess() {
        return redirect()->back()->with('success', 'Stripe onboarding completed.');
    }

    // -----------------------------
    // Admin release payment to owner
    // -----------------------------
    public function releasePayment($id)
    {
        $booking = Booking::findOrFail($id);

        if($booking->payment_status !== 'paid' || !$booking->owner->stripe_account_id){
            return redirect()->back()->with('error', 'Cannot release payment.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        Transfer::create([
            'amount' => $booking->owner_amount * 100, // cents
            'currency' => 'usd',
            'destination' => $booking->owner->stripe_account_id,
        ]);

        $booking->payment_status = 'released';
        $booking->save();

        return redirect()->back()->with('success', 'Payment released to owner.');
    }

    // -----------------------------
    // Generate QR after payment
    // -----------------------------
    public function generateQr($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array($booking->payment_status, ['escrow', 'paid', 'released'])) {
            return back()->with('error', 'Payment not confirmed yet.');
        }

        $booking->update([
            'qr_code'       => Str::uuid(),
            'qr_expires_at' => $booking->end_date,
            'expires_at'    => $booking->end_date
        ]);

        return back()->with('success', 'QR generated.');
    }

    // -----------------------------
    // Warehouse confirms goods stored
    // -----------------------------
    public function confirmGoods($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'goods_confirmed' => 1
        ]);

        return back()->with('success', 'Goods storage confirmed.');
    }
}
