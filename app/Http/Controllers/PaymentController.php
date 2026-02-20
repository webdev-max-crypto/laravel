<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    /**
     * Owner payments page + balance
     */
    public function ownerIndex()
    {
        $ownerId = Auth::id();

        // Fetch all bookings for this owner's warehouses
        $bookings = Booking::whereHas('warehouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->with(['customer','warehouse'])
          ->orderBy('created_at','desc')
          ->get();

        // Owner balance
        $ownerBalance = Auth::user()->balance ?? 0;

        return view('owner.payments.index', compact('bookings','ownerBalance'));
    }

    /**
     * Owner balance page (separate view)
     */
    public function ownerBalance()
{
    $owner = Auth::user();

    // Fetch all bookings for this owner that are paid or released
    $payments = Booking::whereHas('warehouse', function($q) use ($owner) {
        $q->where('owner_id', $owner->id);
    })->with(['customer','warehouse'])
      ->orderBy('created_at','desc')
      ->get();

    // Calculate total received dynamically
    $totalReceived = $payments->whereIn('payment_status',['paid','released'])
                              ->sum(function($booking){
                                  return $booking->owner_amount ?? ($booking->total_price * 0.9);
                              });

    return view('owner.balances.index', compact('totalReceived','payments'));
}

    /**
     * Stripe payout for owner
     */
    public function ownerStripePay(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->whereHas('warehouse', function($q) {
                $q->where('owner_id', Auth::id());
            })->with('warehouse')
            ->firstOrFail();

        if($booking->payment_status == 'paid'){
            return back()->with('info', 'Payment already completed.');
        }

        $stripeAccountId = $booking->warehouse->stripe_account_id;

        if(!$stripeAccountId){
            return back()->with('error', 'Stripe account not linked.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => intval($booking->owner_amount * 100), // cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'transfer_data' => [
                    'destination' => $stripeAccountId,
                ],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'owner_id' => Auth::id(),
                ],
            ]);

            // Mark owner amount as paid
            $booking->payment_status = 'paid';
            $booking->save();

            // Update owner's balance
            $owner = Auth::user();
            $owner->balance = ($owner->balance ?? 0) + $booking->owner_amount;
            $owner->save();

            return back()->with('success', 'Payment sent to Stripe successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Stripe error: ' . $e->getMessage());
        }
    }

    /**
     * JazzCash payment simulation
     */
    public function ownerJazzCashPay(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->whereHas('warehouse', function($q) {
                $q->where('owner_id', Auth::id());
            })->with('warehouse')
            ->firstOrFail();

        if($booking->payment_status == 'paid'){
            return back()->with('info', 'Payment already completed.');
        }

        // Mark as paid
        $booking->payment_status = 'paid';
        $booking->save();

        // Update owner's balance
        $owner = Auth::user();
        $owner->balance = ($owner->balance ?? 0) + $booking->owner_amount;
        $owner->save();

        return back()->with('success', 'Payment marked as received via JazzCash.');
    }
}
