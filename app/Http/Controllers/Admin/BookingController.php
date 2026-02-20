<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class BookingController extends Controller
{
    // Active bookings
    public function active()
    {
        $bookings = Booking::where('status','active')->paginate(25);
        return view('admin.bookings.active', compact('bookings'));
    }

    // Expired bookings
    public function expired()
    {
        $bookings = Booking::where('status','expired')->paginate(25);
        return view('admin.bookings.expired', compact('bookings'));
    }

    // Show release payment page for a booking
    public function releasePaymentPage($id)
    {
        $booking = Booking::with('warehouse.owner')->findOrFail($id);

        $stripePaymentIntent = null;

        if ($booking->warehouse->preferred_payment_method === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $stripePaymentIntent = PaymentIntent::create([
                'amount' => $booking->total_price * 100, // convert PKR to paisa
                'currency' => 'pkr',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'owner_stripe_id' => $booking->warehouse->stripe_account_id,
                ],
            ]);
        }

        return view('admin.bookings.release_payment', compact('booking','stripePaymentIntent'));
    }

    // Handle Stripe payment
    public function payStripe(Request $request, $id)
    {
        $booking = Booking::with('warehouse.owner')->findOrFail($id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
        $paymentIntent->confirm([
            'payment_method' => $request->payment_method_id,
        ]);

        // Mark booking as released
        $booking->status = 'released';
        $booking->payment_status = 'paid';
        $booking->save();

        return redirect()->route('admin.bookings.active')
                         ->with('success', 'Payment released to owner successfully.');
    }

    // Mark as released manually (for JazzCash)
    public function release($id)
    {
        $booking = Booking::with('warehouse.owner')->findOrFail($id);

        $booking->status = 'released';
        $booking->payment_status = 'paid';
        $booking->save();

        return redirect()->back()->with('success', 'Booking released successfully.');
    }
}
